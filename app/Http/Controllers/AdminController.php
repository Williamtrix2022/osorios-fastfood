<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Dashboard del administrador con estadísticas
     */
    public function dashboard()
    {
        // Estadísticas generales
        $totalPedidos = Pedido::count();
        $totalClientes = User::where('role', 'cliente')->count();
        $totalProductos = Producto::count();
        $ventasTotales = Pedido::where('estado', 'entregado')->sum('total');

        // Pedidos por estado
        $pedidosPorEstado = [
            'pendiente' => Pedido::where('estado', 'pendiente')->count(),
            'en_preparacion' => Pedido::where('estado', 'en_preparacion')->count(),
            'listo' => Pedido::where('estado', 'listo')->count(),
            'entregado' => Pedido::where('estado', 'entregado')->count(),
            'cancelado' => Pedido::where('estado', 'cancelado')->count(),
        ];

        // Estadísticas de pagos
        $pagosPendientes = \App\Models\Pago::where('estado_pago', 'pendiente')->count();
        $pagosCompletados = \App\Models\Pago::where('estado_pago', 'completado')->count();
        $totalRecaudado = \App\Models\Pago::where('estado_pago', 'completado')->sum('monto');

        // Productos más vendidos
        $productosMasVendidos = Producto::selectRaw('productos.*, COUNT(detalle_pedidos.id) as ventas')
                                       ->leftJoin('detalle_pedidos', 'productos.id', '=', 'detalle_pedidos.producto_id')
                                       ->groupBy('productos.id')
                                       ->orderByDesc('ventas')
                                       ->limit(5)
                                       ->get();


        // Últimos pedidos
        $ultimosPedidos = Pedido::with('user', 'pago')
                                ->latest()
                                ->limit(5)
                                ->get();

        return view('admin.dashboard', compact(
            'totalPedidos',
            'totalClientes',
            'totalProductos',
            'ventasTotales',
            'pedidosPorEstado',
            'pagosPendientes',
            'pagosCompletados',
            'totalRecaudado',
            'productosMasVendidos',
            'ultimosPedidos'
        ));
    }

    /**
     * Mostrar reportes de ventas
     */
    public function reportes()
    {
        // Obtener parámetros de filtro
        $filtroFecha = request('fecha', 'todos');
        $filtroEstado = request('estado', 'todos');

        $query = Pedido::with('user', 'pago');

        // Aplicar filtros
        if ($filtroFecha !== 'todos') {
            if ($filtroFecha === 'hoy') {
                $query->whereDate('created_at', today());
            } elseif ($filtroFecha === 'semana') {
                $query->whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ]);
            } elseif ($filtroFecha === 'mes') {
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
            }
        }

        if ($filtroEstado !== 'todos') {
            $query->where('estado', $filtroEstado);
        }

        $pedidos = $query->latest()->paginate(20);

        // Calcular totales
        $totalVentas = Pedido::sum('total');
        $ventasHoy = Pedido::whereDate('created_at', today())->sum('total');

        return view('admin.reportes', compact(
            'pedidos',
            'filtroFecha',
            'filtroEstado',
            'totalVentas',
            'ventasHoy'
        ));
    }

    /**
     * API: Obtener estadísticas para gráficos
     */
    public function getEstadisticas()
    {
        $ventasPorDia = Pedido::selectRaw('DATE(created_at) as fecha, SUM(total) as total')
                              ->groupBy('fecha')
                              ->orderBy('fecha', 'desc')
                              ->limit(7)
                              ->get();

        return response()->json([
            'ventasPorDia' => $ventasPorDia,
        ]);
    }
}
