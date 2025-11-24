<!-- ===== HEADER DEL SIDEBAR ===== -->
<div class="sidebar-header">
    <div class="header-content">
        <div class="header-title">
            <i class="fas fa-shopping-bag"></i>
            <span>Mi Carrito</span>
        </div>
        <button onclick="toggleCartSidebar()" class="close-sidebar-btn">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="item-counter" id="itemCount">
        <i class="fas fa-cube mr-2"></i>
        <span>0 productos</span>
    </div>
</div>

<!-- ===== ITEMS DEL CARRITO ===== -->
<div class="sidebar-items" id="cartItems">
    <div class="empty-cart-state">
        <i class="fas fa-shopping-bag"></i>
        <p class="empty-title">Tu carrito está vacío</p>
        <p class="empty-subtitle">¡Empieza a agregar productos deliciosos!</p>
    </div>
</div>

<!-- ===== RESUMEN Y ACCIONES ===== -->
<div class="sidebar-footer">

    <!-- Totales -->
    <div class="totals-section">
        <div class="total-row">
            <span>Subtotal</span>
            <span id="subtotal" class="total-value">$0.00</span>
        </div>
        <div class="total-row">
            <span>Impuesto (10%)</span>
            <span id="tax" class="total-value">$0.00</span>
        </div>
        <div class="total-row-grand">
            <span>Total a Pagar</span>
            <span id="total" class="total-grand">$0.00</span>
        </div>
    </div>

    <!-- Botones de acción -->
    <div class="actions-section">
        <a href="{{ route('cliente.carrito') }}" class="btn-checkout" id="checkoutBtn">
            <i class="fas fa-credit-card mr-2"></i>
            <span>Ir a Pagar</span>
        </a>

        <button onclick="clearCart()" class="btn-clear">
            <i class="fas fa-trash-alt mr-2"></i>
            <span>Vaciar Carrito</span>
        </button>
    </div>

    <!-- Información adicional -->
    <div class="footer-info">
        <i class="fas fa-shield-check text-green-500"></i>
        <span>Compra segura y protegida</span>
    </div>
</div>

<!-- ===== ESTILOS DEL SIDEBAR ===== -->
<style>
    /* ===== HEADER DEL SIDEBAR ===== */
    .sidebar-header {
        padding: 1.5rem;
        background: linear-gradient(135deg, var(--color-secondary) 0%, var(--color-primary) 100%);
        border-bottom: 2px solid rgba(245, 158, 11, 0.2);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .header-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--color-text);
    }

    .header-title i {
        color: var(--color-accent);
        font-size: 1.75rem;
    }

    .close-sidebar-btn {
        width: 40px;
        height: 40px;
        background: rgba(239, 68, 68, 0.1);
        border: 2px solid rgba(239, 68, 68, 0.3);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--color-danger);
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1.125rem;
    }

    .close-sidebar-btn:hover {
        background: var(--color-danger);
        color: white;
        transform: rotate(90deg);
    }

    .item-counter {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        background: rgba(245, 158, 11, 0.1);
        border: 2px solid rgba(245, 158, 11, 0.3);
        border-radius: 12px;
        color: var(--color-accent);
        font-weight: 600;
        font-size: 0.95rem;
    }

    /* ===== ITEMS DEL CARRITO ===== */
    .sidebar-items {
        flex: 1;
        overflow-y: auto;
        padding: 1rem;
        background: var(--color-primary);
    }

    .empty-cart-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        text-align: center;
        padding: 2rem;
    }

    .empty-cart-state i {
        font-size: 4rem;
        color: var(--color-text-muted);
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--color-text);
        margin-bottom: 0.5rem;
    }

    .empty-subtitle {
        font-size: 0.9rem;
        color: var(--color-text-muted);
    }

    /* Tarjeta de item del carrito */
    .cart-item {
        background: var(--color-secondary);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        animation: slideInRight 0.3s ease-out;
    }

    .cart-item:hover {
        border-color: var(--color-accent);
        box-shadow: 0 4px 16px rgba(245, 158, 11, 0.2);
    }

    .item-main {
        display: flex;
        gap: 0.875rem;
        margin-bottom: 0.875rem;
    }

    .item-image-small {
        width: 70px;
        height: 70px;
        border-radius: 10px;
        overflow: hidden;
        flex-shrink: 0;
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    }

    .item-image-small img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .cart-item:hover .item-image-small img {
        transform: scale(1.1);
    }

    .item-details {
        flex: 1;
        min-width: 0;
    }

    .item-name {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--color-text);
        margin-bottom: 0.25rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.3;
    }

    .item-price {
        font-size: 1.125rem;
        font-weight: 800;
        color: var(--color-accent);
    }

    .item-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.5rem;
    }

    .quantity-mini {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--color-primary);
        padding: 0.375rem 0.75rem;
        border-radius: 10px;
    }

    .quantity-mini-btn {
        width: 28px;
        height: 28px;
        background: var(--color-accent);
        color: var(--color-primary);
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .quantity-mini-btn:hover {
        transform: scale(1.1);
    }

    .quantity-mini-btn:active {
        transform: scale(0.9);
    }

    .quantity-mini-value {
        min-width: 30px;
        text-align: center;
        color: var(--color-text);
        font-weight: 700;
        font-size: 0.95rem;
    }

    .item-remove {
        background: none;
        border: none;
        color: var(--color-danger);
        cursor: pointer;
        font-size: 0.875rem;
        padding: 0.5rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .item-remove:hover {
        color: #dc2626;
        transform: scale(1.1);
    }

    /* ===== FOOTER DEL SIDEBAR ===== */
    .sidebar-footer {
        padding: 1.5rem;
        background: var(--color-secondary);
        border-top: 2px solid rgba(245, 158, 11, 0.2);
        box-shadow: 0 -8px 24px rgba(0, 0, 0, 0.3);
    }

    .totals-section {
        padding: 1.25rem;
        background: var(--color-primary);
        border-radius: 12px;
        margin-bottom: 1rem;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        color: var(--color-text-muted);
        font-size: 0.95rem;
    }

    .total-value {
        font-weight: 600;
        color: var(--color-text);
    }

    .total-row-grand {
        display: flex;
        justify-content: space-between;
        padding: 1rem 0;
        margin-top: 0.5rem;
        border-top: 2px solid rgba(245, 158, 11, 0.3);
        font-size: 1.0625rem;
        font-weight: 700;
        color: var(--color-text);
    }

    .total-grand {
        font-size: 1.625rem;
        font-weight: 800;
        color: var(--color-accent);
    }

    .actions-section {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .btn-checkout {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-accent-light) 100%);
        color: var(--color-primary);
        font-weight: 700;
        font-size: 1.0625rem;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none;
        box-shadow: 0 4px 16px rgba(245, 158, 11, 0.4);
    }

    .btn-checkout:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 24px rgba(245, 158, 11, 0.6);
    }

    .btn-checkout:active {
        transform: translateY(0);
    }

    .btn-checkout:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .btn-clear {
        width: 100%;
        padding: 0.875rem;
        background: transparent;
        color: var(--color-text);
        font-weight: 600;
        border: 2px solid rgba(248, 250, 252, 0.2);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-clear:hover {
        background: rgba(239, 68, 68, 0.1);
        border-color: var(--color-danger);
        color: var(--color-danger);
    }

    .footer-info {
        text-align: center;
        padding-top: 1rem;
        border-top: 1px solid rgba(248, 250, 252, 0.1);
        color: var(--color-text-muted);
        font-size: 0.8125rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
</style>

<!-- ===== SCRIPTS DEL SIDEBAR ===== -->
@push('scripts')
<script>
    /**
     * ===== ACTUALIZAR VISUALIZACIÓN DEL CARRITO =====
     */
    function updateCartDisplay() {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        const cartItemsDiv = document.getElementById('cartItems');
        const itemCount = document.getElementById('itemCount');

        // Actualizar contador
        const totalItems = carrito.reduce((sum, item) => sum + item.cantidad, 0);
        itemCount.innerHTML = `<i class="fas fa-cube mr-2"></i><span>${totalItems} ${totalItems === 1 ? 'producto' : 'productos'}</span>`;

        // Si el carrito está vacío
        if (carrito.length === 0) {
            cartItemsDiv.innerHTML = `
                <div class="empty-cart-state">
                    <i class="fas fa-shopping-bag"></i>
                    <p class="empty-title">Tu carrito está vacío</p>
                    <p class="empty-subtitle">¡Empieza a agregar productos deliciosos!</p>
                </div>
            `;
            document.getElementById('checkoutBtn').style.pointerEvents = 'none';
            document.getElementById('checkoutBtn').style.opacity = '0.5';
            updateCartTotals();
            return;
        }

        document.getElementById('checkoutBtn').style.pointerEvents = 'auto';
        document.getElementById('checkoutBtn').style.opacity = '1';

        // Renderizar items
        cartItemsDiv.innerHTML = carrito.map((item, index) => {
            const imgSrc = item.imagen
                ? `/storage/${item.imagen}`
                : `https://via.placeholder.com/70x70/1e293b/f59e0b?text=${encodeURIComponent(item.nombre.substring(0, 1))}`;

            return `
                <div class="cart-item" style="animation-delay: ${index * 0.05}s">
                    <div class="item-main">
                        <div class="item-image-small">
                            <img src="${imgSrc}" alt="${item.nombre}">
                        </div>
                        <div class="item-details">
                            <h4 class="item-name">${item.nombre}</h4>
                            <p class="item-price">$${parseFloat(item.precio).toFixed(2)}</p>
                        </div>
                    </div>
                    <div class="item-controls">
                        <div class="quantity-mini">
                            <button onclick="decrementCartItem(${index})" class="quantity-mini-btn">
                                <i class="fas fa-minus"></i>
                            </button>
                            <span class="quantity-mini-value">${item.cantidad}</span>
                            <button onclick="incrementCartItem(${index})" class="quantity-mini-btn">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <button onclick="removeCartItem(${index})" class="item-remove">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
            `;
        }).join('');

        updateCartTotals();
    }

    /**
     * ===== ACTUALIZAR TOTALES =====
     */
    function updateCartTotals() {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        const subtotal = carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
        const tax = subtotal * 0.10;
        const total = subtotal + tax;

        document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
        document.getElementById('tax').textContent = `$${tax.toFixed(2)}`;
        document.getElementById('total').textContent = `$${total.toFixed(2)}`;
    }

    /**
     * ===== INCREMENTAR ITEM =====
     */
    function incrementCartItem(index) {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        if (carrito[index]) {
            carrito[index].cantidad++;
            localStorage.setItem('carrito', JSON.stringify(carrito));
            updateCartDisplay();
            updateCartCount();
        }
    }

    /**
     * ===== DECREMENTAR ITEM =====
     */
    function decrementCartItem(index) {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        if (carrito[index]) {
            if (carrito[index].cantidad > 1) {
                carrito[index].cantidad--;
                localStorage.setItem('carrito', JSON.stringify(carrito));
                updateCartDisplay();
                updateCartCount();
            } else {
                removeCartItem(index);
            }
        }
    }

    /**
     * ===== ELIMINAR ITEM =====
     */
    function removeCartItem(index) {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        const producto = carrito[index];

        Swal.fire({
            title: '¿Eliminar producto?',
            text: `¿Quitar ${producto.nombre} del carrito?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fas fa-check mr-1"></i> Sí, eliminar',
            cancelButtonText: 'Cancelar',
            background: '#1e293b',
            color: '#f8fafc'
        }).then((result) => {
            if (result.isConfirmed) {
                carrito.splice(index, 1);
                localStorage.setItem('carrito', JSON.stringify(carrito));
                updateCartDisplay();
                updateCartCount();
            }
        });
    }

    /**
     * ===== VACIAR CARRITO =====
     */
    function clearCart() {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];

        if (carrito.length === 0) {
            Swal.fire({
                icon: 'info',
                title: 'Carrito vacío',
                text: 'No hay productos en tu carrito',
                confirmButtonColor: '#f59e0b',
                background: '#1e293b',
                color: '#f8fafc'
            });
            return;
        }

        Swal.fire({
            title: '¿Vaciar carrito?',
            text: 'Se eliminarán todos los productos',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fas fa-trash-alt mr-1"></i> Sí, vaciar',
            cancelButtonText: 'Cancelar',
            background: '#1e293b',
            color: '#f8fafc'
        }).then((result) => {
            if (result.isConfirmed) {
                localStorage.removeItem('carrito');
                updateCartDisplay();
                updateCartCount();

                Swal.fire({
                    icon: 'success',
                    title: 'Carrito vaciado',
                    text: 'Todos los productos fueron eliminados',
                    timer: 2000,
                    showConfirmButton: false,
                    background: '#1e293b',
                    color: '#f8fafc'
                });
            }
        });
    }

    // ===== INICIALIZAR Y ESCUCHAR EVENTOS =====
    document.addEventListener('DOMContentLoaded', updateCartDisplay);
    window.addEventListener('cartUpdated', updateCartDisplay);
</script>
@endpush

