@extends('layouts.app-cliente')

@section('title', 'Menú Digital - OsoriosFoodApp')

@section('content')
<div class="menu-container fade-in-up">

    <!-- HERO SECTION CON BÚSQUEDA -->
    <div class="hero-section mb-6">
        <div class="text-center mb-5">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-2 leading-tight">
                Descubre nuestro
                <span class="text-accent">Menú Delicioso</span>
            </h1>
            <p class="text-base text-gray-400 max-w-2xl mx-auto">
                Comida rápida de calidad, preparada con los mejores ingredientes
            </p>
        </div>

        <!-- BARRA DE BÚSQUEDA MEJORADA -->
        <div class="search-bar max-w-2xl mx-auto">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-base"></i>
                <input
                    type="text"
                    id="searchInput"
                    placeholder="Busca tu comida favorita..."
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-secondary text-white text-sm border-2 border-transparent focus:border-accent focus:outline-none transition shadow-lg"
                >
            </div>
        </div>
    </div>

    <!-- CATEGORÍAS CON SCROLL HORIZONTAL -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-lg font-bold text-white">
                <i class="fas fa-list-ul text-accent mr-2"></i>
                Categorías
            </h2>
        </div>
        <div class="categories-scroll overflow-x-auto pb-2">
            <div class="flex gap-2 min-w-max" id="categoriesContainer">
                <button
                    onclick="filterByCategory('todas')"
                    class="categoria-chip active"
                    data-category="todas"
                >
                    <i class="fas fa-fire mr-2"></i>Todas
                </button>
            </div>
        </div>
    </div>

    <!-- GRID DE PRODUCTOS MODERNO -->
    <div id="productsGrid" class="products-grid">
        <!-- Loader inicial -->
        <div class="col-span-full text-center py-16">
            <div class="inline-block animate-spin mb-4">
                <i class="fas fa-spinner text-5xl text-accent"></i>
            </div>
            <p class="text-gray-400 text-lg">Cargando productos deliciosos...</p>
        </div>
    </div>

    <!-- TEMPLATE PRODUCTO CON DISEÑO MODERNO -->
    <template id="productTemplate">
        <div class="product-card group" onclick="showProductDetail(this.dataset.id)">
            <!-- Imagen del producto con placeholder -->
            <div class="product-image-container">
                <div class="product-image-wrapper">
                    <img src="" alt="" class="product-image">
                    <div class="product-overlay">
                        <div class="overlay-content">
                            <i class="fas fa-eye text-3xl"></i>
                            <span class="text-sm font-semibold">Ver detalles</span>
                        </div>
                    </div>
                </div>
                <div class="product-badge">
                    <span class="precio">$0.00</span>
                </div>
            </div>

            <!-- Información del producto -->
            <div class="product-info">
                <h3 class="product-title nombre"></h3>
                <p class="product-description descripcion"></p>

                <!-- Botón agregar al carrito -->
                <button
                    onclick="event.stopPropagation(); addToCart(this.closest('.product-card'))"
                    class="add-to-cart-btn"
                >
                    <i class="fas fa-cart-plus mr-2"></i>
                    <span>Agregar</span>
                </button>
            </div>
        </div>
    </template>

    <!-- TEMPLATE CATEGORÍA -->
    <template id="categoryTemplate">
        <button
            class="categoria-chip"
            data-category=""
            onclick="filterByCategory(this.dataset.category)"
        >
        </button>
    </template>

</div>

<!-- MODAL DE PRODUCTO MEJORADO -->
<div id="productModal" class="product-modal">
    <div class="product-modal-content">
        <!-- Botón cerrar -->
        <button onclick="closeProductModal()" class="modal-close-btn">
            <i class="fas fa-times"></i>
        </button>

        <!-- Imagen del producto -->
        <div class="modal-image-section">
            <img id="modalImage" src="" alt="" class="modal-product-image">
            <div class="modal-price-badge">
                <span id="modalPrice">$0.00</span>
            </div>
        </div>

        <!-- Información del producto -->
        <div class="modal-info-section">
            <h2 id="modalNombre" class="modal-title"></h2>
            <p id="modalDescripcion" class="modal-description"></p>

            <!-- Control de cantidad -->
            <div class="quantity-section">
                <label class="quantity-label">
                    <i class="fas fa-sort-numeric-up mr-2"></i>
                    Cantidad:
                </label>
                <div class="quantity-controls">
                    <button onclick="decreaseQuantity()" class="quantity-btn">
                        <i class="fas fa-minus"></i>
                    </button>
                    <input type="number" id="modalQuantity" value="1" min="1" class="quantity-input" readonly>
                    <button onclick="increaseQuantity()" class="quantity-btn">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="modal-actions">
                <button onclick="closeProductModal()" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </button>
                <button onclick="addToCartFromModal()" class="btn-primary">
                    <i class="fas fa-cart-plus mr-2"></i>
                    Agregar al Carrito
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    let productos = [];
    let currentProductId = null;

    // ===== CARGAR PRODUCTOS =====
    async function loadProductos() {
        try {
            const response = await fetch('/cliente/api/productos');
            if (!response.ok) throw new Error('Error al cargar productos');

            productos = await response.json();
            renderProductos('todas');
            renderCategorias();
            updateCartCount();
        } catch (error) {
            console.error('Error:', error);
            document.getElementById('productsGrid').innerHTML = `
                <div class="col-span-full text-center py-16">
                    <i class="fas fa-exclamation-circle text-5xl text-red-500 mb-4"></i>
                    <p class="text-gray-400 text-lg">Error al cargar los productos</p>
                    <button onclick="loadProductos()" class="mt-4 px-6 py-3 bg-accent text-white rounded-lg font-semibold hover:opacity-90">
                        Reintentar
                    </button>
                </div>
            `;
        }
    }

    // ===== RENDERIZAR PRODUCTOS =====
    function renderProductos(filter = 'todas') {
        const grid = document.getElementById('productsGrid');
        grid.innerHTML = '';

        let filtered = productos;
        if (filter !== 'todas') {
            filtered = productos.filter(p => p.categoria_id == filter);
        }

        if (filtered.length === 0) {
            grid.innerHTML = `
                <div class="col-span-full text-center py-16">
                    <i class="fas fa-search text-5xl text-gray-500 mb-4"></i>
                    <p class="text-gray-400 text-lg">No se encontraron productos</p>
                </div>
            `;
            return;
        }

        filtered.forEach((producto, index) => {
            const template = document.getElementById('productTemplate');
            const clone = template.content.cloneNode(true);

            const card = clone.querySelector('.product-card');
            card.dataset.id = producto.id;
            card.style.animationDelay = `${index * 0.05}s`;

            // Imagen del producto o placeholder
            const imgSrc = producto.imagen
                ? `/storage/${producto.imagen}`
                : `https://via.placeholder.com/400x300/1e293b/f59e0b?text=${encodeURIComponent(producto.nombre)}`;

            clone.querySelector('img').src = imgSrc;
            clone.querySelector('img').alt = producto.nombre;
            clone.querySelector('.nombre').textContent = producto.nombre;
            clone.querySelector('.descripcion').textContent = producto.descripcion || 'Producto delicioso';
            clone.querySelector('.precio').textContent = `$${parseFloat(producto.precio).toFixed(2)}`;

            grid.appendChild(clone);
        });
    }

    // ===== RENDERIZAR CATEGORÍAS =====
    async function renderCategorias() {
        try {
            const response = await fetch('/cliente/api/categorias');
            const categorias = await response.json();

            const container = document.getElementById('categoriesContainer');
            const template = document.getElementById('categoryTemplate');

            categorias.forEach(cat => {
                const clone = template.content.cloneNode(true);
                const btn = clone.querySelector('.categoria-chip');
                btn.dataset.category = cat.id;
                btn.innerHTML = `<i class="fas fa-tag mr-2"></i>${cat.nombre}`;
                container.appendChild(clone);
            });
        } catch (error) {
            console.error('Error cargando categorías:', error);
        }
    }

    // ===== FILTRAR POR CATEGORÍA =====
    function filterByCategory(categoryId) {
        // Actualizar botones activos
        document.querySelectorAll('.categoria-chip').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelector(`[data-category="${categoryId}"]`).classList.add('active');

        // Renderizar productos filtrados
        renderProductos(categoryId);
    }

    // ===== MOSTRAR DETALLE DEL PRODUCTO =====
    function showProductDetail(productId) {
        const producto = productos.find(p => p.id == productId);
        if (!producto) return;

        currentProductId = productId;

        const imgSrc = producto.imagen
            ? `/storage/${producto.imagen}`
            : `https://via.placeholder.com/800x600/1e293b/f59e0b?text=${encodeURIComponent(producto.nombre)}`;

        document.getElementById('modalImage').src = imgSrc;
        document.getElementById('modalNombre').textContent = producto.nombre;
        document.getElementById('modalDescripcion').textContent = producto.descripcion || 'Producto delicioso de la casa';
        document.getElementById('modalPrice').textContent = `$${parseFloat(producto.precio).toFixed(2)}`;
        document.getElementById('modalQuantity').value = 1;

        const modal = document.getElementById('productModal');
        modal.classList.remove('hidden');
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    // ===== CERRAR MODAL =====
    function closeProductModal() {
        const modal = document.getElementById('productModal');
        modal.classList.remove('active');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 300);
    }

    // ===== CONTROL DE CANTIDAD =====
    function increaseQuantity() {
        const input = document.getElementById('modalQuantity');
        input.value = parseInt(input.value) + 1;
    }

    function decreaseQuantity() {
        const input = document.getElementById('modalQuantity');
        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
        }
    }

    // ===== AGREGAR DESDE MODAL =====
    function addToCartFromModal() {
        const producto = productos.find(p => p.id == currentProductId);
        const cantidad = parseInt(document.getElementById('modalQuantity').value);
        agregarAlCarrito(producto, cantidad);
        closeProductModal();
    }

    // ===== AGREGAR DESDE TARJETA =====
    function addToCart(card) {
        const productId = card.dataset.id;
        const producto = productos.find(p => p.id == productId);
        agregarAlCarrito(producto, 1);
    }

    // ===== AGREGAR AL CARRITO =====
    function agregarAlCarrito(producto, cantidad) {
        const existe = carrito.find(item => item.id === producto.id);

        if (existe) {
            existe.cantidad += cantidad;
        } else {
            carrito.push({
                id: producto.id,
                nombre: producto.nombre,
                precio: producto.precio,
                imagen: producto.imagen,
                cantidad: cantidad
            });
        }

        localStorage.setItem('carrito', JSON.stringify(carrito));
        updateCartCount();

        // Notificación elegante
        Swal.fire({
            icon: 'success',
            title: '¡Agregado al carrito!',
            html: `<strong>${producto.nombre}</strong><br><small>${cantidad} ${cantidad > 1 ? 'unidades' : 'unidad'}</small>`,
            timer: 2000,
            showConfirmButton: false,
            background: '#1e293b',
            color: '#f8fafc',
            customClass: {
                popup: 'rounded-2xl border-2 border-amber-500'
            }
        });

        // Disparar evento para actualizar sidebar
        window.dispatchEvent(new Event('cartUpdated'));
    }

    // ===== ACTUALIZAR CONTADOR DEL CARRITO =====
    function updateCartCount() {
        const total = carrito.reduce((sum, item) => sum + item.cantidad, 0);
        document.getElementById('cartCount').textContent = total;
    }

    // ===== BÚSQUEDA DE PRODUCTOS =====
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase().trim();

        if (!term) {
            renderProductos('todas');
            return;
        }

        const filtered = productos.filter(p =>
            p.nombre.toLowerCase().includes(term) ||
            (p.descripcion && p.descripcion.toLowerCase().includes(term))
        );

        const grid = document.getElementById('productsGrid');
        grid.innerHTML = '';

        if (filtered.length === 0) {
            grid.innerHTML = `
                <div class="col-span-full text-center py-16">
                    <i class="fas fa-search text-5xl text-gray-500 mb-4"></i>
                    <p class="text-gray-400 text-lg">No se encontraron resultados para "${term}"</p>
                </div>
            `;
            return;
        }

        filtered.forEach((producto, index) => {
            const template = document.getElementById('productTemplate');
            const clone = template.content.cloneNode(true);
            const card = clone.querySelector('.product-card');
            card.dataset.id = producto.id;

            const imgSrc = producto.imagen
                ? `/storage/${producto.imagen}`
                : `https://via.placeholder.com/400x300/1e293b/f59e0b?text=${encodeURIComponent(producto.nombre)}`;

            clone.querySelector('img').src = imgSrc;
            clone.querySelector('img').alt = producto.nombre;
            clone.querySelector('.nombre').textContent = producto.nombre;
            clone.querySelector('.descripcion').textContent = producto.descripcion || 'Producto delicioso';
            clone.querySelector('.precio').textContent = `$${parseFloat(producto.precio).toFixed(2)}`;

            grid.appendChild(clone);
        });
    });

    // ===== CERRAR MODAL CON ESC Y CLICK FUERA =====
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closeProductModal();
        }
    });

    document.getElementById('productModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeProductModal();
        }
    });

    // ===== INICIALIZAR AL CARGAR LA PÁGINA =====
    document.addEventListener('DOMContentLoaded', loadProductos);

</script>
@endpush

@push('css')
<style>
    /* ===== HERO SECTION ===== */
    .hero-section {
        padding: 2rem 0;
    }

    .search-bar input {
        background: var(--color-secondary);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
    }

    .search-bar input:focus {
        box-shadow: 0 8px 32px rgba(245, 158, 11, 0.3);
        transform: translateY(-2px);
    }

    /* ===== CATEGORÍAS MODERNAS ===== */
    .categories-scroll::-webkit-scrollbar {
        height: 6px;
    }

    .categoria-chip {
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        white-space: nowrap;
        background: var(--color-secondary);
        color: var(--color-text-muted);
        border: 2px solid transparent;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .categoria-chip:hover {
        background: rgba(245, 158, 11, 0.1);
        color: var(--color-accent);
        border-color: var(--color-accent);
        transform: translateY(-2px);
    }

    .categoria-chip.active {
        background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-accent-light) 100%);
        color: var(--color-primary);
        border-color: var(--color-accent);
        box-shadow: 0 4px 16px rgba(245, 158, 11, 0.4);
    }

    /* ===== GRID DE PRODUCTOS ===== */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 640px) {
        .products-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
    }

    /* ===== TARJETA DE PRODUCTO MODERNA ===== */
    .product-card {
        background: var(--color-secondary);
        border-radius: 12px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent;
        box-shadow: var(--shadow-md);
    }

    .product-card:hover {
        transform: translateY(-8px);
        border-color: var(--color-accent);
        box-shadow: 0 16px 48px rgba(245, 158, 11, 0.25);
    }

    /* Contenedor de imagen con aspect ratio */
    .product-image-container {
        position: relative;
        width: 100%;
    }

    .product-image-wrapper {
        position: relative;
        width: 100%;
        padding-bottom: 75%; /* Aspect ratio 4:3 */
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        overflow: hidden;
    }

    .product-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .product-card:hover .product-image {
        transform: scale(1.1);
    }

    /* Overlay de la imagen */
    .product-overlay {
        position: absolute;
        inset: 0;
        background: rgba(15, 23, 42, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .product-card:hover .product-overlay {
        opacity: 1;
    }

    .overlay-content {
        text-align: center;
        color: var(--color-accent);
        transform: translateY(10px);
        transition: transform 0.3s ease;
    }

    .product-card:hover .overlay-content {
        transform: translateY(0);
    }

    /* Badge de precio */
    .product-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        padding: 0.5rem 1rem;
        border-radius: 50px;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        z-index: 10;
    }

    .product-badge .precio {
        color: white;
        font-weight: 700;
        font-size: 1.125rem;
    }

    /* Información del producto */
    .product-info {
        padding: 1.25rem;
    }

    .product-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--color-text);
        margin-bottom: 0.375rem;
        line-height: 1.4;
    }

    .product-description {
        color: var(--color-text-muted);
        font-size: 0.8125rem;
        margin-bottom: 0.875rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.5;
        min-height: 2.5em;
    }

    /* Botón agregar al carrito */
    .add-to-cart-btn {
        width: 100%;
        padding: 0.875rem;
        background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-accent-light) 100%);
        color: var(--color-primary);
        font-weight: 700;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .add-to-cart-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(245, 158, 11, 0.5);
    }

    .add-to-cart-btn:active {
        transform: translateY(0);
    }

    /* ===== MODAL DE PRODUCTO ===== */
    .product-modal {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.92);
        backdrop-filter: blur(8px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 1rem;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .product-modal.active {
        display: flex;
        opacity: 1;
    }

    .product-modal-content {
        background: var(--color-secondary);
        border-radius: 24px;
        max-width: 900px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
        display: grid;
        grid-template-columns: 1fr 1fr;
        box-shadow: var(--shadow-xl);
        border: 2px solid rgba(245, 158, 11, 0.2);
        animation: modalSlideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    @media (max-width: 768px) {
        .product-modal-content {
            grid-template-columns: 1fr;
        }
    }

    .modal-close-btn {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        width: 48px;
        height: 48px;
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(8px);
        border: 2px solid rgba(248, 250, 252, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
        z-index: 10;
        transition: all 0.3s ease;
        font-size: 1.25rem;
    }

    .modal-close-btn:hover {
        background: var(--color-danger);
        border-color: var(--color-danger);
        transform: rotate(90deg);
    }

    .modal-image-section {
        position: relative;
        min-height: 400px;
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    }

    .modal-product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .modal-price-badge {
        position: absolute;
        bottom: 1.5rem;
        left: 1.5rem;
        background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-accent-light) 100%);
        padding: 1rem 1.5rem;
        border-radius: 16px;
        box-shadow: var(--shadow-lg);
    }

    .modal-price-badge span {
        color: var(--color-primary);
        font-weight: 800;
        font-size: 2rem;
    }

    .modal-info-section {
        padding: 2.5rem;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .modal-title {
        font-size: 2rem;
        font-weight: 800;
        color: var(--color-text);
        line-height: 1.3;
    }

    .modal-description {
        color: var(--color-text-muted);
        font-size: 1rem;
        line-height: 1.8;
    }

    /* Control de cantidad */
    .quantity-section {
        padding: 1.5rem 0;
        border-top: 2px solid rgba(248, 250, 252, 0.1);
        border-bottom: 2px solid rgba(248, 250, 252, 0.1);
    }

    .quantity-label {
        display: block;
        color: var(--color-text);
        font-weight: 600;
        margin-bottom: 1rem;
        font-size: 1.125rem;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 1rem;
        background: var(--color-primary);
        padding: 0.5rem;
        border-radius: 12px;
        width: fit-content;
    }

    .quantity-btn {
        width: 44px;
        height: 44px;
        background: var(--color-accent);
        color: var(--color-primary);
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 1.125rem;
        font-weight: 700;
        transition: all 0.2s ease;
    }

    .quantity-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    }

    .quantity-btn:active {
        transform: scale(0.95);
    }

    .quantity-input {
        width: 80px;
        text-align: center;
        background: transparent;
        color: var(--color-text);
        border: none;
        font-size: 1.5rem;
        font-weight: 700;
        outline: none;
    }

    /* Botones del modal */
    .modal-actions {
        display: flex;
        gap: 1rem;
        margin-top: auto;
    }

    .btn-secondary, .btn-primary {
        flex: 1;
        padding: 1rem;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 1rem;
    }

    .btn-secondary {
        background: transparent;
        border: 2px solid rgba(248, 250, 252, 0.2);
        color: var(--color-text);
    }

    .btn-secondary:hover {
        background: rgba(248, 250, 252, 0.05);
        border-color: rgba(248, 250, 252, 0.3);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-accent-light) 100%);
        color: var(--color-primary);
        border: none;
        box-shadow: 0 4px 16px rgba(245, 158, 11, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 24px rgba(245, 158, 11, 0.5);
    }
</style>
@endpush

@push('scripts')
<script>
    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    let productos = [];
    let currentProductId = null;

    // CARGAR PRODUCTOS
    async function loadProductos() {
        try {
            const response = await fetch('/cliente/api/productos');
            productos = await response.json();
            renderProductos('todas');
            renderCategorias();
            updateCartCount();
        } catch (error) {
            console.error('Error cargando productos:', error);
            Swal.fire('Error', 'No se pudieron cargar los productos', 'error');
        }
    }

    // RENDERIZAR PRODUCTOS
    function renderProductos(filter = 'todas') {
        const grid = document.getElementById('productsGrid');
        grid.innerHTML = '';

        let filtered = productos;
        if (filter !== 'todas') {
            filtered = productos.filter(p => p.categoria_id == filter);
        }

        if (filtered.length === 0) {
            grid.innerHTML = '<div class="col-span-full text-center py-12"><p class="text-gray-400">No hay productos</p></div>';
            return;
        }

        filtered.forEach(producto => {
            const template = document.getElementById('productTemplate');
            const clone = template.content.cloneNode(true);

            const card = clone.querySelector('.producto-card');
            card.dataset.id = producto.id;

            clone.querySelector('img').src = producto.imagen ? `/storage/${producto.imagen}` : 'https://via.placeholder.com/300x200?text=Producto';
            clone.querySelector('img').alt = producto.nombre;
            clone.querySelector('.nombre').textContent = producto.nombre;
            clone.querySelector('.descripcion').textContent = producto.descripcion || '';
            clone.querySelector('.precio').textContent = `$${parseFloat(producto.precio).toFixed(2)}`;

            grid.appendChild(clone);
        });
    }

    // RENDERIZAR CATEGORÍAS
    function renderCategorias() {
        const container = document.getElementById('categoriesContainer');
        const template = document.getElementById('categoryTemplate');

        fetch('/cliente/api/categorias')
            .then(res => res.json())
            .then(categorias => {
                categorias.forEach(cat => {
                    const clone = template.content.cloneNode(true);
                    const btn = clone.querySelector('.categoria-tab');
                    btn.dataset.category = cat.id;
                    btn.textContent = cat.nombre;
                    container.appendChild(clone);
                });
            })
            .catch(error => console.error('Error cargando categorías:', error));
    }

    // FILTRAR POR CATEGORÍA
    function filterByCategory(categoryId) {
        document.querySelectorAll('.categoria-tab').forEach(btn => btn.classList.remove('active'));
        document.querySelector(`[data-category="${categoryId}"]`).classList.add('active');
        renderProductos(categoryId);
    }

    // MOSTRAR DETALLE DEL PRODUCTO
    function showProductDetail(productId) {
        const producto = productos.find(p => p.id == productId);
        if (!producto) return;

        currentProductId = productId;
        document.getElementById('modalImage').src = producto.imagen ? `/storage/${producto.imagen}` : 'https://via.placeholder.com/400x300?text=Producto';
        document.getElementById('modalNombre').textContent = producto.nombre;
        document.getElementById('modalDescripcion').textContent = producto.descripcion || '';
        document.getElementById('modalPrice').textContent = `$${parseFloat(producto.precio).toFixed(2)}`;
        document.getElementById('modalQuantity').value = 1;
        document.getElementById('productModal').classList.remove('hidden');
    }

    // CERRAR MODAL
    function closeProductModal() {
        document.getElementById('productModal').classList.add('hidden');
    }

    // CANTIDAD EN MODAL
    function increaseQuantity() {
        const input = document.getElementById('modalQuantity');
        input.value = parseInt(input.value) + 1;
    }

    function decreaseQuantity() {
        const input = document.getElementById('modalQuantity');
        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
        }
    }

    // AGREGAR DESDE MODAL
    function addToCartFromModal() {
        const producto = productos.find(p => p.id == currentProductId);
        const cantidad = parseInt(document.getElementById('modalQuantity').value);
        agregarAlCarrito(producto, cantidad);
        closeProductModal();
    }

    // AGREGAR DESDE TARJETA
    function addToCart(card) {
        const productId = card.dataset.id;
        const producto = productos.find(p => p.id == productId);
        agregarAlCarrito(producto, 1);
    }

    // AGREGAR AL CARRITO
    function agregarAlCarrito(producto, cantidad) {
        const existe = carrito.find(item => item.id === producto.id);
        if (existe) {
            existe.cantidad += cantidad;
        } else {
            carrito.push({
                id: producto.id,
                nombre: producto.nombre,
                precio: producto.precio,
                imagen: producto.imagen,
                cantidad: cantidad
            });
        }

        localStorage.setItem('carrito', JSON.stringify(carrito));
        updateCartCount();

        Swal.fire({
            icon: 'success',
            title: '¡Agregado!',
            text: `${producto.nombre} al carrito`,
            timer: 1500,
            showConfirmButton: false
        });

        window.dispatchEvent(new Event('cartUpdated'));
    }

    // ACTUALIZAR CONTADOR
    function updateCartCount() {
        const total = carrito.reduce((sum, item) => sum + item.cantidad, 0);
        document.getElementById('cartCount').textContent = total;
    }

    // BUSCAR
    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        const term = e.target.value.toLowerCase();
        if (!term) {
            renderProductos('todas');
            return;
        }

        const filtered = productos.filter(p =>
            p.nombre.toLowerCase().includes(term) ||
            (p.descripcion && p.descripcion.toLowerCase().includes(term))
        );

        const grid = document.getElementById('productsGrid');
        grid.innerHTML = '';

        filtered.forEach(producto => {
            const template = document.getElementById('productTemplate');
            const clone = template.content.cloneNode(true);
            const card = clone.querySelector('.producto-card');
            card.dataset.id = producto.id;
            clone.querySelector('img').src = producto.imagen ? `/storage/${producto.imagen}` : 'https://via.placeholder.com/300x200?text=Producto';
            clone.querySelector('img').alt = producto.nombre;
            clone.querySelector('.nombre').textContent = producto.nombre;
            clone.querySelector('.descripcion').textContent = producto.descripcion || '';
            clone.querySelector('.precio').textContent = `$${parseFloat(producto.precio).toFixed(2)}`;
            grid.appendChild(clone);
        });
    });

    // TOGGLE CARRITO SIDEBAR
    function toggleCartSidebar() {
        const sidebar = document.getElementById('cartSidebar');
        if (sidebar) {
            sidebar.style.display = sidebar.style.display === 'none' ? 'flex' : 'none';
        }
    }

    // EVENTOS
    document.addEventListener('DOMContentLoaded', loadProductos);
    document.addEventListener('keydown', e => e.key === 'Escape' && closeProductModal());
    document.getElementById('productModal').addEventListener('click', e => e.target.id === 'productModal' && closeProductModal());

</script>
@endpush
