@extends('layouts.app-cliente')

@section('title', 'Mis Pedidos - OsoriosFoodApp')

@section('content')
<div class="max-w-6xl mx-auto w-full">
    
    <!-- ===== BREADCRUMB ===== -->
    <div class="mb-8">
        <a href="{{ route('cliente.menu') }}" class="text-gold hover:text-accent flex items-center gap-2 transition">
            <i class="fas fa-arrow-left"></i> Volver al menú
        </a>
    </div>

    <!-- ===== TÍTULO ===== -->
    <h1 class="text-5xl font-bold text-white mb-10 flex items-center gap-3">
        <i class="fas fa-history text-gold"></i>
        Mis Pedidos
    </h1>

    <!-- ===== CONTENEDOR DE PEDIDOS ===== -->
    <div id="pedidosContainer" class="space-y-4">
        <!-- Cargado por JavaScript -->
        <div class="text-center py-12">
            <div class="inline-block animate-spin">
                <i class="fas fa-spinner text-4xl text-gold"></i>
            </div>
            <p class="text-gray-400 mt-4">Cargando tus pedidos...</p>
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
        box-shadow: 0 0 15px rgba(212, 175, 55, 0.2);
    }

    .status-badge {
        display: inline-block;
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

    .status-entregado {
        background-color: #059669;
        color: white;
    }

    .status-cancelado {
        background-color: #dc2626;
        color: white;
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
    /**
     * FUNCIÓN: Cargar pedidos del cliente
     */
    async function loadPedidos() {
        try {
            const response = await fetch('/cliente/api/mis-pedidos');
            const pedidos = await response.json();

            const container = document.getElementById('pedidosContainer');

            // Si no hay pedidos
            if (pedidos.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-16 bg-gray-800 rounded-xl border-2 border-gray-700">
                        <i class="fas fa-inbox text-gray-500 text-6xl mb-4 block"></i>
                        <p class="text-gray-400 text-lg mb-6">No tienes pedidos aún</p>
                        <a href="{{ route('cliente.menu') }}" 
                           class="inline-block px-8 py-3 rounded-lg text-white font-bold transition"
                           style="background-color: var(--color-gold); color: var(--color-dark);">
                            <i class="fas fa-shopping-bag mr-2"></i>Realizar un Pedido
                        </a>
                    </div>
                `;
                return;
            }

            // Renderizar cada pedido
            container.innerHTML = pedidos.map(pedido => {
                const statusLabels = {
                    'pendiente': '⏳ Pendiente',
                    'en_preparacion': '👨‍🍳 En Preparación',
                    'listo': '✓ Listo',
                    'entregado': '🚚 Entregado',
                    'cancelado': '❌ Cancelado'
                };

                return `
                    <div class="pedido-card rounded-xl p-6 cursor-pointer hover:scale-105 transition" 
                         onclick="window.location.href='/cliente/pedido/${pedido.id}'">
                        
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-white font-bold text-lg">Pedido #${pedido.id}</h3>
                                <p class="text-gray-400 text-sm">
                                    ${new Date(pedido.created_at).toLocaleDateString('es-CO')} 
                                    - ${new Date(pedido.created_at).toLocaleTimeString('es-CO')}
                                </p>
                            </div>
                            <span class="status-badge status-${pedido.estado}">
                                ${statusLabels[pedido.estado]}
                            </span>
                        </div>

                        <!-- Info del pedido -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4 pb-4 border-b border-gray-700">
                            <div>
                                <p class="text-gray-400 text-sm">Productos</p>
                                <p class="text-white font-bold text-lg">${pedido.detalles.length}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-sm">Método</p>
                                <p class="text-white font-bold text-sm">
                                    ${pedido.pago.metodo_pago === 'efectivo' ? '💵 Efectivo' : 
                                      pedido.pago.metodo_pago === 'tarjeta' ? '💳 Tarjeta' : 
                                      '🏦 Transferencia'}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-sm">Estado Pago</p>
                                <p class="text-white font-bold text-sm">
                                    ${pedido.pago.estado_pago === 'completado' ? '✓ Completado' : '⏳ Pendiente'}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-sm">Total</p>
                                <p class="text-gold font-bold text-lg">$${parseFloat(pedido.total).toFixed(2)}</p>
                            </div>
                        </div>

                        <!-- Botón Ver Detalles -->
                        <button class="text-gold hover:text-accent font-semibold flex items-center gap-2 transition">
                            Ver Detalles <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                `;
            }).join('');

        } catch (error) {
            console.error('Error:', error);
            document.getElementById('pedidosContainer').innerHTML = `
                <div class="bg-red-900 border border-red-700 rounded-xl p-6 text-center">
                    <i class="fas fa-exclamation-circle text-3xl text-red-400 mb-3 block"></i>
                    <p class="text-white">Error al cargar tus pedidos</p>
                </div>
            `;
        }
    }

    /**
     * EVENTO: Cargar pedidos al abrir la página
     */
    document.addEventListener('DOMContentLoaded', loadPedidos);

</script>
@endpush