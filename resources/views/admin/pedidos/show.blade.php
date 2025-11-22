@extends('layouts.app-admin')

@section('title', 'Detalle del Pedido - OsoriosFoodApp')
@section('page-title', 'Pedido #' . $pedido->id)
@section('page-subtitle', 'Información completa del pedido')

@section('content')

<!-- Breadcrumb -->
<div class="mb-8">
    <a href="{{ route('admin.pedidos.index') }}" class="text-gold hover:text-accent transition">
        <i class="fas fa-arrow-left mr-2"></i>Volver a Pedidos
    </a>
</div>

<!-- Grid 2 columnas -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- COLUMNA IZQUIERDA (2/3) -->
    <div class="lg:col-span-2 space-y-8">
        
        <!-- Info del Pedido -->
        <div class="bg-gray-800 rounded-xl p-6 border-2 border-gray-700">
            <h3 class="text-2xl font-bold text-white mb-6 flex items-center gap-2">
                <i class="fas fa-info-circle text-gold"></i>
                Información del Pedido
            </h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-400 text-sm mb-1">Número de Pedido</p>
                    <p class="text-white font-bold text-xl">#{{ $pedido->id }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Estado</p>
                    <span class="px-4 py-2 rounded-full text-sm font-bold inline-block
                        @if($pedido->estado == 'pendiente') bg-yellow-900 text-yellow-300
                        @elseif($pedido->estado == 'en_preparacion') bg-blue-900 text-blue-300
                        @elseif($pedido->estado == 'listo') bg-purple-900 text-purple-300
                        @elseif($pedido->estado == 'entregado') bg-green-900 text-green-300
                        @else bg-red-900 text-red-300
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $pedido->estado)) }}
                    </span>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Fecha</p>
                    <p class="text-white font-semibold">{{ $pedido->created_at->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Hora</p>
                    <p class="text-white font-semibold">{{ $pedido->created_at->format('H:i A') }}</p>
                </div>
            </div>

            @if($pedido->notas)
            <div class="mt-6 p-4 bg-yellow-900 bg-opacity-20 border-l-4 border-yellow-500 rounded">
                <p class="text-yellow-300 font-semibold mb-2">
                    <i class="fas fa-sticky-note mr-2"></i>Notas del Cliente:
                </p>
                <p class="text-white">{{ $pedido->notas }}</p>
            </div>
            @endif
        </div>

        <!-- Productos -->
        <div class="bg-gray-800 rounded-xl p-6 border-2 border-gray-700">
            <h3 class="text-2xl font-bold text-white mb-6 flex items-center gap-2">
                <i class="fas fa-shopping-bag text-gold"></i>
                Productos ({{ $pedido->detalles->count() }})
            </h3>
            <div class="space-y-4">
                @foreach($pedido->detalles as $detalle)
                <div class="flex justify-between items-center p-4 bg-gray-700 rounded-lg">
                    <div class="flex-1">
                        <h4 class="text-white font-bold">{{ $detalle->producto->nombre }}</h4>
                        <p class="text-gray-400 text-sm">Cantidad: {{ $detalle->cantidad }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-gold font-bold text-lg">${{ number_format($detalle->subtotal, 2) }}</p>
                        <p class="text-gray-400 text-sm">@${{ number_format($detalle->precio_unitario, 2) }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Total -->
            <div class="mt-6 pt-6 border-t-2 border-gray-600">
                <div class="flex justify-between items-center">
                    <span class="text-white text-xl font-bold">Total del Pedido:</span>
                    <span class="text-gold text-3xl font-bold">${{ number_format($pedido->total, 2) }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- COLUMNA DERECHA (1/3) -->
    <div class="space-y-8">
        
        <!-- Info Cliente -->
        <div class="bg-gray-800 rounded-xl p-6 border-2 border-gray-700">
            <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                <i class="fas fa-user text-gold"></i>
                Cliente
            </h3>
            <div class="space-y-3">
                <div>
                    <p class="text-gray-400 text-sm">Nombre</p>
                    <p class="text-white font-semibold">{{ $pedido->user->name }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Email</p>
                    <p class="text-white">{{ $pedido->user->email }}</p>
                </div>
                @if($pedido->user->telefono)
                <div>
                    <p class="text-gray-400 text-sm">Teléfono</p>
                    <p class="text-white">{{ $pedido->user->telefono }}</p>
                </div>
                @endif
                @if($pedido->user->direccion)
                <div>
                    <p class="text-gray-400 text-sm">Dirección</p>
                    <p class="text-white">{{ $pedido->user->direccion }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Info Pago -->
        <div class="bg-gray-800 rounded-xl p-6 border-2 border-gray-700">
            <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                <i class="fas fa-credit-card text-gold"></i>
                Pago
            </h3>
            <div class="space-y-3">
                <div>
                    <p class="text-gray-400 text-sm">Método</p>
                    <p class="text-white font-semibold">
                        @if($pedido->pago->metodo_pago == 'efectivo') 💵 Efectivo
                        @elseif($pedido->pago->metodo_pago == 'tarjeta') 💳 Tarjeta
                        @else 🏦 Transferencia
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Estado del Pago</p>
                    <span class="px-3 py-1 rounded-full text-sm font-bold
                        {{ $pedido->pago->estado_pago == 'completado' ? 'bg-green-900 text-green-300' : 'bg-yellow-900 text-yellow-300' }}">
                        {{ $pedido->pago->estado_pago == 'completado' ? '✓ Completado' : '⏳ Pendiente' }}
                    </span>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Monto</p>
                    <p class="text-gold font-bold text-xl">${{ number_format($pedido->pago->monto, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Cambiar Estado -->
        <div class="bg-gray-800 rounded-xl p-6 border-2 border-gray-700">
            <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                <i class="fas fa-exchange-alt text-gold"></i>
                Cambiar Estado
            </h3>
            <form action="{{ route('admin.pedidos.update', $pedido->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <select name="estado" class="w-full px-4 py-3 rounded-lg bg-gray-700 text-white border-2 border-gray-600 mb-4">
                    <option value="pendiente" {{ $pedido->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="en_preparacion" {{ $pedido->estado == 'en_preparacion' ? 'selected' : '' }}>En Preparación</option>
                    <option value="listo" {{ $pedido->estado == 'listo' ? 'selected' : '' }}>Listo</option>
                    <option value="entregado" {{ $pedido->estado == 'entregado' ? 'selected' : '' }}>Entregado</option>
                    <option value="cancelado" {{ $pedido->estado == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                </select>
                <button type="submit" class="w-full py-3 rounded-lg font-bold text-white transition" style="background-color: var(--color-gold); color: var(--color-dark);">
                    <i class="fas fa-save mr-2"></i>Actualizar Estado
                </button>
            </form>
        </div>

    </div>

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