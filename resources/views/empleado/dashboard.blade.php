@extends('layouts.app-empleado')

@section('title', 'Dashboard Empleado - OsoriosFoodApp')

@section('content')

<!-- ===== TÍTULO ===== -->
<div class="mb-10">
    <h1 class="text-5xl font-bold text-white mb-2">
        <i class="fas fa-chart-line" style="color: var(--color-gold);"></i>
        Dashboard
    </h1>
    <p class="text-gray-400 text-lg">Resumen de pedidos en tiempo real</p>
</div>

<!-- ===== TARJETAS DE RESUMEN ===== -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    
    <!-- Pendientes -->
    <div class="bg-gradient-to-br from-yellow-900 to-yellow-800 rounded-xl p-6 border-2 border-yellow-600">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-yellow-300 text-sm font-semibold">PENDIENTES</p>
                <h2 class="text-4xl font-bold text-white" id="totalPendientes">0</h2>
            </div>
            <div class="bg-yellow-500 w-16 h-16 rounded-full flex items-center justify-center">
                <i class="fas fa-hourglass-start text-3xl text-yellow-900"></i>
            </div>
        </div>
        <p class="text-yellow-200 text-sm">Pedidos sin atender</p>
    </div>

    <!-- En Preparación -->
    <div class="bg-gradient-to-br from-blue-900 to-blue-800 rounded-xl p-6 border-2 border-blue-600">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-blue-300 text-sm font-semibold">EN PREPARACIÓN</p>
                <h2 class="text-4xl font-bold text-white" id="totalEnPreparacion">0</h2>
            </div>
            <div class="bg-blue-500 w-16 h-16 rounded-full flex items-center justify-center">
                <i class="fas fa-chef text-3xl text-blue-900"></i>
            </div>
        </div>
        <p class="text-blue-200 text-sm">Pedidos en proceso</p>
    </div>

    <!-- Listos -->
    <div class="bg-gradient-to-br from-purple-900 to-purple-800 rounded-xl p-6 border-2 border-purple-600">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-purple-300 text-sm font-semibold">LISTOS</p>
                <h2 class="text-4xl font-bold text-white" id="totalListos">0</h2>
            </div>
            <div class="bg-purple-500 w-16 h-16 rounded-full flex items-center justify-center">
                <i class="fas fa-check-circle text-3xl text-purple-900"></i>
            </div>
        </div>
        <p class="text-purple-200 text-sm">Para entregar</p>
    </div>

</div>

<!-- ===== ÚLTIMOS PEDIDOS ===== -->
<div class="bg-gray-800 rounded-xl p-6 border-2 border-gray-700">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-white">
            <i class="fas fa-receipt" style="color: var(--color-gold);"></i>
            Últimos Pedidos
        </h2>
        <a href="{{ route('empleado.pedidos') }}" class="text-gold hover:text-accent transition font-semibold">
            Ver todos <i class="fas fa-arrow-right ml-2"></i>
        </a>
    </div>

    <!-- Contenedor de pedidos -->
    <div id="ultimosPedidosContainer">
        <div class="text-center py-12">
            <div class="inline-block animate-spin">
                <i class="fas fa-spinner text-4xl text-gold"></i>
            </div>
            <p class="text-gray-400 mt-4">Cargando pedidos...</p>
        </div>
    </div>
</div>

@endsection

@push('css')
<style>
    .pedido-card {
        background: linear-gradient(135deg, #2a2a2a 0%, #1a1a1a 100%);
        border: 2px solid #404040;
        transition: all 0.3s ease;
    }

    .pedido-card:hover {
        border-color: var(--color-gold);
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(212, 175, 55, 0.2);
    }

    .status-badge {
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: bold;
    }

    .status-pendiente {
        background-color: #b8860b;
        color: white;
    }

    .status-en_preparacion {
        background-color: #1e40af;
        color: white;
    }

    .status-listo {
        background-color: #7c3aed;
        color: white;
    }
</style>
@endpush

@push('scripts')
<script>
    /**
     * FUNCIÓN: Cargar estadísticas del dashboard
     */
    async function loadDashboard() {
        try {
            // Cargar estadísticas
            const statsPendientes = await fetch('/empleado/api/pendientes').then(r => r.json());
            const statsEnPrep = await fetch('/empleado/api/en-preparacion').then(r => r.json());
            
            document.getElementById('totalPendientes').textContent = statsPendientes.length;
            document.getElementById('totalEnPreparacion').textContent = statsEnPrep.length;
            
            // Calcular listos (desde todos los pedidos)
            const response = await fetch('/empleado/api/pedidos');
            const pedidos = await response.json();
            
            const listos = pedidos.filter(p => p.estado === 'listo');
            document.getElementById('totalListos').textContent = listos.length;

            // Mostrar últimos 5 pedidos
            renderUltimosPedidos(pedidos.slice(0, 5));

        } catch (error) {
            console.error('Error:', error);
        }
    }

    /**
     * FUNCIÓN: Renderizar últimos pedidos
     */
    function renderUltimosPedidos(pedidos) {
        const container = document.getElementById('ultimosPedidosContainer');

        if (pedidos.length === 0) {
            container.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-inbox text-gray-500 text-4xl mb-3 block"></i>
                    <p class="text-gray-400">No hay pedidos recientes</p>
                </div>
            `;
            return;
        }

        const statusLabels = {
            'pendiente': '⏳ Pendiente',
            'en_preparacion': '👨‍🍳 En Preparación',
            'listo': '✓ Listo',
            'entregado': '🚚 Entregado'
        };

        container.innerHTML = pedidos.map(pedido => `
            <div class="pedido-card rounded-xl p-4 mb-3 cursor-pointer" onclick="window.location.href='/empleado/pedido/${pedido.id}'">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="text-white font-bold text-lg">Pedido #${pedido.id}</h3>
                            <span class="status-badge status-${pedido.estado}">
                                ${statusLabels[pedido.estado]}
                            </span>
                        </div>
                        <p class="text-gray-400 text-sm mb-2">
                            <i class="fas fa-user mr-2"></i>${pedido.user.name}
                        </p>
                        <p class="text-gray-400 text-sm">
                            <i class="fas fa-clock mr-2"></i>
                            ${new Date(pedido.created_at).toLocaleString('es-CO')}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-gold font-bold text-xl">$${parseFloat(pedido.total).toFixed(2)}</p>
                        <p class="text-gray-400 text-sm">${pedido.detalles.length} productos</p>
                    </div>
                </div>
            </div>
        `).join('');
    }

    /**
     * EVENTO: Cargar dashboard al abrir la página
     */
    document.addEventListener('DOMContentLoaded', loadDashboard);

    /**
     * Auto-refresh cada 15 segundos
     */
    setInterval(loadDashboard, 15000);

</script>
@endpush