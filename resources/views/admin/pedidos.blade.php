@extends('layouts.app-admin')

@section('title', 'Gestión de Pedidos - OsoriosFoodApp')
@section('page-title', 'Gestión de Pedidos')
@section('page-subtitle', 'Administra todos los pedidos del sistema')

@section('content')

<!-- ===== FILTROS ===== -->
<div class="bg-gray-800 rounded-xl p-6 border-2 border-gray-700 mb-8">
    <h3 class="text-xl font-bold text-white mb-4">
        <i class="fas fa-filter text-gold mr-2"></i>Filtros
    </h3>
    <form method="GET" action="{{ route('admin.pedidos.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Estado -->
        <div>
            <label class="block text-white font-semibold mb-2">Estado</label>
            <select name="estado" class="w-full px-4 py-2 rounded-lg bg-gray-700 text-white border-2 border-gray-600">
                <option value="">Todos los estados</option>
                <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="en_preparacion" {{ request('estado') == 'en_preparacion' ? 'selected' : '' }}>En Preparación</option>
                <option value="listo" {{ request('estado') == 'listo' ? 'selected' : '' }}>Listo</option>
                <option value="entregado" {{ request('estado') == 'entregado' ? 'selected' : '' }}>Entregado</option>
                <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
            </select>
        </div>

        <!-- Fecha -->
        <div>
            <label class="block text-white font-semibold mb-2">Fecha</label>
            <input type="date" 
                   name="fecha" 
                   value="{{ request('fecha') }}"
                   class="w-full px-4 py-2 rounded-lg bg-gray-700 text-white border-2 border-gray-600">
        </div>

        <!-- Botón -->
        <div class="flex items-end">
            <button type="submit" class="w-full px-6 py-2 rounded-lg font-bold text-white" style="background-color: var(--color-gold); color: var(--color-dark);">
                <i class="fas fa-search mr-2"></i>Filtrar
            </button>
        </div>
    </form>
</div>

<!-- ===== ESTADÍSTICAS RÁPIDAS ===== -->
<div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
    <div class="bg-gray-800 rounded-lg p-4 border-2 border-gray-700 text-center">
        <p class="text-gray-400 text-sm">Total</p>
        <p class="text-3xl font-bold text-white">{{ $pedidos->total() }}</p>
    </div>
    <div class="bg-yellow-900 bg-opacity-30 rounded-lg p-4 border-2 border-yellow-600 text-center">
        <p class="text-yellow-300 text-sm">Pendientes</p>
        <p class="text-3xl font-bold text-white">{{ $pedidos->where('estado', 'pendiente')->count() }}</p>
    </div>
    <div class="bg-blue-900 bg-opacity-30 rounded-lg p-4 border-2 border-blue-600 text-center">
        <p class="text-blue-300 text-sm">En Preparación</p>
        <p class="text-3xl font-bold text-white">{{ $pedidos->where('estado', 'en_preparacion')->count() }}</p>
    </div>
    <div class="bg-green-900 bg-opacity-30 rounded-lg p-4 border-2 border-green-600 text-center">
        <p class="text-green-300 text-sm">Entregados</p>
        <p class="text-3xl font-bold text-white">{{ $pedidos->where('estado', 'entregado')->count() }}</p>
    </div>
    <div class="bg-purple-900 bg-opacity-30 rounded-lg p-4 border-2 border-purple-600 text-center">
        <p class="text-purple-300 text-sm">Total Ventas</p>
        <p class="text-2xl font-bold text-gold">${{ number_format($pedidos->where('estado', 'entregado')->sum('total'), 2) }}</p>
    </div>
</div>

<!-- ===== TABLA DE PEDIDOS ===== -->
<div class="bg-gray-800 rounded-xl border-2 border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-900">
                <tr>
                    <th class="text-left text-gold font-bold p-4">#</th>
                    <th class="text-left text-gold font-bold p-4">Cliente</th>
                    <th class="text-left text-gold font-bold p-4">Fecha</th>
                    <th class="text-left text-gold font-bold p-4">Total</th>
                    <th class="text-left text-gold font-bold p-4">Método Pago</th>
                    <th class="text-left text-gold font-bold p-4">Estado</th>
                    <th class="text-left text-gold font-bold p-4">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pedidos as $pedido)
                <tr class="border-b border-gray-700 hover:bg-gray-700 transition">
                    <!-- ID -->
                    <td class="p-4">
                        <span class="text-white font-bold text-lg">#{{ $pedido->id }}</span>
                    </td>

                    <!-- Cliente -->
                    <td class="p-4">
                        <p class="text-white font-semibold">{{ $pedido->user->name }}</p>
                        <p class="text-gray-400 text-sm">{{ $pedido->user->email }}</p>
                    </td>

                    <!-- Fecha -->
                    <td class="p-4">
                        <p class="text-white">{{ $pedido->created_at->format('d/m/Y') }}</p>
                        <p class="text-gray-400 text-sm">{{ $pedido->created_at->format('H:i A') }}</p>
                    </td>

                    <!-- Total -->
                    <td class="p-4">
                        <span class="text-gold font-bold text-xl">${{ number_format($pedido->total, 2) }}</span>
                        <p class="text-gray-400 text-sm">{{ $pedido->detalles->count() }} productos</p>
                    </td>

                    <!-- Método de Pago -->
                    <td class="p-4">
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            @if($pedido->pago->metodo_pago == 'efectivo') bg-green-900 text-green-300
                            @elseif($pedido->pago->metodo_pago == 'tarjeta') bg-blue-900 text-blue-300
                            @else bg-purple-900 text-purple-300
                            @endif">
                            @if($pedido->pago->metodo_pago == 'efectivo') 💵 Efectivo
                            @elseif($pedido->pago->metodo_pago == 'tarjeta') 💳 Tarjeta
                            @else 🏦 Transferencia
                            @endif
                        </span>
                    </td>

                    <!-- Estado -->
                    <td class="p-4">
                        <span class="px-3 py-1 rounded-full text-sm font-bold
                            @if($pedido->estado == 'pendiente') bg-yellow-900 text-yellow-300
                            @elseif($pedido->estado == 'en_preparacion') bg-blue-900 text-blue-300
                            @elseif($pedido->estado == 'listo') bg-purple-900 text-purple-300
                            @elseif($pedido->estado == 'entregado') bg-green-900 text-green-300
                            @else bg-red-900 text-red-300
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $pedido->estado)) }}
                        </span>
                    </td>

                    <!-- Acciones -->
                    <td class="p-4">
                        <a href="{{ route('admin.pedidos.show', $pedido->id) }}" 
                           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded transition inline-block">
                            <i class="fas fa-eye mr-1"></i>Ver
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-12">
                        <i class="fas fa-inbox text-5xl text-gray-500 mb-3 block"></i>
                        <p class="text-gray-400 text-lg">No hay pedidos</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    @if($pedidos->hasPages())
    <div class="p-4 bg-gray-900">
        {{ $pedidos->links() }}
    </div>
    @endif
</div>

@endsection

@push('css')
<style>
    input[type="date"],
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