<!-- ===== HEADER DEL CARRITO ===== -->
<div class="p-6 border-b border-gray-700 bg-gradient-to-r from-gray-800 to-gray-700">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-white flex items-center gap-2">
            <i class="fas fa-shopping-cart text-gold"></i>
            Carrito
        </h2>
        <span id="itemCount" class="text-sm font-bold px-3 py-1 rounded-full text-black" 
              style="background-color: var(--color-gold);">
            0 items
        </span>
    </div>
</div>

<!-- ===== ITEMS DEL CARRITO ===== -->
<div class="flex-1 overflow-y-auto p-6" id="cartItems">
    <div id="emptyCart" class="flex flex-col items-center justify-center h-full text-center">
        <i class="fas fa-shopping-cart text-gray-500 text-5xl mb-4"></i>
        <p class="text-gray-400 text-lg">Tu carrito está vacío</p>
        <p class="text-gray-500 text-sm mt-2">Agrega productos para comenzar</p>
    </div>
</div>

<!-- ===== RESUMEN Y BOTONES ===== -->
<div class="p-6 border-t border-gray-700 space-y-4 bg-gradient-to-t from-gray-900 to-gray-800">
    
    <!-- Detalles del total -->
    <div class="space-y-3 pb-4 border-b border-gray-700">
        <div class="flex justify-between text-gray-400">
            <span>Subtotal:</span>
            <span id="subtotal">$0.00</span>
        </div>
        <div class="flex justify-between text-gray-400">
            <span>Impuesto (10%):</span>
            <span id="tax">$0.00</span>
        </div>
        <div class="flex justify-between text-white font-bold text-lg pt-2">
            <span>Total:</span>
            <span id="total" style="color: var(--color-gold);">$0.00</span>
        </div>
    </div>

    <!-- Botón de pago -->
    <button 
        onclick="proceedToCheckout()" 
        class="w-full py-3 rounded-lg text-white font-bold transition hover:opacity-90 active:scale-95"
        style="background-color: var(--color-gold); color: var(--color-dark);"
        id="checkoutBtn"
    >
        <i class="fas fa-credit-card mr-2"></i>Pagar Ahora
    </button>

    <!-- Botón vaciar carrito -->
    <button 
        onclick="clearCart()" 
        class="w-full py-2 rounded-lg border-2 border-gray-600 text-white font-semibold hover:bg-gray-700 transition"
    >
        <i class="fas fa-trash mr-2"></i>Vaciar Carrito
    </button>
</div>

<!-- ===== SCRIPTS PARA EL CARRITO ===== -->
@push('scripts')
<script>
    /**
     * FUNCIÓN: Actualizar la visualización del carrito
     */
    function updateCartDisplay() {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        const cartItemsDiv = document.getElementById('cartItems');
        const itemCount = document.getElementById('itemCount');

        // Actualizar contador de items
        const totalItems = carrito.reduce((sum, item) => sum + item.cantidad, 0);
        itemCount.textContent = `${totalItems} ${totalItems === 1 ? 'item' : 'items'}`;

        // Si no hay productos en el carrito
        if (carrito.length === 0) {
            cartItemsDiv.innerHTML = `
                <div class="flex flex-col items-center justify-center h-full text-center">
                    <i class="fas fa-shopping-cart text-gray-500 text-5xl mb-4"></i>
                    <p class="text-gray-400 text-lg">Tu carrito está vacío</p>
                    <p class="text-gray-500 text-sm mt-2">Agrega productos para comenzar</p>
                </div>
            `;
            return;
        }

        // Renderizar cada item del carrito
        cartItemsDiv.innerHTML = carrito.map((item, index) => `
            <div class="bg-gray-700 p-4 rounded-lg mb-3 hover:bg-gray-600 transition border border-gray-600">
                
                <!-- Fila 1: Imagen + Info -->
                <div class="flex gap-3 mb-3">
                    <img src="${item.imagen ? '/storage/' + item.imagen : 'https://via.placeholder.com/60?text=Producto'}" 
                         alt="${item.nombre}" 
                         class="w-16 h-16 rounded object-cover border border-gray-500">
                    <div class="flex-1">
                        <h4 class="text-white font-semibold line-clamp-2">${item.nombre}</h4>
                        <p class="text-gold font-bold text-sm">$${parseFloat(item.precio).toFixed(2)}</p>
                    </div>
                </div>
                
                <!-- Fila 2: Cantidad + Eliminar -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 bg-gray-800 rounded px-2 py-1">
                        <button onclick="decrementCartItem(${index})" class="text-white hover:text-gold transition px-2">−</button>
                        <span class="w-6 text-center text-white font-bold">${item.cantidad}</span>
                        <button onclick="incrementCartItem(${index})" class="text-white hover:text-gold transition px-2">+</button>
                    </div>
                    <button onclick="removeCartItem(${index})" class="text-red-500 hover:text-red-700 transition text-sm">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </div>
            </div>
        `).join('');

        // Actualizar totales
        updateCartTotals();
    }

    /**
     * FUNCIÓN: Actualizar totales (subtotal, impuesto, total)
     */
    function updateCartTotals() {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        const subtotal = carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
        const tax = subtotal * 0.10;
        const total = subtotal + tax;

        document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
        document.getElementById('tax').textContent = `$${tax.toFixed(2)}`;
        document.getElementById('total').textContent = `$${total.toFixed(2)}`;

        // Deshabilitar botón de pago si el carrito está vacío
        document.getElementById('checkoutBtn').disabled = carrito.length === 0;
    }

    /**
     * FUNCIÓN: Aumentar cantidad de un item
     */
    function incrementCartItem(index) {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        if (carrito[index]) {
            carrito[index].cantidad++;
            localStorage.setItem('carrito', JSON.stringify(carrito));
            updateCartDisplay();
            window.dispatchEvent(new Event('cartUpdated'));
        }
    }

    /**
     * FUNCIÓN: Disminuir cantidad de un item
     */
    function decrementCartItem(index) {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        if (carrito[index]) {
            if (carrito[index].cantidad > 1) {
                carrito[index].cantidad--;
            } else {
                carrito.splice(index, 1);
            }
            localStorage.setItem('carrito', JSON.stringify(carrito));
            updateCartDisplay();
            window.dispatchEvent(new Event('cartUpdated'));
        }
    }

    /**
     * FUNCIÓN: Eliminar un item del carrito
     */
    function removeCartItem(index) {
        Swal.fire({
            title: '¿Eliminar producto?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: 'var(--color-gold)',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
                carrito.splice(index, 1);
                localStorage.setItem('carrito', JSON.stringify(carrito));
                updateCartDisplay();
                window.dispatchEvent(new Event('cartUpdated'));
            }
        });
    }

    /**
     * FUNCIÓN: Vaciar todo el carrito
     */
    function clearCart() {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Se vaciará todo tu carrito',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: 'var(--color-gold)',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Sí, vaciar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                localStorage.removeItem('carrito');
                updateCartDisplay();
                window.dispatchEvent(new Event('cartUpdated'));
            }
        });
    }

    /**
     * FUNCIÓN: Ir al checkout
     */
    function proceedToCheckout() {
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

        window.location.href = '/cliente/carrito';
    }

    /**
     * EVENTO: Actualizar carrito al cargar la página
     */
    document.addEventListener('DOMContentLoaded', updateCartDisplay);

    /**
     * EVENTO: Escuchar cambios en localStorage (otra pestaña)
     */
    window.addEventListener('storage', updateCartDisplay);

    /**
     * EVENTO: Escuchar evento personalizado de carrito actualizado
     */
    window.addEventListener('cartUpdated', updateCartDisplay);
</script>
@endpush