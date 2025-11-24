@extends('layouts.app-empleado')

@section('title', 'Pedidos Completados - OsoriosFoodApp')

@section('content')

<!-- ===== HEADER ===== -->
<div class="mb-10">
    <h1 class="text-5xl font-bold text-white mb-2">
        <i class="fas fa-check-double" style="color: var(--color-gold);"></i>
        Pedidos Completados
    </h1>
    <p class="text-gray-400 text-lg">Pedidos entregados y cancelados de hoy</p>
</div>

<!-- ===== FILTROS ===== -->
<div class="bg-gray-800 rounded-xl p-6 border-2 border-gray-700 mb-8">
    <div class="flex flex-wrap gap-3">
        <button onclick="filterByStatus('todos')"
                class="filter-btn active px-6 py-2 rounded-full font-semibold transition"
                data-status="todos"
                style="background-color: var(--color-gold); color: var(--color-dark);">
            <i class="fas fa-list mr-2"></i>Todos
        </button>
        <button onclick="filterByStatus('entregado')"
                class="filter-btn px-6 py-2 rounded-full font-semibold bg-gray-700 hover:bg-gray-600 transition text-white"
                data-status="entregado">
            <i class="fas fa-check-circle mr-2"></i>Entregados
        </button>
        <button onclick="filterByStatus('cancelado')"
                class="filter-btn px-6 py-2 rounded-full font-semibold bg-gray-700 hover:bg-gray-600 transition text-white"
                data-status="cancelado">
            <i class="fas fa-times-circle mr-2"></i>Cancelados
        </button>
    </div>
</div>

<!-- ===== ESTADÍSTICAS RÁPIDAS ===== -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-gradient-to-br from-green-900 to-green-800 rounded-xl p-6 border-2 border-green-600">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-300 text-sm font-semibold uppercase mb-2">Entregados Hoy</p>
                <h2 class="text-4xl font-bold text-white" id="countEntregados">{{ $pedidos->where('estado', 'entregado')->count() }}</h2>
            </div>
            <div class="bg-green-500 w-16 h-16 rounded-full flex items-center justify-center">
                <i class="fas fa-truck text-3xl text-green-900"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-red-900 to-red-800 rounded-xl p-6 border-2 border-red-600">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-red-300 text-sm font-semibold uppercase mb-2">Cancelados Hoy</p>
                <h2 class="text-4xl font-bold text-white" id="countCancelados">{{ $pedidos->where('estado', 'cancelado')->count() }}</h2>
            </div>
            <div class="bg-red-500 w-16 h-16 rounded-full flex items-center justify-center">
                <i class="fas fa-ban text-3xl text-red-900"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-900 to-purple-800 rounded-xl p-6 border-2 border-purple-600">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-300 text-sm font-semibold uppercase mb-2">Total Hoy</p>
                <h2 class="text-4xl font-bold text-white">{{ $pedidos->total() }}</h2>
            </div>
            <div class="bg-purple-500 w-16 h-16 rounded-full flex items-center justify-center">
                <i class="fas fa-list-check text-3xl text-purple-900"></i>
            </div>
        </div>
    </div>
</div>

<!-- ===== LISTA DE PEDIDOS ===== -->
<div id="pedidosContainer" class="space-y-4">
    <!-- Cargado por JavaScript -->
    <div class="text-center py-12">
        <div class="inline-block animate-spin">
            <i class="fas fa-spinner text-4xl text-gold"></i>
        </div>
        <p class="text-gray-400 mt-4">Cargando pedidos completados...</p>
    </div>
</div>

@endsection

@push('css')
<style>
    .pedido-card {
        background: linear-gradient(135deg, #2a2a2a 0%, #1a1a1a 100%);
        border: 2px solid #404040;
        transition: all 0.3s ease;
        opacity: 0.9;
    }

    .pedido-card:hover {
        opacity: 1;
        transform: translateY(-2px);
    }

    .pedido-card.entregado {
        border-color: #059669;
    }

    .pedido-card.cancelado {
        border-color: #dc2626;
    }

    .filter-btn.active {
        background-color: var(--color-gold) !important;
        color: var(--color-dark) !important;
    }

    .status-badge {
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: bold;
        display: inline-block;
    }

    .status-entregado {
        background-color: #059669;
        color: white;
    }

    .status-cancelado {
        background-color: #dc2626;
        color: white;
    }
</style>
@endpush

@push('scripts')
<script>
    let todosPedidos = [];
    let filtroActual = 'todos';
    let isFirstLoad = true;

    /**
     * FUNCIÓN: Cargar pedidos completados (optimizado)
     */
    async function loadPedidos() {
        try {
            // Solo mostrar loading en la primera carga
            if (isFirstLoad) {
                document.getElementById('pedidosContainer').innerHTML = `
                    <div class="text-center py-12">
                        <div class="inline-block animate-spin">
                            <i class="fas fa-spinner text-4xl text-gold"></i>
                        </div>
                        <p class="text-gray-400 mt-4">Cargando pedidos completados...</p>
                    </div>
                `;
            }

            const response = await fetch('/empleado/api/pedidos-completados', {
                headers: {
                    'Accept': 'application/json',
                    'Cache-Control': 'no-cache'
                }
            });

            if (!response.ok) throw new Error('Error al cargar pedidos');

            todosPedidos = await response.json();
            renderPedidos();

            if (isFirstLoad) {
                isFirstLoad = false;
            }
        } catch (error) {
            console.error('Error:', error);
            document.getElementById('pedidosContainer').innerHTML = `
                <div class="text-center py-12 bg-red-900 bg-opacity-20 rounded-xl border-2 border-red-700">
                    <i class="fas fa-exclamation-circle text-red-500 text-5xl mb-4 block"></i>
                    <p class="text-red-400 text-lg mb-4">No se pudieron cargar los pedidos</p>
                    <button onclick="location.reload()"
                            class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition">
                        <i class="fas fa-redo mr-2"></i>Reintentar
                    </button>
                </div>
            `;
        }
    }

    /**
     * FUNCIÓN: Renderizar pedidos según filtro
     */
    function renderPedidos() {
        const container = document.getElementById('pedidosContainer');

        let pedidos = todosPedidos;
        if (filtroActual !== 'todos') {
            pedidos = todosPedidos.filter(p => p.estado === filtroActual);
        }

        if (pedidos.length === 0) {
            container.innerHTML = `
                <div class="text-center py-16 bg-gray-800 rounded-xl border-2 border-gray-700">
                    <i class="fas fa-inbox text-gray-500 text-6xl mb-4 block"></i>
                    <p class="text-gray-400 text-lg">No hay pedidos completados con este filtro</p>
                </div>
            `;
            return;
        }

        const statusLabels = {
            'entregado': '🚚 Entregado',
            'cancelado': '❌ Cancelado'
        };

        container.innerHTML = pedidos.map(pedido => `
            <div class="pedido-card ${pedido.estado} rounded-xl p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <!-- COLUMNA 1: INFO DEL PEDIDO -->
                    <div>
                        <div class="flex items-center gap-3 mb-3">
                            <h3 class="text-white font-bold text-xl">Pedido #${pedido.id}</h3>
                            <span class="status-badge status-${pedido.estado}">
                                ${statusLabels[pedido.estado]}
                            </span>
                        </div>
                        <p class="text-gray-400 text-sm mb-2">
                            <i class="fas fa-user mr-2 text-gold"></i>
                            <strong>Cliente:</strong> ${pedido.user.name}
                        </p>
                        <p class="text-gray-400 text-sm mb-2">
                            <i class="fas fa-clock mr-2 text-gold"></i>
                            <strong>Completado:</strong> ${new Date(pedido.updated_at).toLocaleTimeString('es-CO', {hour: '2-digit', minute: '2-digit'})}
                        </p>
                        <p class="text-gray-400 text-sm mb-2">
                            <i class="fas fa-calendar mr-2 text-gold"></i>
                            <strong>Fecha:</strong> ${new Date(pedido.created_at).toLocaleDateString('es-CO')}
                        </p>
                        <p class="text-gold font-bold text-lg mt-3">
                            <i class="fas fa-dollar-sign mr-2"></i>
                            Total: $${parseFloat(pedido.total).toFixed(2)}
                        </p>
                    </div>

                    <!-- COLUMNA 2: PRODUCTOS -->
                    <div>
                        <h4 class="text-white font-semibold mb-3">
                            <i class="fas fa-shopping-bag mr-2 text-gold"></i>
                            Productos (${pedido.detalles.length})
                        </h4>
                        <div class="space-y-2">
                            ${pedido.detalles.slice(0, 3).map(d => `
                                <div class="text-gray-300 text-sm">
                                    • ${d.producto.nombre} <span class="text-gold">(x${d.cantidad})</span>
                                </div>
                            `).join('')}
                            ${pedido.detalles.length > 3 ? `
                                <div class="text-gray-400 text-sm italic">
                                    + ${pedido.detalles.length - 3} más...
                                </div>
                            ` : ''}
                        </div>
                        ${pedido.notas ? `
                            <div class="mt-3 p-3 bg-gray-700 rounded border-l-4 border-yellow-500">
                                <p class="text-yellow-300 text-sm font-semibold mb-1">
                                    <i class="fas fa-sticky-note mr-2"></i>Notas:
                                </p>
                                <p class="text-gray-300 text-sm">${pedido.notas}</p>
                            </div>
                        ` : ''}
                    </div>

                    <!-- COLUMNA 3: INFO PAGO Y ACCIONES -->
                    <div class="flex flex-col justify-between">
                        <div>
                            <h4 class="text-white font-semibold mb-3">
                                <i class="fas fa-credit-card mr-2 text-gold"></i>
                                Información de Pago
                            </h4>
                            <div class="space-y-2 text-sm">
                                <p class="text-gray-300">
                                    <strong>Método:</strong>
                                    ${pedido.pago.metodo_pago === 'efectivo' ? '💵 Efectivo' :
                                      pedido.pago.metodo_pago === 'tarjeta' ? '💳 Tarjeta' :
                                      '🏦 Transferencia'}
                                </p>
                                <p class="text-gray-300">
                                    <strong>Estado:</strong>
                                    <span class="${pedido.pago.estado_pago === 'completado' ? 'text-green-400' : pedido.pago.estado_pago === 'pendiente' ? 'text-yellow-400' : 'text-red-400'}">
                                        ${pedido.pago.estado_pago === 'completado' ? '✓ Completado' :
                                          pedido.pago.estado_pago === 'pendiente' ? '⏳ Pendiente' :
                                          '❌ Rechazado'}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <button onclick="window.location.href='/empleado/pedido/${pedido.id}'"
                                class="mt-4 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-semibold transition">
                            <i class="fas fa-eye mr-2"></i>Ver Detalles Completos
                        </button>
                    </div>

                </div>
            </div>
        `).join('');

        // Actualizar contadores
        document.getElementById('countEntregados').textContent = todosPedidos.filter(p => p.estado === 'entregado').length;
        document.getElementById('countCancelados').textContent = todosPedidos.filter(p => p.estado === 'cancelado').length;
    }

    /**
     * FUNCIÓN: Filtrar por estado
     */
    function filterByStatus(status) {
        filtroActual = status;

        // Actualizar botones activos
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelector(`[data-status="${status}"]`).classList.add('active');

        // Renderizar
        renderPedidos();
    }

    /**
     * EVENTO: Cargar pedidos al abrir la página
     */
    document.addEventListener('DOMContentLoaded', () => {
        loadPedidos();

        // Agregar indicador de última actualización
        const header = document.querySelector('.mb-10');
        if (header) {
            const indicator = document.createElement('div');
            indicator.id = 'lastUpdate';
            indicator.className = 'text-gray-400 text-sm mt-2';
            indicator.innerHTML = '<i class="fas fa-clock mr-2"></i>Actualizado: Ahora';
            header.appendChild(indicator);
        }
    });

    /**
     * Auto-refresh cada 60 segundos (para pedidos completados no es tan crítico)
     */
    let refreshInterval;
    let lastUpdateTime = Date.now();

    function startAutoRefresh() {
        refreshInterval = setInterval(async () => {
            await loadPedidos();
            lastUpdateTime = Date.now();

            // Actualizar indicador
            const indicator = document.getElementById('lastUpdate');
            if (indicator) {
                indicator.innerHTML = '<i class="fas fa-sync-alt mr-2"></i>Actualizado: Ahora';
                setTimeout(() => {
                    const seconds = Math.floor((Date.now() - lastUpdateTime) / 1000);
                    indicator.innerHTML = `<i class="fas fa-clock mr-2"></i>Última actualización: hace ${seconds}s`;
                }, 2000);
            }
        }, 60000); // 60 segundos
    }

    startAutoRefresh();

    // Limpiar interval al salir de la página
    window.addEventListener('beforeunload', () => {
        if (refreshInterval) clearInterval(refreshInterval);
    });

</script>
@endpush

