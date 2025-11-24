@extends('layouts.app-admin')

@section('title', 'Gestión de Pagos - OsoriosFoodApp')
@section('page-title', 'Gestión de Pagos')
@section('page-subtitle', 'Administra todos los pagos del sistema')

@section('content')

<!-- ===== FILTROS ===== -->
<div class="bg-gray-800 rounded-xl p-6 border-2 border-gray-700 mb-8">
    <h3 class="text-xl font-bold text-white mb-4">
        <i class="fas fa-filter text-gold mr-2"></i>Filtros
    </h3>
    <form method="GET" action="{{ route('admin.pagos.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Estado de Pago -->
        <div>
            <label class="block text-white font-semibold mb-2">Estado de Pago</label>
            <select name="estado_pago" class="w-full px-4 py-2 rounded-lg bg-gray-700 text-white border-2 border-gray-600">
                <option value="">Todos los estados</option>
                <option value="pendiente" {{ request('estado_pago') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="completado" {{ request('estado_pago') == 'completado' ? 'selected' : '' }}>Completado</option>
                <option value="rechazado" {{ request('estado_pago') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
            </select>
        </div>

        <!-- Método de Pago -->
        <div>
            <label class="block text-white font-semibold mb-2">Método de Pago</label>
            <select name="metodo_pago" class="w-full px-4 py-2 rounded-lg bg-gray-700 text-white border-2 border-gray-600">
                <option value="">Todos los métodos</option>
                <option value="efectivo" {{ request('metodo_pago') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                <option value="tarjeta" {{ request('metodo_pago') == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                <option value="transferencia" {{ request('metodo_pago') == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
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
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-gray-800 rounded-lg p-4 border-2 border-gray-700 text-center">
        <p class="text-gray-400 text-sm">Total Pagos</p>
        <p class="text-3xl font-bold text-white">{{ $totalPagos }}</p>
    </div>
    <div class="bg-green-900 bg-opacity-30 rounded-lg p-4 border-2 border-green-600 text-center">
        <p class="text-green-300 text-sm">Completados</p>
        <p class="text-3xl font-bold text-white">{{ $pagosCompletados }}</p>
    </div>
    <div class="bg-yellow-900 bg-opacity-30 rounded-lg p-4 border-2 border-yellow-600 text-center">
        <p class="text-yellow-300 text-sm">Pendientes</p>
        <p class="text-3xl font-bold text-white">{{ $pagosPendientes }}</p>
    </div>
    <div class="bg-purple-900 bg-opacity-30 rounded-lg p-4 border-2 border-purple-600 text-center">
        <p class="text-purple-300 text-sm">Total Recaudado</p>
        <p class="text-2xl font-bold text-gold">${{ number_format($totalRecaudado, 2) }}</p>
    </div>
</div>

<!-- ===== TABLA DE PAGOS ===== -->
<div class="bg-gray-800 rounded-xl border-2 border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-900">
                <tr>
                    <th class="text-left text-gold font-bold p-4">ID</th>
                    <th class="text-left text-gold font-bold p-4">Pedido</th>
                    <th class="text-left text-gold font-bold p-4">Cliente</th>
                    <th class="text-left text-gold font-bold p-4">Fecha</th>
                    <th class="text-left text-gold font-bold p-4">Monto</th>
                    <th class="text-left text-gold font-bold p-4">Método</th>
                    <th class="text-left text-gold font-bold p-4">Estado</th>
                    <th class="text-left text-gold font-bold p-4">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pagos as $pago)
                <tr class="border-b border-gray-700 hover:bg-gray-700 transition">
                    <!-- ID -->
                    <td class="p-4">
                        <span class="text-white font-bold">#{{ $pago->id }}</span>
                    </td>

                    <!-- Pedido -->
                    <td class="p-4">
                        <a href="{{ route('admin.pedidos.show', $pago->pedido_id) }}"
                           class="text-blue-400 hover:text-blue-300 font-semibold">
                            Pedido #{{ $pago->pedido_id }}
                        </a>
                    </td>

                    <!-- Cliente -->
                    <td class="p-4">
                        <p class="text-white font-semibold">{{ $pago->pedido->user->name }}</p>
                        <p class="text-gray-400 text-sm">{{ $pago->pedido->user->email }}</p>
                    </td>

                    <!-- Fecha -->
                    <td class="p-4">
                        <p class="text-white">{{ $pago->created_at->format('d/m/Y') }}</p>
                        <p class="text-gray-400 text-sm">{{ $pago->created_at->format('H:i A') }}</p>
                    </td>

                    <!-- Monto -->
                    <td class="p-4">
                        <span class="text-gold font-bold text-xl">${{ number_format($pago->monto, 2) }}</span>
                    </td>

                    <!-- Método de Pago -->
                    <td class="p-4">
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            @if($pago->metodo_pago == 'efectivo') bg-green-900 text-green-300
                            @elseif($pago->metodo_pago == 'tarjeta') bg-blue-900 text-blue-300
                            @else bg-purple-900 text-purple-300
                            @endif">
                            @if($pago->metodo_pago == 'efectivo') 💵 Efectivo
                            @elseif($pago->metodo_pago == 'tarjeta') 💳 Tarjeta
                            @else 🏦 Transferencia
                            @endif
                        </span>
                    </td>

                    <!-- Estado -->
                    <td class="p-4">
                        <span class="px-3 py-1 rounded-full text-sm font-bold
                            @if($pago->estado_pago == 'completado') bg-green-900 text-green-300
                            @elseif($pago->estado_pago == 'pendiente') bg-yellow-900 text-yellow-300
                            @else bg-red-900 text-red-300
                            @endif">
                            @if($pago->estado_pago == 'completado') ✓ Completado
                            @elseif($pago->estado_pago == 'pendiente') ⏳ Pendiente
                            @else ❌ Rechazado
                            @endif
                        </span>
                    </td>

                    <!-- Acciones -->
                    <td class="p-4">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.pagos.show', $pago->id) }}"
                               class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded transition">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($pago->estado_pago == 'pendiente')
                            <button onclick="cambiarEstadoPago({{ $pago->id }}, 'completado')"
                                    class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm rounded transition"
                                    title="Marcar como Completado">
                                <i class="fas fa-check"></i>
                            </button>
                            <button onclick="cambiarEstadoPago({{ $pago->id }}, 'rechazado')"
                                    class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm rounded transition"
                                    title="Marcar como Rechazado">
                                <i class="fas fa-times"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-12">
                        <i class="fas fa-inbox text-5xl text-gray-500 mb-3 block"></i>
                        <p class="text-gray-400 text-lg">No hay pagos registrados</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    @if($pagos->hasPages())
    <div class="p-4 bg-gray-900">
        {{ $pagos->links() }}
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

@push('scripts')
<script>
async function cambiarEstadoPago(pagoId, nuevoEstado) {
    if (!confirm('¿Está seguro de cambiar el estado de este pago?')) {
        return;
    }

    try {
        const response = await fetch(`/admin/pago/${pagoId}/estado`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                estado_pago: nuevoEstado
            })
        });

        const data = await response.json();

        if (data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al actualizar el estado del pago');
    }
}
</script>
@endpush

