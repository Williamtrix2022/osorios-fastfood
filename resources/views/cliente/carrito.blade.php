@extends('layouts.app-cliente')

@section('title', 'Carrito de Compras - OsoriosFoodApp')

@section('content')
<div class="cart-page fade-in-up">

    <!-- ===== BREADCRUMB ===== -->
    <div class="breadcrumb-nav mb-8">
        <a href="{{ route('cliente.menu') }}" class="breadcrumb-link">
            <i class="fas fa-home mr-2"></i>Menú
        </a>
        <i class="fas fa-chevron-right text-gray-500 mx-3"></i>
        <span class="text-accent font-semibold">Carrito</span>
    </div>

    <!-- ===== TÍTULO ===== -->
    <div class="page-header mb-10">
        <h1 class="page-title">
            <i class="fas fa-shopping-cart text-accent mr-4"></i>
            Mi Carrito de Compras
        </h1>
        <p class="page-subtitle">Revisa tus productos y confirma tu pedido</p>
    </div>

    <!-- ===== CONTENIDO EN 2 COLUMNAS ===== -->
    <div class="cart-layout">

        <!-- IZQUIERDA: ITEMS DEL CARRITO -->
        <div class="cart-items-section">
            <div id="cartItemsContainer">
                <!-- Loader inicial -->
                <div class="loading-state">
                    <div class="inline-block animate-spin mb-4">
                        <i class="fas fa-spinner text-5xl text-accent"></i>
                    </div>
                    <p class="text-gray-400 text-lg">Cargando tu carrito...</p>
                </div>
            </div>
        </div>

        <!-- DERECHA: RESUMEN Y FORMULARIO -->
        <div class="cart-summary-section">
            <div class="summary-card sticky top-24">

                <!-- Título -->
                <div class="summary-header">
                    <h2 class="summary-title">
                        <i class="fas fa-file-invoice text-accent mr-2"></i>
                        Resumen del Pedido
                    </h2>
                </div>

                <!-- Totales -->
                <div class="summary-totals">
                    <div class="total-row">
                        <span>Subtotal:</span>
                        <span id="resumenSubtotal" class="total-value">$0.00</span>
                    </div>
                    <div class="total-row">
                        <span>Impuesto (10%):</span>
                        <span id="resumenTax" class="total-value">$0.00</span>
                    </div>
                    <div class="total-row-final">
                        <span>Total:</span>
                        <span id="resumenTotal" class="total-final">$0.00</span>
                    </div>
                </div>

                <!-- Formulario -->
                <form id="checkoutForm" class="checkout-form">
                    @csrf

                    <!-- Método de Pago -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-credit-card mr-2 text-accent"></i>
                            Método de Pago
                        </label>
                        <select id="metodoPago" name="metodo_pago" required class="form-select">
                            <option value="">-- Selecciona una opción --</option>
                            <option value="efectivo">💵 Efectivo (Contraentrega)</option>
                            <option value="tarjeta">💳 Tarjeta de Crédito/Débito</option>
                            <option value="transferencia">🏦 Transferencia Bancaria</option>
                        </select>
                    </div>

                    <!-- Notas Adicionales -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-sticky-note mr-2 text-accent"></i>
                            Instrucciones Especiales <span class="text-gray-500 text-sm">(Opcional)</span>
                        </label>
                        <textarea
                            id="notas"
                            name="notas"
                            rows="4"
                            placeholder="Ej: Sin cebolla, extra salsa, tocar el timbre..."
                            class="form-textarea"
                        ></textarea>
                    </div>

                    <!-- Botón Confirmar Pedido -->
                    <button type="submit" class="btn-confirm-order" id="submitBtn">
                        <i class="fas fa-check-circle mr-2"></i>
                        Confirmar Pedido
                    </button>

                    <!-- Botón Seguir Comprando -->
                    <a href="{{ route('cliente.menu') }}" class="btn-continue-shopping">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Seguir Comprando
                    </a>
                </form>

                <!-- Seguridad y garantía -->
                <div class="security-badge">
                    <i class="fas fa-shield-alt text-green-500"></i>
                    <span>Pago 100% seguro y protegido</span>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('css')
<style>
    /* ===== BREADCRUMB ===== */
    .breadcrumb-nav {
        display: flex;
        align-items: center;
        font-size: 0.9rem;
    }

    .breadcrumb-link {
        color: var(--color-text-muted);
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .breadcrumb-link:hover {
        color: var(--color-accent);
    }

    /* ===== PAGE HEADER ===== */
    .page-header {
        text-align: center;
    }

    .page-title {
        font-size: 3rem;
        font-weight: 800;
        color: var(--color-text);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
    }

    .page-subtitle {
        font-size: 1.125rem;
        color: var(--color-text-muted);
    }

    @media (max-width: 768px) {
        .page-title {
            font-size: 2rem;
        }
    }

    /* ===== LAYOUT DEL CARRITO ===== */
    .cart-layout {
        display: grid;
        grid-template-columns: 1fr 450px;
        gap: 2rem;
        align-items: start;
    }

    @media (max-width: 1024px) {
        .cart-layout {
            grid-template-columns: 1fr;
        }
    }

    /* ===== SECCIÓN DE ITEMS ===== */
    .cart-items-section {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .loading-state {
        text-align: center;
        padding: 4rem 2rem;
        background: var(--color-secondary);
        border-radius: var(--border-radius);
        border: 2px solid rgba(248, 250, 252, 0.1);
    }

    /* ===== TARJETA DE ITEM DEL CARRITO ===== */
    .cart-item-card {
        background: var(--color-secondary);
        border-radius: var(--border-radius);
        padding: 1.5rem;
        border: 2px solid transparent;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: var(--shadow-sm);
    }

    .cart-item-card:hover {
        border-color: var(--color-accent);
        box-shadow: 0 8px 24px rgba(245, 158, 11, 0.2);
        transform: translateY(-2px);
    }

    .item-content {
        display: flex;
        gap: 1.5rem;
        align-items: stretch;
    }

    .item-image {
        flex-shrink: 0;
        width: 120px;
        height: 120px;
        border-radius: 12px;
        overflow: hidden;
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    }

    .item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .cart-item-card:hover .item-image img {
        transform: scale(1.1);
    }

    .item-info {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .item-name {
        font-size: 1.375rem;
        font-weight: 700;
        color: var(--color-text);
        margin-bottom: 0.5rem;
    }

    .item-price-unit {
        color: var(--color-text-muted);
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }

    .item-price-unit .price-value {
        color: var(--color-accent);
        font-weight: 700;
        font-size: 1.125rem;
    }

    .item-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    /* Control de cantidad */
    .quantity-control {
        display: flex;
        align-items: center;
        gap: 1rem;
        background: var(--color-primary);
        padding: 0.5rem 1rem;
        border-radius: 12px;
    }

    .quantity-btn {
        width: 36px;
        height: 36px;
        background: var(--color-accent);
        color: var(--color-primary);
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 1rem;
        font-weight: 700;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .quantity-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    }

    .quantity-btn:active {
        transform: scale(0.95);
    }

    .quantity-value {
        min-width: 40px;
        text-align: center;
        color: var(--color-text);
        font-weight: 700;
        font-size: 1.125rem;
    }

    /* Precio total y eliminar */
    .item-total {
        text-align: right;
    }

    .item-total-price {
        color: var(--color-accent);
        font-weight: 800;
        font-size: 1.75rem;
        display: block;
        margin-bottom: 0.5rem;
    }

    .btn-remove {
        background: none;
        border: none;
        color: var(--color-danger);
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.2s ease;
        padding: 0.25rem 0;
    }

    .btn-remove:hover {
        color: #dc2626;
        text-decoration: underline;
    }

    /* ===== RESUMEN Y FORMULARIO ===== */
    .summary-card {
        background: var(--color-secondary);
        border-radius: var(--border-radius);
        padding: 2rem;
        border: 2px solid rgba(248, 250, 252, 0.1);
        box-shadow: var(--shadow-md);
    }

    .summary-header {
        margin-bottom: 1.5rem;
    }

    .summary-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--color-text);
        display: flex;
        align-items: center;
    }

    /* Totales */
    .summary-totals {
        padding: 1.5rem 0;
        margin-bottom: 1.5rem;
        border-bottom: 2px solid rgba(248, 250, 252, 0.1);
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        color: var(--color-text-muted);
        font-size: 1rem;
    }

    .total-value {
        font-weight: 600;
        color: var(--color-text);
    }

    .total-row-final {
        display: flex;
        justify-content: space-between;
        padding: 1rem 0;
        margin-top: 0.5rem;
        border-top: 2px solid rgba(245, 158, 11, 0.3);
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--color-text);
    }

    .total-final {
        color: var(--color-accent);
        font-size: 1.75rem;
        font-weight: 800;
    }

    /* Formulario */
    .checkout-form {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-label {
        color: var(--color-text);
        font-weight: 600;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
    }

    .form-select,
    .form-textarea {
        width: 100%;
        padding: 1rem;
        background: var(--color-primary);
        color: var(--color-text);
        border: 2px solid rgba(248, 250, 252, 0.1);
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        font-family: 'Poppins', sans-serif;
    }

    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: var(--color-accent);
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
    }

    .form-textarea {
        resize: vertical;
        min-height: 100px;
    }

    .form-textarea::placeholder {
        color: var(--color-text-muted);
    }

    /* Botones */
    .btn-confirm-order {
        width: 100%;
        padding: 1.125rem;
        background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-accent-light) 100%);
        color: var(--color-primary);
        font-weight: 700;
        font-size: 1.125rem;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        box-shadow: 0 4px 16px rgba(245, 158, 11, 0.4);
    }

    .btn-confirm-order:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 24px rgba(245, 158, 11, 0.6);
    }

    .btn-confirm-order:active {
        transform: translateY(0);
    }

    .btn-confirm-order:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .btn-continue-shopping {
        width: 100%;
        padding: 1rem;
        background: transparent;
        color: var(--color-text);
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        border: 2px solid rgba(248, 250, 252, 0.2);
        border-radius: 12px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-continue-shopping:hover {
        background: rgba(248, 250, 252, 0.05);
        border-color: rgba(248, 250, 252, 0.3);
    }

    /* Badge de seguridad */
    .security-badge {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 2px solid rgba(248, 250, 252, 0.1);
        text-align: center;
        color: var(--color-text-muted);
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 640px) {
        .item-content {
            flex-direction: column;
        }

        .item-image {
            width: 100%;
            height: 200px;
        }

        .item-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .item-total {
            text-align: left;
        }

        .quantity-control {
            justify-content: center;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    /**
     * Formatea un precio en pesos colombianos
     */
    function formatPrecio(precio) {
        return '$ ' + parseFloat(precio).toLocaleString('es-CO', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
    }

    /**
     * ===== CARGAR ITEMS DEL CARRITO =====
     */
    function loadCartItems() {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        const container = document.getElementById('cartItemsContainer');

        // Si el carrito está vacío
        if (carrito.length === 0) {
            container.innerHTML = `
                <div class="loading-state">
                    <i class="fas fa-shopping-cart text-gray-500 text-6xl mb-4 block"></i>
                    <p class="text-gray-400 text-xl mb-2">Tu carrito está vacío</p>
                    <p class="text-gray-500 text-sm mb-6">¡Explora nuestro delicioso menú!</p>
                    <a href="{{ route('cliente.menu') }}"
                       class="inline-block px-8 py-3 rounded-xl font-bold transition"
                       style="background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-accent-light) 100%); color: var(--color-primary);">
                        <i class="fas fa-utensils mr-2"></i>Ver Menú
                    </a>
                </div>
            `;
            updateTotals();
            return;
        }

        // Crear HTML para cada item
        container.innerHTML = carrito.map((item, index) => {
            const imgSrc = item.imagen
                ? `/storage/${item.imagen}`
                : `https://via.placeholder.com/120x120/1e293b/f59e0b?text=${encodeURIComponent(item.nombre.substring(0, 1))}`;

            return `
                <div class="cart-item-card fade-in" style="animation-delay: ${index * 0.1}s">
                    <div class="item-content">
                        <div class="item-image">
                            <img src="${imgSrc}" alt="${item.nombre}">
                        </div>

                        <div class="item-info">
                            <div>
                                <h3 class="item-name">${item.nombre}</h3>
                                <p class="item-price-unit">
                                    Precio unitario: <span class="price-value">${formatPrecio(item.precio)}</span>
                                </p>
                            </div>

                            <div class="item-actions">
                                <div class="quantity-control">
                                    <button onclick="decrementItem(${index})" class="quantity-btn" aria-label="Disminuir">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <span class="quantity-value">${item.cantidad}</span>
                                    <button onclick="incrementItem(${index})" class="quantity-btn" aria-label="Aumentar">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>

                                <div class="item-total">
                                    <span class="item-total-price">${formatPrecio(item.precio * item.cantidad)}</span>
                                    <button onclick="removeItem(${index})" class="btn-remove">
                                        <i class="fas fa-trash-alt mr-1"></i>Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }).join('');

        updateTotals();
    }

    /**
     * ===== AUMENTAR CANTIDAD =====
     */
    function incrementItem(index) {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        if (carrito[index]) {
            carrito[index].cantidad++;
            localStorage.setItem('carrito', JSON.stringify(carrito));
            loadCartItems();
            window.dispatchEvent(new Event('cartUpdated'));
        }
    }

    /**
     * ===== DISMINUIR CANTIDAD =====
     */
    function decrementItem(index) {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        if (carrito[index]) {
            if (carrito[index].cantidad > 1) {
                carrito[index].cantidad--;
                localStorage.setItem('carrito', JSON.stringify(carrito));
                loadCartItems();
                window.dispatchEvent(new Event('cartUpdated'));
            } else {
                removeItem(index);
            }
        }
    }

    /**
     * ===== ELIMINAR ITEM =====
     */
    function removeItem(index) {
        Swal.fire({
            title: '¿Eliminar producto?',
            text: '¿Estás seguro de quitar este producto del carrito?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fas fa-check mr-2"></i>Sí, eliminar',
            cancelButtonText: 'Cancelar',
            background: '#1e293b',
            color: '#f8fafc',
            customClass: {
                popup: 'rounded-2xl'
            }
        }).then(result => {
            if (result.isConfirmed) {
                const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
                const producto = carrito[index];
                carrito.splice(index, 1);
                localStorage.setItem('carrito', JSON.stringify(carrito));
                loadCartItems();
                window.dispatchEvent(new Event('cartUpdated'));

                Swal.fire({
                    icon: 'success',
                    title: 'Eliminado',
                    text: `${producto.nombre} fue eliminado del carrito`,
                    timer: 2000,
                    showConfirmButton: false,
                    background: '#1e293b',
                    color: '#f8fafc'
                });
            }
        });
    }

    /**
     * ===== ACTUALIZAR TOTALES =====
     */
    function updateTotals() {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        const subtotal = carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
        const tax = subtotal * 0.10;
        const total = subtotal + tax;

        document.getElementById('resumenSubtotal').textContent = formatPrecio(subtotal);
        document.getElementById('resumenTax').textContent = formatPrecio(tax);
        document.getElementById('resumenTotal').textContent = formatPrecio(total);
    }

    /**
     * ===== ENVIAR PEDIDO =====
     */
    document.getElementById('checkoutForm')?.addEventListener('submit', async (e) => {
        e.preventDefault();

        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];

        if (carrito.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Carrito vacío',
                text: 'Agrega productos antes de continuar',
                confirmButtonColor: '#f59e0b',
                background: '#1e293b',
                color: '#f8fafc'
            });
            return;
        }

        const metodoPago = document.getElementById('metodoPago').value;
        if (!metodoPago) {
            Swal.fire({
                icon: 'warning',
                title: 'Campo requerido',
                text: 'Por favor selecciona un método de pago',
                confirmButtonColor: '#f59e0b',
                background: '#1e293b',
                color: '#f8fafc'
            });
            return;
        }

        const notas = document.getElementById('notas').value;
        const items = carrito.map(item => ({
            producto_id: item.id,
            cantidad: item.cantidad,
            precio_unitario: item.precio
        }));

        // Deshabilitar botón y mostrar loading
        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner animate-spin mr-2"></i>Procesando tu pedido...';

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
                window.dispatchEvent(new Event('cartUpdated'));

                // Mostrar éxito con confetti
                Swal.fire({
                    icon: 'success',
                    title: '¡Pedido Confirmado!',
                    html: `
                        <p class="mb-2">Tu pedido ha sido creado exitosamente</p>
                        <p class="text-sm text-gray-400">Número de pedido: #${data.pedido_id}</p>
                    `,
                    confirmButtonColor: '#f59e0b',
                    confirmButtonText: 'Ver mi pedido',
                    background: '#1e293b',
                    color: '#f8fafc'
                }).then(() => {
                    window.location.href = `/cliente/pedido/${data.pedido_id}`;
                });
            } else {
                throw new Error(data.message || 'Error al procesar el pedido');
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Hubo un problema al procesar tu pedido',
                confirmButtonColor: '#f59e0b',
                background: '#1e293b',
                color: '#f8fafc'
            });
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });

    // ===== INICIALIZAR =====
    document.addEventListener('DOMContentLoaded', loadCartItems);
    window.addEventListener('cartUpdated', loadCartItems);
</script>
@endpush

