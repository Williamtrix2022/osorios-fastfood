@extends('layouts.app-cliente')

@section('title', 'Seguimiento de Pedido - OsoriosFoodApp')

@section('content')
<div class="max-w-4xl mx-auto w-full">
    
    <!-- ===== BREADCRUMB ===== -->
    <div class="mb-8">
        <a href="{{ route('cliente.pedidos') }}" class="text-gold hover:text-accent flex items-center gap-2 transition">
            <i class="fas fa-arrow-left"></i> Volver a mis pedidos
        </a>
    </div>

    <!-- ===== TÍTULO ===== -->
    <h1 class="text-5xl font-bold text-white mb-10 flex items-center gap-3">
        <i class="fas fa-tracking text-gold"></i>
        Seguimiento de Pedido
    </h1>

    <!-- ===== CONTENEDOR PRINCIPAL ===== -->
    <div id="pedidoContainer" class="space-y-8">
        <!-- Cargado por JavaScript -->
        <div class="text-center py-12">
            <div class="inline-block animate-spin">
                <i class="fas fa-spinner text-4xl text-gold"></i>
            </div>
            <p class="text-gray-400 mt-4">Cargando detalles del pedido...</p>
        </div>
    </div>

</div>

@endsection

@push('css')
<style>
    .estado-item {
        text-align: center;
        position: relative;
        flex: 1;
    }

    .estado-circle {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 28px;
        transition: all 0.3s ease;
    }

    .estado-circle.completado {
        background-color: var(--color-gold);
        color: var(--color-dark);
        box-shadow: 0 0 20px rgba(212, 175, 55, 0.5);
    }

    .estado-circle.pendiente {
        background-color: #4b5563;
        color: #9ca3af;
    }

    .estado-circle.activo {
        background-color: var(--color-gold);
        color: var(--color-dark);
        box-shadow: 0 0 30px rgba(212, 175, 55, 0.8);
        transform: scale(1.15);
    }

    .linea-progreso {
        height: 4px;
        flex: 1;
        margin: 35px 5px;
        border-radius: 2px;
    }

    .linea-progreso.completada {
        background-color: var(--color-gold);
    }

    .linea-progreso.pendiente {
        background-color: #4b5563;
    }

    .animate-spin {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
@endpush

@push('scripts')
<script>
    // Obtener ID del pedido desde la URL
    const pedidoId = window.location.pathname.split('/').pop();

    /**
     * FUNCIÓN: Cargar detalles del pedido
     */
    async function loadPedido() {
        try {
            const response = await fetch(`/cliente/api/pedido/${pedidoId}`);
            const pedido = await response.json();

            const container = document.getElementById('pedidoContainer');

            // ===== SECCIÓN 1: ESTADOS DEL PEDIDO =====
            const estados = ['pendiente', 'en_preparacion', 'listo', 'entregado'];
            const estadoActual = estados.indexOf(pedido.estado);

            const labelMap = {
                'pendiente': 'Pendiente',
                'en_preparacion': 'En Preparación',
                'listo': 'Listo',
                'entregado': 'Entregado'
            };

            const iconMap = {
                'pendiente': 'fa-hourglass-start',
                'en_preparacion': 'fa-chef',
                'listo': 'fa-check-circle',
                'entregado': 'fa-truck'
            };

            let statesHTML = `
                <div class="bg-gray-800 rounded-xl p-8 border-2 border-gray-700">
                    <h2 class="text-2xl font-bold text-white mb-8">Estado del Pedido</h2>
                    <div class="flex justify-between items-center">
            `;

            estados.forEach((estado, index) => {
                const isCompleted = index <= estadoActual;
                const isActive = index === estadoActual;

                statesHTML += `
                    <div class="estado-item">
                        <div class="estado-circle ${isCompleted ? 'completado' : 'pendiente'} ${isActive ? 'activo' : ''}">
                            <i class="fas ${iconMap[estado]}"></i>
                        </div>
                        <p class="text-white font-semibold text-sm">${labelMap[estado]}</p>
                    </div>
                    ${index < estados.length - 1 ? `
                        <div class="linea-progreso ${index < estadoActual ? 'completada' : 'pendiente'}"></div>
                    ` : ''}
                `;
            });

            statesHTML += `
                    </div>
                </div>
            `;

            // ===== SECCIÓN 2: INFO DEL PEDIDO Y PAGO =====
            const infoHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Información del Pedido -->
                    <div class="bg-gray-800 rounded-xl p-6 border-2 border-gray-700">
                        <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                            <i class="fas fa-receipt text-gold"></i>
                            Información del Pedido
                        </h3>
                        <div class="space-y-3 text-gray-300">
                            <div class="flex justify-between pb-3 border-b border-gray-600">
                                <span>Número:</span>
                                <span class="text-gold font-bold">#${pedido.id}</span>
                            </div>
                            <div class="flex justify-between pb-3 border-b border-gray-600">
                                <span>Fecha:</span>
                                <span>${new Date(pedido.created_at).toLocaleDateString('es-CO')}</span>
                            </div>
                            <div class="flex justify-between pb-3 border-b border-gray-600">
                                <span>Hora:</span>
                                <span>${new Date(pedido.created_at).toLocaleTimeString('es-CO')}</span>
                            </div>
                            <div class="flex justify-between pt-2">
                                <span>Total:</span>
                                <span class="text-gold font-bold text-lg">$${parseFloat(pedido.total).toFixed(2)}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Información del Pago -->
                    <div class="bg-gray-800 rounded-xl p-6 border-2 border-gray-700">
                        <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                            <i class="fas fa-credit-card text-gold"></i>
                            Método de Pago
                        </h3>
                        <div class="space-y-3 text-gray-300">
                            <div class="flex justify-between pb-3 border-b border-gray-600">
                                <span>Método:</span>
                                <span class="font-semibold">
                                    ${pedido.pago.metodo_pago === 'efectivo' ? '💵 Efectivo' : 
                                      pedido.pago.metodo_pago === 'tarjeta' ? '💳 Tarjeta' : 
                                      '🏦 Transferencia'}
                                </span>
                            </div>
                            <div class="flex justify-between pb-3 border-b border-gray-600">
                                <span>Estado del Pago:</span>
                                <span class="${pedido.pago.estado_pago === 'completado' ? 'text-green-400' : 'text-yellow-400'} font-bold">
                                    ${pedido.pago.estado_pago === 'completado' ? '✓ Completado' : '⏳ Pendiente'}
                                </span>
                            </div>
                            <div class="flex justify-between pt-2">
                                <span>Monto:</span>
                                <span class="font-semibold">$${parseFloat(pedido.pago.monto).toFixed(2)}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // ===== SECCIÓN 3: PRODUCTOS =====
            let itemsHTML = `
                <div class="bg-gray-800 rounded-xl p-6 border-2 border-gray-700">
                    <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <i class="fas fa-shopping-bag text-gold"></i>
                        Productos
                    </h3>
                    <div class="space-y-4">
            `;

            pedido.detalles.forEach(detalle => {
                itemsHTML += `
                    <div class="flex justify-between items-center pb-4 border-b border-gray-700 last:border-b-0">
                        <div class="flex-1">
                            <p class="text-white font-semibold">${detalle.producto.nombre}</p>
                            <p class="text-gray-400 text-sm">Cantidad: ${detalle.cantidad}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-gold font-bold">$${parseFloat(detalle.subtotal).toFixed(2)}</p>
                            <p class="text-gray-400 text-sm">@$${parseFloat(detalle.precio_unitario).toFixed(2)}</p>
                        </div>
                    </div>
                `;
            });

            itemsHTML += `
                    </div>
                </div>
            `;

            // ===== SECCIÓN 4: BOTONES =====
            const actionsHTML = `
                <div class="flex gap-4">
                    <a href="{{ route('cliente.menu') }}" class="flex-1 py-3 rounded-lg text-white font-bold transition text-center"
                       style="background-color: var(--color-gold); color: var(--color-dark);">
                        <i class="fas fa-home mr-2"></i>Ir al Menú
                    </a>
                    <a href="{{ route('cliente.pedidos') }}" class="flex-1 py-3 rounded-lg border-2 border-gray-600 text-white font-bold hover:bg-gray-700 transition text-center">
                        <i class="fas fa-list mr-2"></i>Ver todos mis Pedidos
                    </a>
                </div>
            `;

            // ===== MONTAR TODO =====
            container.innerHTML = statesHTML + infoHTML + itemsHTML + actionsHTML;

            // Auto-refrescar cada 10 segundos
            setTimeout(() => loadPedido(), 10000);

        } catch (error) {
            console.error('Error:', error);
            document.getElementById('pedidoContainer').innerHTML = `
                <div class="bg-red-900 border border-red-700 rounded-xl p-6 text-center">
                    <i class="fas fa-exclamation-circle text-3xl text-red-400 mb-3 block"></i>
                    <p class="text-white">Error al cargar el pedido</p>
                </div>
            `;
        }
    }

    /**
     * EVENTO: Cargar pedido al abrir la página
     */
    document.addEventListener('DOMContentLoaded', loadPedido);

</script>
@endpush