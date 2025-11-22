@extends('layouts.app-empleado')

@section('title', 'Gestión de Pedidos - OsoriosFoodApp')

@section('content')

<!-- ===== HEADER ===== -->
<div class="mb-10">
    <h1 class="text-5xl font-bold text-white mb-2">
        <i class="fas fa-clipboard-list" style="color: var(--color-gold);"></i>
        Gestión de Pedidos
    </h1>
    <p class="text-gray-400 text-lg">Administra el estado de todos los pedidos</p>
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
        <button onclick="filterByStatus('pendiente')" 
                class="filter-btn px-6 py-2 rounded-full font-semibold bg-gray-700 hover:bg-gray-600 transition text-white"
                data-status="pendiente">
            <i class="fas fa-hourglass-start mr-2"></i>Pendientes
        </button>
        <button onclick="filterByStatus('en_preparacion')" 
                class="filter-btn px-6 py-2 rounded-full font-semibold bg-gray-700 hover:bg-gray-600 transition text-white"
                data-status="en_preparacion">
            <i class="fas fa-chef mr-2"></i>En Preparación
        </button>
        <button onclick="filterByStatus('listo')" 
                class="filter-btn px-6 py-2 rounded-full font-semibold bg-gray-700 hover:bg-gray-600 transition text-white"
                data-status="listo">
            <i class="fas fa-check-circle mr-2"></i>Listos
        </button>
        <button onclick="filterByStatus('entregado')" 
                class="filter-btn px-6 py-2 rounded-full font-semibold bg-gray-700 hover:bg-gray-600 transition text-white"
                data-status="entregado">
            <i class="fas fa-truck mr-2"></i>Entregados
        </button>
    </div>
</div>

<!-- ===== LISTA DE PEDIDOS ===== -->
<div id="pedidosContainer" class="space-y-4">
    <!-- Cargado por JavaScript -->
    <div class="text-center py-12">
        <div class="inline-block animate-spin">
            <i class="fas fa-spinner text-4xl text-gold"></i>
        </div>
        <p class="text-gray-400 mt-4">Cargando pedidos...</p>
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
        box-shadow: 0 0 20px rgba(212, 175, 55, 0.2);
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

    .status-entregado {
        background-color: #059669;
        color: white;
    }

    .status-cancelado {
        background-color: #dc2626;
        color: white;
    }

    .action-btn {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-preparar {
        background-color: #1e40af;
        color: white;
    }

    .btn-preparar:hover {
        background-color: #1e3a8a;
    }

    .btn-listo {
        background-color: #7c3aed;
        color: white;
    }

    .btn-listo:hover {
        background-color: #6d28d9;
    }

    .btn-entregar {
        background-color: #059669;
        color: white;
    }

    .btn-entregar:hover {
        background-color: #047857;
    }

    .btn-cancelar {
        background-color: #dc2626;
        color: white;
    }

    .btn-cancelar:hover {
        background-color: #b91c1c;
    }
</style>
@endpush

@push('scripts')
<script>
    let todosPedidos = [];
    let filtroActual = 'todos';

    /**
     * FUNCIÓN: Cargar todos los pedidos
     */
    async function loadPedidos() {
        try {
            const response = await fetch('/empleado/api/pedidos');
            todosPedidos = await response.json();
            renderPedidos();
        } catch (error) {
            console.error('Error:', error);
            Swal.fire('Error', 'No se pudieron cargar los pedidos', 'error');
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
                    <p class="text-gray-400 text-lg">No hay pedidos con este estado</p>
                </div>
            `;
            return;
        }

        const statusLabels = {
            'pendiente': '⏳ Pendiente',
            'en_preparacion': '👨‍🍳 En Preparación',
            'listo': '✓ Listo',
            'entregado': '🚚 Entregado',
            'cancelado': '❌ Cancelado'
        };

        container.innerHTML = pedidos.map(pedido => `
            <div class="pedido-card rounded-xl p-6">
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
                            <strong>Hora:</strong> ${new Date(pedido.created_at).toLocaleTimeString('es-CO')}
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

                    <!-- COLUMNA 3: ACCIONES -->
                    <div class="flex flex-col justify-between">
                        <div>
                            <h4 class="text-white font-semibold mb-3">
                                <i class="fas fa-tasks mr-2 text-gold"></i>
                                Acciones
                            </h4>
                            <div class="space-y-2">
                                ${pedido.estado === 'pendiente' ? `
                                    <button onclick="cambiarEstado(${pedido.id}, 'en_preparacion')" 
                                            class="action-btn btn-preparar w-full">
                                        <i class="fas fa-chef mr-2"></i>Iniciar Preparación
                                    </button>
                                ` : ''}
                                
                                ${pedido.estado === 'en_preparacion' ? `
                                    <button onclick="cambiarEstado(${pedido.id}, 'listo')" 
                                            class="action-btn btn-listo w-full">
                                        <i class="fas fa-check-circle mr-2"></i>Marcar como Listo
                                    </button>
                                ` : ''}
                                
                                ${pedido.estado === 'listo' ? `
                                    <button onclick="cambiarEstado(${pedido.id}, 'entregado')" 
                                            class="action-btn btn-entregar w-full">
                                        <i class="fas fa-truck mr-2"></i>Marcar como Entregado
                                    </button>
                                ` : ''}

                                ${pedido.estado !== 'entregado' && pedido.estado !== 'cancelado' ? `
                                    <button onclick="cambiarEstado(${pedido.id}, 'cancelado')" 
                                            class="action-btn btn-cancelar w-full">
                                        <i class="fas fa-times-circle mr-2"></i>Cancelar Pedido
                                    </button>
                                ` : ''}
                            </div>
                        </div>

                        <button onclick="window.location.href='/empleado/pedido/${pedido.id}'" 
                                class="mt-4 text-gold hover:text-accent font-semibold transition">
                            <i class="fas fa-eye mr-2"></i>Ver Detalles Completos
                        </button>
                    </div>

                </div>
            </div>
        `).join('');
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
     * FUNCIÓN: Cambiar estado de un pedido
     */
    async function cambiarEstado(pedidoId, nuevoEstado) {
        const estadosLabels = {
            'en_preparacion': 'En Preparación',
            'listo': 'Listo',
            'entregado': 'Entregado',
            'cancelado': 'Cancelado'
        };

        const result = await Swal.fire({
            title: '¿Confirmar cambio?',
            text: `El pedido #${pedidoId} será marcado como "${estadosLabels[nuevoEstado]}"`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: 'var(--color-gold)',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Sí, cambiar',
            cancelButtonText: 'Cancelar'
        });

        if (!result.isConfirmed) return;

        try {
            const response = await fetch(`/empleado/pedido/${pedidoId}/estado`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ estado: nuevoEstado })
            });

            const data = await response.json();

            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Estado actualizado!',
                    text: `El pedido #${pedidoId} ahora está ${estadosLabels[nuevoEstado]}`,
                    timer: 2000,
                    showConfirmButton: false
                });

                // Recargar pedidos
                await loadPedidos();
            } else {
                Swal.fire('Error', 'No se pudo actualizar el estado', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire('Error', 'Hubo un problema al actualizar el estado', 'error');
        }
    }

    /**
     * EVENTO: Cargar pedidos al abrir la página
     */
    document.addEventListener('DOMContentLoaded', loadPedidos);

    /**
     * Auto-refresh cada 20 segundos
     */
    setInterval(loadPedidos, 20000);

</script>
@endpush