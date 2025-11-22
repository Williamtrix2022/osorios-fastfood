@extends('layouts.app-admin')

@section('title', 'Reportes - OsoriosFoodApp')
@section('page-title', 'Reportes de Ventas')
@section('page-subtitle', 'Analiza las ventas y pedidos del negocio')

@section('content')

<!-- ===== FILTROS ===== -->
<div class="bg-gray-800 rounded-xl p-6 border-2 border-gray-700 mb-8">
    <h3 class="text-xl font-bold text-white mb-4">
        <i class="fas fa-filter text-gold mr-2"></i>Filtrar Reportes
    </h3>
    <form method="GET" action="{{ route('admin.reportes') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        
        <!-- Periodo -->
        <div>
            <label class="block text-white font-semibold mb-2">Periodo</label>
            <select name="fecha" class="w-full px-4 py-2 rounded-lg bg-gray-700 text-white border-2 border-gray-600">
                <option value="todos" {{ request('fecha') == 'todos' ? 'selected' : '' }}>Todos</option>
                <option value="hoy" {{ request('fecha') == 'hoy' ? 'selected' : '' }}>Hoy</option>
                <option value="semana" {{ request('fecha') == 'semana' ? 'selected' : '' }}>Esta Semana</option>
                <option value="mes" {{ request('fecha') == 'mes' ? 'selected' : '' }}>Este Mes</option>
            </select>
        </div>

        <!-- Estado -->
        <div>
            <label class="block text-white font-semibold mb-2">Estado</label>
            <select name="estado" class="w-full px-4 py-2 rounded-lg bg-gray-700 text-white border-2 border-gray-600">
                <option value="todos" {{ request('estado') == 'todos' ? 'selected' : '' }}>Todos</option>
                <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="en_preparacion" {{ request('estado') == 'en_preparacion' ? 'selected' : '' }}>En Preparación</option>
                <option value="entregado" {{ request('estado') == 'entregado' ? 'selected' : '' }}>Entregado</option>
                <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
            </select>
        </div>

        <!-- Botones -->
        <div class="flex items-end gap-2 md:col-span-2">
            <button type="submit" class="flex-1 px-6 py-2 rounded-lg font-bold text-white" style="background-color: var(--color-gold); color: var(--color-dark);">
                <i class="fas fa-search mr-2"></i>Filtrar
            </button>
            <a href="{{ route('admin.reportes') }}" class="px-6 py-2 rounded-lg border-2 border-gray-600 text-white font-bold hover:bg-gray-700 transition">
                <i class="fas fa-redo mr-2"></i>Limpiar
            </a>
        </div>
    </form>
</div>

<!-- ===== RESUMEN DE VENTAS ===== -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    
    <!-- Total Pedidos -->
    <div class="bg-gradient-to-br from-blue-900 to-blue-800 rounded-xl p-6 border-2 border-blue-600">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-blue-300 text-sm font-semibold uppercase">Total Pedidos</p>
                <h2 class="text-4xl font-bold text-white">{{ $pedidos->count() }}</h2>
            </div>
            <div class="bg-blue-500 w-16 h-16 rounded-full flex items-center justify-center">
                <i class="fas fa-shopping-bag text-3xl text-blue-900"></i>
            </div>
        </div>
    </div>

    <!-- Ventas Totales -->
    <div class="bg-gradient-to-br from-green-900 to-green-800 rounded-xl p-6 border-2 border-green-600">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-green-300 text-sm font-semibold uppercase">Ventas Totales</p>
                <h2 class="text-3xl font-bold text-white">${{ number_format($totalVentas, 2) }}</h2>
            </div>
            <div class="bg-green-500 w-16 h-16 rounded-full flex items-center justify-center">
                <i class="fas fa-dollar-sign text-3xl text-green-900"></i>
            </div>
        </div>
    </div>

    <!-- Ventas Hoy -->
    <div class="bg-gradient-to-br from-yellow-900 to-yellow-800 rounded-xl p-6 border-2 border-yellow-600">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-yellow-300 text-sm font-semibold uppercase">Ventas Hoy</p>
                <h2 class="text-3xl font-bold text-white">${{ number_format($ventasHoy, 2) }}</h2>
            </div>
            <div class="bg-yellow-500 w-16 h-16 rounded-full flex items-center justify-center">
                <i class="fas fa-calendar-day text-3xl text-yellow-900"></i>
            </div>
        </div>
    </div>

    <!-- Promedio por Pedido -->
    <div class="bg-gradient-to-br from-purple-900 to-purple-800 rounded-xl p-6 border-2 border-purple-600">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-purple-300 text-sm font-semibold uppercase">Promedio/Pedido</p>
                <h2 class="text-3xl font-bold text-white">${{ $pedidos->count() > 0 ? number_format($totalVentas / $pedidos->count(), 2) : '0.00' }}</h2>
            </div>
            <div class="bg-purple-500 w-16 h-16 rounded-full flex items-center justify-center">
                <i class="fas fa-chart-line text-3xl text-purple-900"></i>
            </div>
        </div>
    </div>

</div>

<!-- ===== TABLA DE PEDIDOS ===== -->
<div class="bg-gray-800 rounded-xl p-6 border-2 border-gray-700">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-2xl font-bold text-white">
            <i class="fas fa-list text-gold mr-2"></i>
            Detalle de Pedidos
        </h3>
        <span class="text-gray-400">{{ $pedidos->count() }} resultados</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-900">
                <tr>
                    <th class="text-left text-gold font-bold p-3">#</th>
                    <th class="text-left text-gold font-bold p-3">Cliente</th>
                    <th class="text-left text-gold font-bold p-3">Fecha</th>
                    <th class="text-left text-gold font-bold p-3">Productos</th>
                    <th class="text-left text-gold font-bold p-3">Total</th>
                    <th class="text-left text-gold font-bold p-3">Estado</th>
                    <th class="text-left text-gold font-bold p-3">Método Pago</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pedidos as $pedido)
                <tr class="border-b border-gray-700 hover:bg-gray-700 transition">
                    <td class="p-3 text-white font-bold">#{{ $pedido->id }}</td>
                    <td class="p-3 text-white">{{ $pedido->user->name }}</td>
                    <td class="p-3 text-gray-400 text-sm">{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                    <td class="p-3 text-gray-300">{{ $pedido->detalles->count() }}</td>
                    <td class="p-3 text-gold font-bold text-lg">${{ number_format($pedido->total, 2) }}</td>
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
                    <td class="p-3 text-gray-300">
                        @if($pedido->pago->metodo_pago == 'efectivo') 💵 Efectivo
                        @elseif($pedido->pago->metodo_pago == 'tarjeta') 💳 Tarjeta
                        @else 🏦 Transferencia
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-12">
                        <i class="fas fa-inbox text-5xl text-gray-500 mb-3 block"></i>
                        <p class="text-gray-400 text-lg">No hay datos para mostrar</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    @if($pedidos->hasPages())
    <div class="mt-6">
        {{ $pedidos->links() }}
    </div>
    @endif
</div>

@endsection

@push('css')
<style>
    select {
        background-color: #374151 !important;
        color: white !important;
    }

    select option {
        background-color: #374151;
        color: white;
    }
</style>
@endpush