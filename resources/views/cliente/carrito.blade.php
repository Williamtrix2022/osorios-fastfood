@extends('layouts.app-cliente')

@section('title', 'Carrito de Compras - OsoriosFoodApp')

@section('content')
<div class="max-w-6xl mx-auto w-full">
    
    <!-- ===== BREADCRUMB ===== -->
    <div class="mb-8 flex items-center gap-2 text-gray-400">
        <a href="{{ route('cliente.menu') }}" class="hover:text-gold transition">
            <i class="fas fa-home mr-1"></i>Menú
        </a>
        <i class="fas fa-chevron-right"></i>
        <span class="text-gold">Carrito</span>
    </div>

    <!-- ===== TÍTULO ===== -->
    <h1 class="text-5xl font-bold text-white mb-10 flex items-center gap-3">
        <i class="fas fa-shopping-cart text-gold"></i>
        Mi Carrito
    </h1>

    <!-- ===== CONTENIDO EN 2 COLUMNAS ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- IZQUIERDA: ITEMS DEL CARRITO (2/3) -->
        <div class="lg:col-span-2">
            <div id="cartItemsContainer" class="space-y-4">
                <!-- Cargado por JavaScript -->
                <div class="text-center py-12">
                    <div class="inline-block animate-spin">
                        <i class="fas fa-spinner text-4xl text-gold"></i>
                    </div>
                    <p class="text-gray-400 mt-4">Cargando carrito...</p>
                </div>
            </div>
        </div>

        <!-- DERECHA: RESUMEN Y FORMULARIO (1/3) -->
        <div>
            <div class="bg-gray-800 rounded-xl p-6 border-2 border-gray-700 sticky top-6">
                
                <!-- Título -->
                <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-2">
                    <i class="fas fa-receipt text-gold"></i>
                    Resumen
                </h2>

                <!-- Totales -->
                <div class="space-y-3 mb-6 pb-6 border-b-2 border-gray-700">
                    <div class="flex justify-between text-gray-400">
                        <span>Subtotal:</span>
                        <span id="resumenSubtotal">$0.00</span>
                    </div>
                    <div class="flex justify-between text-gray-400">
                        <span>Impuesto (10%):</span>
                        <span id="resumenTax">$0.00</span>
                    </div>
                    <div class="flex justify-between text-white font-bold text-lg">
                        <span>Total:</span>
                        <span id="resumenTotal" style="color: var(--color-gold);">$0.00</span>
                    </div>
                </div>

                <!-- Formulario -->
                <form id="checkoutForm" class="space-y-4">
                    @csrf

                    <!-- Método de Pago -->
                    <div>
                        <label class="block text-white font-semibold mb-2">
                            <i class="fas fa-credit-card mr-2 text-gold"></i>Método de Pago
                        </label>
                        <select id="metodoPago" 
                                name="metodo_pago" 
                                required
                                class="w-full px-4 py-3 rounded-lg bg-gray-700 text-white border-2 border-gray-600 focus:border-gold focus:outline-none transition">
                            <option value="">-- Selecciona --</option>
                            <option value="efectivo">💵 Efectivo (Contraentrega)</option>
                            <option value="tarjeta">💳 Tarjeta de Crédito</option>
                            <option value="transferencia">🏦 Transferencia</option>
                        </select>
                    </div>

                    <!-- Notas -->
                    <div>
                        <label class="block text-white font-semibold mb-2">
                            <i class="fas fa-sticky-note mr-2 text-gold"></i>Notas (Opcional)
                        </label>
                        <textarea id="notas" 
                                  name="notas" 
                                  rows="3" 
                                  placeholder="Ej: Sin cebolla, extra salsa..."
                                  class="w-full px-4 py-3 rounded-lg bg-gray-700 text-white border-2 border-gray-600 focus:border-gold focus:outline-none resize-none placeholder-gray-500 transition"></textarea>
                    </div>

                    <!-- Botón Confirmar -->
                    <button 
                        type="submit" 
                        class="w-full py-3 rounded-lg text-white font-bold transition hover:opacity-90 active:scale-95 flex items-center justify-center gap-2 text-lg"
                        style="background-color: var(--color-gold); color: var(--color-dark);"
                        id="submitBtn"
                    >
                        <i class="fas fa-check-circle"></i>
                        Confirmar Pedido
                    </button>

                    <!-- Botón Seguir Comprando -->
                    <a href="{{ route('cliente.menu') }}" 
                       class="block text-center py-3 rounded-lg border-2 border-gray-600 text-white font-semibold hover:bg-gray-700 transition">
                        <i class="fas fa-arrow-left mr-2"></i>Seguir Comprando
                    </a>
                </form>

                <!-- Seguridad -->
                <div class="mt-6 pt-6 border-t border-gray-700 text-center text-gray-400 text-sm flex items-center justify-center gap-2">
                    <i class="fas fa-shield-alt text-green-500"></i>
                    Pago seguro
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

<!-- ===== ESTILOS ===== -->
@push('css')
<style>
    .cart-item-card {
        background: linear-gradient(135deg, #2a2a2a 0%, #1a1a1a 100%);
        transition: all 0.3s ease;
        border: 2px solid #404040;
    }

    .cart-item-card:hover {
        border-color: var(--color-gold);
        box-shadow: 0 0 15px rgba(212, 175, 55, 0.2);
    }

    .line-clamp-2 {
      display: -webkit-box;
      -webkit-box-orient: vertical;
      -webkit-line-clamp: 2;
      line-clamp: 2; /* estándar futuro */
      overflow: hidden;
    }

    .animate-spin {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    /* Asegurar que el texto sea visible en los inputs */
    input[type="text"],
    input[type="number"],
    textarea,
    select {
        background-color: #374151 !important;
        color: white !important;
    }

    input[type="text"]::placeholder,
    textarea::placeholder {
        color: #9ca3af !important;
    }

    /* Asegurar contraste en opciones del select */
    select option {
        background-color: #374151;
        color: white;
    }
</style>
@endpush

<!-- ===== JAVASCRIPT ===== -->
@push('scripts')
<script>
    /**
     * FUNCIÓN: Cargar items del carrito en la página
     */
    function loadCartItems() {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        const container = document.getElementById('cartItemsContainer');

        // Si el carrito está vacío
        if (carrito.length === 0) {
            container.innerHTML = `
                <div class="text-center py-16 bg-gray-800 rounded-xl border-2 border-gray-700">
                    <i class="fas fa-shopping-cart text-gray-500 text-6xl mb-4 block"></i>
                    <p class="text-gray-400 text-lg mb-6">Tu carrito está vacío</p>
                    <a href="{{ route('cliente.menu') }}" 
                       class="inline-block px-8 py-3 rounded-lg text-white font-bold transition"
                       style="background-color: var(--color-gold); color: var(--color-dark);">
                        <i class="fas fa-shopping-bag mr-2"></i>Ir al Menú
                    </a>
                </div>
            `;
            return;
        }

        // Crear HTML para cada item del carrito
        container.innerHTML = carrito.map((item, index) => `
            <div class="cart-item-card rounded-xl p-6">
                <div class="flex gap-6">
                    
                    <!-- IMAGEN -->
                    <div class="flex-shrink-0">
                        <img src="${item.imagen ? '/storage/' + item.imagen : 'https://via.placeholder.com/120?text=Producto'}" 
                             alt="${item.nombre}" 
                             class="w-24 h-24 rounded-lg object-cover border border-gray-600 hover:border-gold transition">
                    </div>
                    
                    <!-- INFO -->
                    <div class="flex-1">
                        <h3 class="text-white font-bold text-xl mb-2">${item.nombre}</h3>
                        <p class="text-gray-400 text-sm mb-4">
                            Precio unitario: <span class="text-gold font-bold">$${parseFloat(item.precio).toFixed(2)}</span>
                        </p>
                        
                        <!-- CANTIDAD Y TOTAL -->
                        <div class="flex items-center justify-between mt-4">
                            <!-- Selector de cantidad -->
                            <div class="flex items-center gap-3 bg-gray-700 rounded-lg px-4 py-2">
                                <button onclick="decrementItem(${index})" class="text-white hover:text-gold transition text-lg font-bold">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <span class="w-8 text-center text-white font-bold text-lg">${item.cantidad}</span>
                                <button onclick="incrementItem(${index})" class="text-white hover:text-gold transition text-lg font-bold">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            
                            <!-- Precio total del item -->
                            <div class="text-right">
                                <p class="text-gold font-bold text-2xl">$${(item.precio * item.cantidad).toFixed(2)}</p>
                                <button onclick="removeItem(${index})" class="text-red-500 hover:text-red-700 text-sm mt-2 transition">
                                    <i class="fas fa-trash mr-1"></i>Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');

        // Actualizar totales
        updateTotals();
    }

    /**
     * FUNCIÓN: Aumentar cantidad de un item
     */
    function incrementItem(index) {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        if (carrito[index]) {
            carrito[index].cantidad++;
            localStorage.setItem('carrito', JSON.stringify(carrito));
            loadCartItems();
        }
    }

    /**
     * FUNCIÓN: Disminuir cantidad de un item
     */
    function decrementItem(index) {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        if (carrito[index]) {
            if (carrito[index].cantidad > 1) {
                carrito[index].cantidad--;
            } else {
                carrito.splice(index, 1);
            }
            localStorage.setItem('carrito', JSON.stringify(carrito));
            loadCartItems();
        }
    }

    /**
     * FUNCIÓN: Eliminar un item
     */
    function removeItem(index) {
        Swal.fire({
            title: '¿Eliminar producto?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: 'var(--color-gold)',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(result => {
            if (result.isConfirmed) {
                const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
                carrito.splice(index, 1);
                localStorage.setItem('carrito', JSON.stringify(carrito));
                loadCartItems();
            }
        });
    }

    /**
     * FUNCIÓN: Actualizar totales del resumen
     */
    function updateTotals() {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        const subtotal = carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
        const tax = subtotal * 0.10;
        const total = subtotal + tax;

        document.getElementById('resumenSubtotal').textContent = `$${subtotal.toFixed(2)}`;
        document.getElementById('resumenTax').textContent = `$${tax.toFixed(2)}`;
        document.getElementById('resumenTotal').textContent = `$${total.toFixed(2)}`;
    }

    /**
     * EVENTO: Enviar formulario de checkout
     */
    document.getElementById('checkoutForm')?.addEventListener('submit', async (e) => {
        e.preventDefault();

        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        
        if (carrito.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Carrito vacío',
                text: 'Agrega productos antes de continuar',
                confirmButtonColor: 'var(--color-gold)'
            });
            return;
        }

        const metodoPago = document.getElementById('metodoPago').value;
        if (!metodoPago) {
            Swal.fire({
                icon: 'warning',
                title: 'Campo requerido',
                text: 'Selecciona un método de pago',
                confirmButtonColor: 'var(--color-gold)'
            });
            return;
        }

        const notas = document.getElementById('notas').value;

        // Preparar items para enviar
        const items = carrito.map(item => ({
            producto_id: item.id,
            cantidad: item.cantidad,
            precio_unitario: item.precio
        }));

        // Mostrar cargando
        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner animate-spin mr-2"></i>Procesando...';

        try {
            const response = await fetch('/cliente/crear-pedido', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    items: items,
                    metodo_pago: metodoPago,
                    notas: notas
                })
            });

            const data = await response.json();

            if (data.success) {
                // Limpiar carrito
                localStorage.removeItem('carrito');

                // Mostrar éxito
                Swal.fire({
                    icon: 'success',
                    title: '¡Pedido confirmado!',
                    text: 'Tu pedido ha sido creado exitosamente',
                    confirmButtonColor: 'var(--color-gold)'
                }).then(() => {
                    // Redirigir a seguimiento
                    window.location.href = `/cliente/pedido/${data.pedido_id}`;
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Hubo un error al procesar tu pedido',
                    confirmButtonColor: 'var(--color-gold)'
                });
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un error al procesar tu pedido. Intenta de nuevo.',
                confirmButtonColor: 'var(--color-gold)'
            });
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });

    /**
     * EVENTO: Cargar carrito al abrir la página
     */
    document.addEventListener('DOMContentLoaded', loadCartItems);

</script>
@endpush