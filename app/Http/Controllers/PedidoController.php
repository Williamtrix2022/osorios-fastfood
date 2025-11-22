<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{
    /**
     * Mostrar lista de pedidos (Admin)
     */
    public function index(Request $request)
{
    $query = Pedido::with('user', 'pago', 'detalles.producto');

    // Filtro por estado
    if ($request->filled('estado') && $request->estado != '') {
        $query->where('estado', $request->estado);
    }

    // Filtro por fecha (fecha específica del input date)
    if ($request->filled('fecha')) {
        $query->whereDate('created_at', $request->fecha);
    }

    $pedidos = $query->latest()->paginate(15);

    // Mantener los parámetros de búsqueda en la paginación
    $pedidos->appends($request->all());

    return view('admin.pedidos', compact('pedidos'));
}

    /**
     * Mostrar detalles de un pedido
     */
    public function show(Pedido $pedido)
    {
        // Cargar relaciones
        $pedido->load('user', 'pago', 'detalles.producto');
        
        return view('admin.pedidos.show', compact('pedido'));
    }

    /**
     * Crear nuevo pedido (Cliente)
     */
    public function store(Request $request)
    {
        // Validar que el carrito tenga items
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.producto_id' => 'required|exists:productos,id',
            'items.*.cantidad' => 'required|integer|min:1',
            'items.*.precio_unitario' => 'required|numeric|min:0.01',
            'notas' => 'nullable|string|max:500',
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia',
        ]);

        try {
            DB::beginTransaction();

            // Calcular total del pedido
            $total = 0;
            foreach ($validated['items'] as $item) {
                $total += $item['cantidad'] * $item['precio_unitario'];
            }

            // Crear el pedido
            $pedido = Pedido::create([
                'user_id' => Auth::id(),
                'total' => $total,
                'estado' => 'pendiente',
                'notas' => $validated['notas'] ?? null,
            ]);

            // Crear detalles del pedido
            foreach ($validated['items'] as $item) {
                $subtotal = $item['cantidad'] * $item['precio_unitario'];
                
                DetallePedido::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $item['producto_id'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'subtotal' => $subtotal,
                ]);
            }

            // Crear registro de pago
            Pago::create([
                'pedido_id' => $pedido->id,
                'metodo_pago' => $validated['metodo_pago'],
                'estado_pago' => $validated['metodo_pago'] === 'efectivo' ? 'pendiente' : 'completado',
                'monto' => $total,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pedido creado exitosamente',
                'pedido_id' => $pedido->id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el pedido: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Actualizar estado del pedido (Empleado o Admin)
     */
    public function update(Request $request, Pedido $pedido)
    {
        $validated = $request->validate([
            'estado' => 'required|in:pendiente,en_preparacion,listo,entregado,cancelado',
        ]);

        $pedido->update($validated);

        // Si es una petición AJAX, devolver JSON
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Estado del pedido actualizado',
                'estado' => $pedido->estado,
            ]);
        }

        return redirect()->back()->with('success', 'Estado del pedido actualizado');
    }

    /**
     * Eliminar pedido (solo Admin)
     */
    public function destroy(Pedido $pedido)
    {
        $pedido->delete();

        return redirect()->route('admin.pedidos.index')
                        ->with('success', 'Pedido eliminado exitosamente');
    }

    /**
     * API: Obtener pedidos del cliente autenticado
     */
    public function getMisPedidos()
    {
       /** @var \App\Models\User $user */
       $user = Auth::user();

       $pedidos = $user->pedidos()
                            ->with('detalles.producto', 'pago')
                            ->latest()
                            ->get();

                    return response()->json($pedidos);
    }

    /**
     * API: Obtener detalles de un pedido específico
     */
    public function getPedidoDetalle(Pedido $pedido)
    {
        // Verificar que el pedido pertenezca al usuario autenticado
        if ($pedido->user_id !== Auth::id() && Auth::user()->role === 'cliente') {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $pedido->load('user', 'pago', 'detalles.producto');
        
        return response()->json($pedido);
    }

    /**
     * API: Obtener todos los pedidos (Empleado)
     */
    public function getPedidosEmpleado()
    {
        $pedidos = Pedido::with('user', 'pago', 'detalles.producto')
                        ->whereIn('estado', ['pendiente', 'en_preparacion', 'listo'])
                        ->latest()
                        ->get();
        
        return response()->json($pedidos);
    }
}