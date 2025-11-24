@extends('layouts.app-admin')

@section('title', 'Detalle del Pago - OsoriosFoodApp')
@section('page-title', 'Pago #' . $pago->id)
@section('page-subtitle', 'Información completa del pago')

@section('content')

<!-- Breadcrumb -->
<div class="mb-8">
    <a href="{{ route('admin.pagos.index') }}" class="text-gold hover:text-accent transition">
        <i class="fas fa-arrow-left mr-2"></i>Volver a Pagos
    </a>
</div>

<!-- Grid 2 columnas -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <!-- COLUMNA IZQUIERDA (2/3) -->
    <div class="lg:col-span-2 space-y-8">

        <!-- Info del Pago -->
        <div class="bg-gray-800 rounded-xl p-6 border-2 border-gray-700">
            <h3 class="text-2xl font-bold text-white mb-6 flex items-center gap-2">
                <i class="fas fa-credit-card text-gold"></i>
                Información del Pago
            </h3>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-gray-400 text-sm mb-1">ID del Pago</p>
                    <p class="text-white font-bold text-xl">#{{ $pago->id }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Pedido Relacionado</p>
                    <a href="{{ route('admin.pedidos.show', $pago->pedido_id) }}"
                       class="text-blue-400 hover:text-blue-300 font-bold text-xl">
                        #{{ $pago->pedido_id }}
                    </a>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Método de Pago</p>
                    <span class="px-4 py-2 rounded-full text-sm font-bold inline-block
                        @if($pago->metodo_pago == 'efectivo') bg-green-900 text-green-300
                        @elseif($pago->metodo_pago == 'tarjeta') bg-blue-900 text-blue-300
                        @else bg-purple-900 text-purple-300
                        @endif">
                        @if($pago->metodo_pago == 'efectivo') 💵 Efectivo
                        @elseif($pago->metodo_pago == 'tarjeta') 💳 Tarjeta
                        @else 🏦 Transferencia
                        @endif
                    </span>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Estado del Pago</p>
                    <span class="px-4 py-2 rounded-full text-sm font-bold inline-block
                        @if($pago->estado_pago == 'completado') bg-green-900 text-green-300
                        @elseif($pago->estado_pago == 'pendiente') bg-yellow-900 text-yellow-300
                        @else bg-red-900 text-red-300
                        @endif">
                        @if($pago->estado_pago == 'completado') ✓ Completado
                        @elseif($pago->estado_pago == 'pendiente') ⏳ Pendiente
                        @else ❌ Rechazado
                        @endif
                    </span>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Fecha de Creación</p>
                    <p class="text-white font-semibold">{{ $pago->created_at->format('d/m/Y H:i A') }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Última Actualización</p>
                    <p class="text-white font-semibold">{{ $pago->updated_at->format('d/m/Y H:i A') }}</p>
                </div>
            </div>

            <!-- Monto -->
            <div class="mt-6 pt-6 border-t-2 border-gray-600">
                <div class="flex justify-between items-center">
                    <span class="text-white text-xl font-bold">Monto Total:</span>
                    <span class="text-gold text-4xl font-bold">${{ number_format($pago->monto, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Info del Pedido -->
        <div class="bg-gray-800 rounded-xl p-6 border-2 border-gray-700">
            <h3 class="text-2xl font-bold text-white mb-6 flex items-center gap-2">
                <i class="fas fa-shopping-bag text-gold"></i>
                Detalles del Pedido
            </h3>

            <!-- Estado del Pedido -->
            <div class="mb-6 p-4 bg-gray-700 rounded-lg">
                <div class="flex justify-between items-center">
                    <span class="text-gray-300">Estado del Pedido:</span>
                    <span class="px-4 py-2 rounded-full text-sm font-bold
                        @if($pago->pedido->estado == 'pendiente') bg-yellow-900 text-yellow-300
                        @elseif($pago->pedido->estado == 'en_preparacion') bg-blue-900 text-blue-300
                        @elseif($pago->pedido->estado == 'listo') bg-purple-900 text-purple-300
                        @elseif($pago->pedido->estado == 'entregado') bg-green-900 text-green-300
                        @else bg-red-900 text-red-300
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $pago->pedido->estado)) }}
                    </span>
                </div>
            </div>

            <!-- Productos -->
            <div class="space-y-3">
                @foreach($pago->pedido->detalles as $detalle)
                <div class="flex justify-between items-center p-4 bg-gray-700 rounded-lg">
                    <div class="flex-1">
                        <h4 class="text-white font-bold">{{ $detalle->producto->nombre }}</h4>
                        <p class="text-gray-400 text-sm">
                            Cantidad: {{ $detalle->cantidad }} × ${{ number_format($detalle->precio_unitario, 2) }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-gold font-bold text-lg">${{ number_format($detalle->subtotal, 2) }}</p>
                    </div>
                </div>
                @endforeach
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
                    <p class="text-white font-semibold">{{ $pago->pedido->user->name }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Email</p>
                    <p class="text-white">{{ $pago->pedido->user->email }}</p>
                </div>
                @if($pago->pedido->user->telefono)
                <div>
                    <p class="text-gray-400 text-sm">Teléfono</p>
                    <p class="text-white">{{ $pago->pedido->user->telefono }}</p>
                </div>
                @endif
                @if($pago->pedido->user->direccion)
                <div>
                    <p class="text-gray-400 text-sm">Dirección</p>
                    <p class="text-white">{{ $pago->pedido->user->direccion }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Cambiar Estado del Pago -->
        @if($pago->estado_pago != 'completado' && $pago->estado_pago != 'rechazado')
        <div class="bg-gray-800 rounded-xl p-6 border-2 border-gray-700">
            <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                <i class="fas fa-exchange-alt text-gold"></i>
                Cambiar Estado del Pago
            </h3>
            <form action="{{ route('admin.pagos.actualizar', $pago->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <select name="estado_pago" class="w-full px-4 py-3 rounded-lg bg-gray-700 text-white border-2 border-gray-600 mb-4">
                    <option value="pendiente" {{ $pago->estado_pago == 'pendiente' ? 'selected' : '' }}>⏳ Pendiente</option>
                    <option value="completado" {{ $pago->estado_pago == 'completado' ? 'selected' : '' }}>✓ Completado</option>
                    <option value="rechazado" {{ $pago->estado_pago == 'rechazado' ? 'selected' : '' }}>❌ Rechazado</option>
                </select>
                <button type="submit" class="w-full py-3 rounded-lg font-bold text-white transition" style="background-color: var(--color-gold); color: var(--color-dark);">
                    <i class="fas fa-save mr-2"></i>Actualizar Estado
                </button>
            </form>
        </div>
        @endif

        <!-- Historial de Cambios (futuro) -->
        <div class="bg-gray-800 rounded-xl p-6 border-2 border-gray-700">
            <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                <i class="fas fa-clock text-gold"></i>
                Información Temporal
            </h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between items-center p-3 bg-gray-700 rounded">
                    <span class="text-gray-400">Creado:</span>
                    <span class="text-white font-semibold">{{ $pago->created_at->diffForHumans() }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-700 rounded">
                    <span class="text-gray-400">Actualizado:</span>
                    <span class="text-white font-semibold">{{ $pago->updated_at->diffForHumans() }}</span>
                </div>
            </div>
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

