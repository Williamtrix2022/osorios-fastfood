<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

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
     * Mostrar lista de pedidos para el empleado
     */
    public function pedidos()
    {
        // Obtener pedidos que no están entregados ni cancelados
        $pedidos = Pedido::with('user', 'detalles.producto', 'pago')
                        ->whereIn('estado', ['pendiente', 'en_preparacion', 'listo'])
                        ->latest()
                        ->paginate(15);
        
        return view('empleado.pedidos', compact('pedidos'));
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
    $validated = $request->validate([
        'estado' => 'required|in:pendiente,en_preparacion,listo,entregado,cancelado',
    ]);

    try {
        $pedido->update([
            'estado' => $validated['estado']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Estado del pedido actualizado exitosamente',
            'pedido' => $pedido->load('user', 'detalles.producto', 'pago')
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al actualizar el estado: ' . $e->getMessage()
        ], 500);
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
 * API: Obtener todos los pedidos activos
 */

public function getPedidosActivos()
{
    $pedidos = Pedido::with('user', 'detalles.producto', 'pago')
                    ->whereIn('estado', ['pendiente', 'en_preparacion', 'listo', 'entregado'])
                    ->latest()
                    ->get();
    
    return response()->json($pedidos);
}
}

