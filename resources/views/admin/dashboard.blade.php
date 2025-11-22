@extends('layouts.app-admin')

@section('title', 'Dashboard Admin - OsoriosFoodApp')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Vista general del sistema')

@section('content')

<!-- ===== TARJETAS DE ESTADÍSTICAS ===== -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Total Pedidos -->
    <div class="stat-card bg-gradient-to-br from-blue-900 to-blue-800 rounded-xl p-6 border-2 border-blue-600">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-blue-300 text-sm font-semibold uppercase">Total Pedidos</p>
                <h2 class="text-4xl font-bold text-white">{{ $totalPedidos ?? 0 }}</h2>
            </div>
            <div class="bg-blue-500 w-16 h-16 rounded-full flex items-center justify-center">
                <i class="fas fa-shopping-bag text-3xl text-blue-900"></i>
            </div>
        </div>
        <p class="text-blue-200 text-sm">
            <i class="fas fa-arrow-up mr-1"></i>Todos los pedidos registrados
        </p>
    </div>

    <!-- Total Clientes -->
    <div class="stat-card bg-gradient-to-br from-green-900 to-green-800 rounded-xl p-6 border-2 border-green-600">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-green-300 text-sm font-semibold uppercase">Clientes</p>
                <h2 class="text-4xl font-bold text-white">{{ $totalClientes ?? 0 }}</h2>
            </div>
            <div class="bg-green-500 w-16 h-16 rounded-full flex items-center justify-center">
                <i class="fas fa-users text-3xl text-green-900"></i>
            </div>
        </div>
        <p class="text-green-200 text-sm">
            <i class="fas fa-user-plus mr-1"></i>Usuarios registrados
        </p>
    </div>

    <!-- Total Productos -->
    <div class="stat-card bg-gradient-to-br from-purple-900 to-purple-800 rounded-xl p-6 border-2 border-purple-600">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-purple-300 text-sm font-semibold uppercase">Productos</p>
                <h2 class="text-4xl font-bold text-white">{{ $totalProductos ?? 0 }}</h2>
            </div>
            <div class="bg-purple-500 w-16 h-16 rounded-full flex items-center justify-center">
                <i class="fas fa-hamburger text-3xl text-purple-900"></i>
            </div>
        </div>
        <p class="text-purple-200 text-sm">
            <i class="fas fa-box mr-1"></i>En el menú
        </p>
    </div>

    <!-- Ventas Totales -->
    <div class="stat-card bg-gradient-to-br from-yellow-900 to-yellow-800 rounded-xl p-6 border-2 border-yellow-600">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-yellow-300 text-sm font-semibold uppercase">Ventas Totales</p>
                <h2 class="text-4xl font-bold text-white">${{ number_format($ventasTotales ?? 0, 2) }}</h2>
            </div>
            <div class="bg-yellow-500 w-16 h-16 rounded-full flex items-center justify-center">
                <i class="fas fa-dollar-sign text-3xl text-yellow-900"></i>
            </div>
        </div>
        <p class="text-yellow-200 text-sm">
            <i class="fas fa-chart-line mr-1"></i>Pedidos entregados
        </p>
    </div>

</div>

<!-- ===== FILA 2: GRÁFICOS Y TABLAS ===== -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    
    <!-- Pedidos por Estado -->
    <div class="bg-gray-800 rounded-xl p-6 border-2 border-gray-700">
        <h3 class="text-2xl font-bold text-white mb-6">
            <i class="fas fa-chart-pie" style="color: var(--color-gold);"></i>
            Pedidos por Estado
        </h3>
        <div class="space-y-4">
            @if(isset($pedidosPorEstado))
                <div class="flex items-center justify-between p-4 bg-yellow-900 bg-opacity-30 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-hourglass-start text-yellow-900"></i>
                        </div>
                        <span class="text-white font-semibold">Pendientes</span>
                    </div>
                    <span class="text-3xl font-bold text-yellow-400">{{ $pedidosPorEstado['pendiente'] ?? 0 }}</span>
                </div>

                <div class="flex items-center justify-between p-4 bg-blue-900 bg-opacity-30 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-chef text-blue-900"></i>
                        </div>
                        <span class="text-white font-semibold">En Preparación</span>
                    </div>
                    <span class="text-3xl font-bold text-blue-400">{{ $pedidosPorEstado['en_preparacion'] ?? 0 }}</span>
                </div>

                <div class="flex items-center justify-between p-4 bg-purple-900 bg-opacity-30 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-purple-900"></i>
                        </div>
                        <span class="text-white font-semibold">Listos</span>
                    </div>
                    <span class="text-3xl font-bold text-purple-400">{{ $pedidosPorEstado['listo'] ?? 0 }}</span>
                </div>

                <div class="flex items-center justify-between p-4 bg-green-900 bg-opacity-30 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-truck text-green-900"></i>
                        </div>
                        <span class="text-white font-semibold">Entregados</span>
                    </div>
                    <span class="text-3xl font-bold text-green-400">{{ $pedidosPorEstado['entregado'] ?? 0 }}</span>
                </div>

                <div class="flex items-center justify-between p-4 bg-red-900 bg-opacity-30 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-times-circle text-red-900"></i>
                        </div>
                        <span class="text-white font-semibold">Cancelados</span>
                    </div>
                    <span class="text-3xl font-bold text-red-400">{{ $pedidosPorEstado['cancelado'] ?? 0 }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Productos Más Vendidos -->
    <div class="bg-gray-800 rounded-xl p-6 border-2 border-gray-700">
        <h3 class="text-2xl font-bold text-white mb-6">
            <i class="fas fa-fire" style="color: var(--color-gold);"></i>
            Productos Más Vendidos
        </h3>
        <div class="space-y-4">
            @if(isset($productosMasVendidos) && $productosMasVendidos->count() > 0)
                @foreach($productosMasVendidos as $producto)
                <div class="flex items-center justify-between p-4 bg-gray-700 rounded-lg hover:bg-gray-600 transition">
                    <div class="flex-1">
                        <h4 class="text-white font-semibold">{{ $producto->nombre }}</h4>
                        <p class="text-gray-400 text-sm">${{ number_format($producto->precio, 2) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-gold font-bold text-xl">{{ $producto->ventas ?? 0 }}</p>
                        <p class="text-gray-400 text-sm">vendidos</p>
                    </div>
                </div>
                @endforeach
            @else
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-inbox text-4xl mb-3 block"></i>
                    <p>No hay datos de ventas aún</p>
                </div>
            @endif
        </div>
    </div>

</div>

<!-- ===== ÚLTIMOS PEDIDOS ===== -->
<div class="bg-gray-800 rounded-xl p-6 border-2 border-gray-700">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-2xl font-bold text-white">
            <i class="fas fa-receipt" style="color: var(--color-gold);"></i>
            Últimos Pedidos
        </h3>
        <a href="{{ route('admin.pedidos.index') }}" class="text-gold hover:text-accent transition font-semibold">
            Ver todos <i class="fas fa-arrow-right ml-2"></i>
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-700">
                    <th class="text-left text-gray-400 font-semibold p-3">#</th>
                    <th class="text-left text-gray-400 font-semibold p-3">Cliente</th>
                    <th class="text-left text-gray-400 font-semibold p-3">Fecha</th>
                    <th class="text-left text-gray-400 font-semibold p-3">Total</th>
                    <th class="text-left text-gray-400 font-semibold p-3">Estado</th>
                    <th class="text-left text-gray-400 font-semibold p-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($ultimosPedidos) && $ultimosPedidos->count() > 0)
                    @foreach($ultimosPedidos as $pedido)
                    <tr class="border-b border-gray-700 hover:bg-gray-700 transition">
                        <td class="p-3 text-white font-bold">#{{ $pedido->id }}</td>
                        <td class="p-3 text-white">{{ $pedido->user->name }}</td>
                        <td class="p-3 text-gray-400 text-sm">{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                        <td class="p-3 text-gold font-bold">${{ number_format($pedido->total, 2) }}</td>
                        <td class="p-3">
                            <span class="px-3 py-1 rounded-full text-xs font-bold
                                @if($pedido->estado == 'pendiente') bg-yellow-900 text-yellow-300
                                @elseif($pedido->estado == 'en_preparacion') bg-blue-900 text-blue-300
                                @elseif($pedido->estado == 'listo') bg-purple-900 text-purple-300
                                @elseif($pedido->estado == 'entregado') bg-green-900 text-green-300
                                @else bg-red-900 text-red-300
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $pedido->estado)) }}
                            </span>
                        </td>
                        <td class="p-3">
                            <a href="{{ route('admin.pedidos.show', $pedido->id) }}" class="text-gold hover:text-accent transition">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-400">
                            <i class="fas fa-inbox text-4xl mb-3 block"></i>
                            <p>No hay pedidos registrados</p>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('css')
<style>
    .stat-card {
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(212, 175, 55, 0.3);
    }

    table {
        border-collapse: separate;
        border-spacing: 0;
    }
</style>
@endpush

