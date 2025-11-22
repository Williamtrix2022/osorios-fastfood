@extends('layouts.app-cliente')

@section('title', 'Menú Digital - OsoriosFoodApp')

@section('content')
<div class="menu-container max-w-7xl mx-auto w-full">
    
    <!-- HEADER -->
    <div class="header-menu mb-10 p-8 rounded-2xl border-l-4" style="border-left-color: var(--color-gold);">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            
            <div>
                <h1 class="text-5xl font-bold text-white mb-2">
                    <i class="fas fa-utensils" style="color: var(--color-gold);"></i>
                    Osorios FoodApp
                </h1>
                <p class="text-gray-400 text-lg">Deliciosas comidas rápidas al alcance de tus manos</p>
            </div>
            
            <div class="flex gap-4 items-center justify-end flex-wrap">
                <input 
                    type="text" 
                    id="searchInput" 
                    placeholder="Buscar producto..." 
                    class="px-4 py-2 rounded-lg bg-gray-700 text-white border-2 border-gray-600 focus:border-gold focus:outline-none w-full md:w-auto"
                >
                
                <button onclick="toggleCartSidebar()" class="relative hover:scale-110 transition">
                    <i class="fas fa-shopping-cart text-3xl text-gold"></i>
                    <span id="cartCount" class="absolute -top-3 -right-3 bg-red-500 text-white rounded-full w-7 h-7 flex items-center justify-center text-sm font-bold">0</span>
                </button>
                
                <a href="{{ route('profile.edit') }}" class="text-gold hover:text-accent text-2xl transition">
                    <i class="fas fa-user-circle"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- CATEGORÍAS -->
    <div class="mb-10 pb-4 overflow-x-auto">
        <div class="flex gap-3 min-w-max" id="categoriesContainer">
            <button 
                onclick="filterByCategory('todas')" 
                class="categoria-tab px-6 py-2 rounded-full font-semibold whitespace-nowrap active"
                data-category="todas"
                style="background-color: var(--color-gold); color: var(--color-dark);"
            >
                <i class="fas fa-list mr-2"></i>Todas
            </button>
        </div>
    </div>

    <!-- GRID DE PRODUCTOS -->
    <div id="productsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-10">
        <div class="col-span-full text-center py-12">
            <div class="inline-block animate-spin">
                <i class="fas fa-spinner text-4xl text-gold"></i>
            </div>
            <p class="text-gray-400 mt-4">Cargando productos...</p>
        </div>
    </div>

    <!-- TEMPLATE PRODUCTO -->
    <template id="productTemplate">
        <div class="producto-card bg-gray-800 rounded-xl overflow-hidden border-2 border-gray-700 hover:border-gold cursor-pointer transition transform hover:scale-105"
             onclick="showProductDetail(this.dataset.id)">
            <div class="relative overflow-hidden h-48 bg-gray-700">
                <img src="" alt="" class="w-full h-full object-cover hover:scale-110 transition duration-300">
                <div class="absolute top-3 right-3 bg-red-500 px-3 py-1 rounded-full text-white font-bold">
                    <span class="precio">$0.00</span>
                </div>
            </div>
            <div class="p-4">
                <h3 class="text-lg font-bold text-white mb-2 nombre"></h3>
                <p class="text-gray-400 text-sm descripcion line-clamp-2 mb-4"></p>
                <button 
                    onclick="event.stopPropagation(); addToCart(this.closest('.producto-card'))" 
                    class="w-full py-2 rounded-lg text-white font-semibold transition hover:opacity-90"
                    style="background-color: var(--color-gold); color: var(--color-dark);"
                >
                    <i class="fas fa-shopping-cart mr-2"></i>Agregar
                </button>
            </div>
        </div>
    </template>

    <!-- TEMPLATE CATEGORÍA -->
    <template id="categoryTemplate">
        <button 
            class="categoria-tab px-6 py-2 rounded-full font-semibold whitespace-nowrap bg-gray-700 hover:bg-gray-600 transition"
            data-category=""
            onclick="filterByCategory(this.dataset.category)"
        >
        </button>
    </template>

</div>

<!-- MODAL DE PRODUCTO -->
<div id="productModal" class="hidden fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50 p-4">
    <div class="bg-gray-800 rounded-2xl max-w-2xl w-full overflow-hidden border-2 border-gold">
        
        <div class="relative h-64 overflow-hidden">
            <button onclick="closeProductModal()" class="absolute top-4 left-4 text-white text-2xl z-10 hover:text-gold bg-black bg-opacity-50 w-10 h-10 rounded-full flex items-center justify-center">
                <i class="fas fa-arrow-left"></i>
            </button>
            <img id="modalImage" src="" alt="" class="w-full h-full object-cover">
            <div class="absolute top-4 right-4 bg-red-500 px-4 py-2 rounded-lg text-white font-bold">
                <span id="modalPrice">$0.00</span>
            </div>
        </div>
        
        <div class="p-6">
            <h2 id="modalNombre" class="text-3xl font-bold text-white mb-3"></h2>
            <p id="modalDescripcion" class="text-gray-400 mb-6"></p>
            
            <div class="mb-6 pb-6 border-b border-gray-700">
                <label class="text-white font-semibold block mb-3">Cantidad:</label>
                <div class="flex items-center border border-gray-600 rounded-lg w-fit">
                    <button onclick="decreaseQuantity()" class="px-4 py-2 text-white hover:bg-gray-700">−</button>
                    <input type="number" id="modalQuantity" value="1" min="1" class="w-16 text-center bg-gray-800 text-white border-0 focus:outline-none" readonly>
                    <button onclick="increaseQuantity()" class="px-4 py-2 text-white hover:bg-gray-700">+</button>
                </div>
            </div>

            <div class="flex gap-3">
                <button onclick="closeProductModal()" class="flex-1 py-3 rounded-lg border-2 border-gray-600 text-white font-semibold hover:bg-gray-700 transition">
                    Cerrar
                </button>
                <button onclick="addToCartFromModal()" class="flex-1 py-3 rounded-lg text-white font-semibold"
                    style="background-color: var(--color-gold); color: var(--color-dark);">
                    <i class="fas fa-shopping-cart mr-2"></i>Agregar
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('css')
<style>
    .header-menu {
        background: linear-gradient(135deg, #2a2a2a 0%, #1a1a1a 100%);
    }

    .categoria-tab {
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .categoria-tab.active {
        background-color: var(--color-gold) !important;
        color: var(--color-dark) !important;
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