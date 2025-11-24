<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmpleadoController extends Controller
{
    /**
     * Dashboard del empleado con resumen de pedidos
     */
    public function dashboard()
    {
        // Contar pedidos por estado
        $pendientes = Pedido::where('estado', 'pendiente')->count();
        $enPreparacion = Pedido::where('estado', 'en_preparacion')->count();
        $listos = Pedido::where('estado', 'listo')->count();

        // Obtener últimos pedidos
        $ultimosPedidos = Pedido::with('user', 'detalles.producto')
                                ->latest()
                                ->limit(5)
                                ->get();

        return view('empleado.dashboard', compact(
            'pendientes',
            'enPreparacion',
            'listos',
            'ultimosPedidos'
        ));
    }

    /**
     * Mostrar lista de pedidos para el empleado (optimizado)
     */
    public function pedidos()
    {
        // Obtener pedidos que no están entregados ni cancelados (últimas 48 horas)
        $pedidos = Pedido::with([
                'user:id,name,email',
                'detalles:id,pedido_id,producto_id,cantidad,precio_unitario,subtotal',
                'detalles.producto:id,nombre,precio',
                'pago:id,pedido_id,metodo_pago,estado_pago,monto'
            ])
            ->whereIn('estado', ['pendiente', 'en_preparacion', 'listo'])
            ->where('created_at', '>=', now()->subHours(48))
            ->latest()
            ->paginate(15);

        return view('empleado.pedidos', compact('pedidos'));
    }

    /**
     * Mostrar pedidos completados (entregados y cancelados de hoy)
     */
    public function pedidosCompletados()
    {
        // Obtener pedidos completados solo de hoy
        $pedidos = Pedido::with([
                'user:id,name,email',
                'detalles:id,pedido_id,producto_id,cantidad,precio_unitario,subtotal',
                'detalles.producto:id,nombre,precio',
                'pago:id,pedido_id,metodo_pago,estado_pago,monto'
            ])
            ->whereIn('estado', ['entregado', 'cancelado'])
            ->whereDate('updated_at', today())
            ->latest('updated_at')
            ->paginate(20);

        return view('empleado.pedidos-completados', compact('pedidos'));
    }

    /**
     * Mostrar detalles de un pedido
     */
    public function verPedido($id)
    {
        $pedido = Pedido::with('user', 'detalles.producto', 'pago')
                       ->findOrFail($id);

        return view('empleado.pedido-detalle', compact('pedido'));
    }

    /**
 * Cambiar estado de un pedido
 */
public function cambiarEstado(Request $request, Pedido $pedido)
{
    // ⚠️ VALIDACIÓN: No permitir cambios en pedidos ya completados
    if (in_array($pedido->estado, ['entregado', 'cancelado'])) {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede cambiar el estado de un pedido ya ' . ($pedido->estado === 'entregado' ? 'entregado' : 'cancelado')
            ], 403);
        }

        return redirect()->back()->with('error', 'No se puede cambiar el estado de un pedido ya completado');
    }

    $validated = $request->validate([
        'estado' => 'required|in:pendiente,en_preparacion,listo,entregado,cancelado',
    ]);

    try {
        DB::beginTransaction();

        $pedido->update([
            'estado' => $validated['estado']
        ]);

        // Actualizar automáticamente el estado del pago
        if ($validated['estado'] === 'entregado' && $pedido->pago) {
            $pedido->pago->update([
                'estado_pago' => 'completado'
            ]);
        }

        if ($validated['estado'] === 'cancelado' && $pedido->pago) {
            $pedido->pago->update([
                'estado_pago' => 'rechazado'
            ]);
        }

        DB::commit();

        // Si es AJAX
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Estado del pedido actualizado exitosamente',
                'pedido' => $pedido->load('user', 'detalles.producto', 'pago')
            ]);
        }

        return redirect()->back()->with('success', 'Estado del pedido actualizado exitosamente');

    } catch (\Exception $e) {
        DB::rollBack();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estado: ' . $e->getMessage()
            ], 500);
        }

        return redirect()->back()->with('error', 'Error al actualizar el estado');
    }
}

    /**
     * API: Obtener pedidos pendientes
     */
    public function getPedidosPendientes()
    {
        $pedidos = Pedido::where('estado', 'pendiente')
                        ->with('user', 'detalles.producto')
                        ->latest()
                        ->get();

        return response()->json($pedidos);
    }

    /**
     * API: Obtener pedidos en preparación
     */
    public function getPedidosEnPreparacion()
    {
        $pedidos = Pedido::where('estado', 'en_preparacion')
                        ->with('user', 'detalles.producto')
                        ->latest()
                        ->get();

        return response()->json($pedidos);
    }


/**
 * API: Obtener todos los pedidos activos (optimizado)
 */
public function getPedidosActivos()
{
    // Solo cargar pedidos de las últimas 24 horas para mejorar rendimiento
    $pedidos = Pedido::with([
            'user:id,name,email',  // Solo campos necesarios
            'detalles' => function($query) {
                $query->select('id', 'pedido_id', 'producto_id', 'cantidad', 'precio_unitario', 'subtotal');
            },
            'detalles.producto:id,nombre,precio,imagen',  // Solo campos necesarios
            'pago:id,pedido_id,metodo_pago,estado_pago,monto'
        ])
        ->whereIn('estado', ['pendiente', 'en_preparacion', 'listo'])
        ->where('created_at', '>=', now()->subHours(24))  // Solo últimas 24 horas
        ->latest()
        ->limit(50)  // Máximo 50 pedidos
        ->get();

    return response()->json($pedidos);
}

/**
 * API: Obtener pedidos completados del día
 */
public function getPedidosCompletadosAPI()
{
    $pedidos = Pedido::with([
            'user:id,name,email',
            'detalles' => function($query) {
                $query->select('id', 'pedido_id', 'producto_id', 'cantidad', 'precio_unitario', 'subtotal');
            },
            'detalles.producto:id,nombre,precio,imagen',
            'pago:id,pedido_id,metodo_pago,estado_pago,monto'
        ])
        ->whereIn('estado', ['entregado', 'cancelado'])
        ->whereDate('updated_at', today())
        ->latest('updated_at')
        ->limit(100)
        ->get();

    return response()->json($pedidos);
}
}

