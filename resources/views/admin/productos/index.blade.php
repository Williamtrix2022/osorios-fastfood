@extends('layouts.app-admin')

@section('title', 'Productos - OsoriosFoodApp')
@section('page-title', 'Gestión de Productos')
@section('page-subtitle', 'Administra el menú de tu restaurante')

@section('content')

<!-- ===== HEADER CON ESTADÍSTICAS Y BOTÓN CREAR ===== -->
<div class="products-header mb-8">
    <div class="header-stats">
        <div class="stat-card">
            <div class="stat-icon bg-blue-500">
                <i class="fas fa-hamburger"></i>
            </div>
            <div class="stat-info">
                <p class="stat-label">Total Productos</p>
                <p class="stat-value">{{ $productos->total() }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-green-500">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <p class="stat-label">Disponibles</p>
                <p class="stat-value">{{ $productos->where('disponible', true)->count() }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-red-500">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-info">
                <p class="stat-label">No Disponibles</p>
                <p class="stat-value">{{ $productos->where('disponible', false)->count() }}</p>
            </div>
        </div>
    </div>
    <a href="{{ route('admin.productos.create') }}" class="btn-create">
        <i class="fas fa-plus mr-2"></i>
        <span>Crear Producto</span>
    </a>
</div>

<!-- ===== MENSAJES DE ÉXITO ===== -->
@if(session('success'))
<div class="alert-success mb-6">
    <i class="fas fa-check-circle text-2xl"></i>
    <span>{{ session('success') }}</span>
</div>
@endif

<!-- ===== GRID DE PRODUCTOS (TARJETAS) ===== -->
<div class="products-grid">
    @forelse($productos as $producto)
    <div class="product-card-admin">
        <!-- Imagen del producto -->
        <div class="product-image-admin">
            @if($producto->imagen)
                <img src="/storage/{{ $producto->imagen }}" alt="{{ $producto->nombre }}">
            @else
                <div class="image-placeholder">
                    <i class="fas fa-image"></i>
                    <p>Sin imagen</p>
                </div>
            @endif

            <!-- Badge de disponibilidad -->
            <div class="availability-badge {{ $producto->disponible ? 'available' : 'unavailable' }}">
                <i class="fas fa-{{ $producto->disponible ? 'check' : 'times' }}-circle mr-1"></i>
                {{ $producto->disponible ? 'Disponible' : 'No disponible' }}
            </div>

            <!-- Categoría badge -->
            <div class="category-badge">
                <i class="fas fa-tag mr-1"></i>
                {{ $producto->categoria->nombre }}
            </div>
        </div>

        <!-- Información del producto -->
        <div class="product-info-admin">
            <h3 class="product-name-admin">{{ $producto->nombre }}</h3>
            <p class="product-description-admin">{{ Str::limit($producto->descripcion ?: 'Sin descripción', 100) }}</p>

            <div class="product-price-admin">
                <i class="fas fa-dollar-sign mr-1"></i>
                ${{ number_format($producto->precio, 2) }}
            </div>

            <!-- Acciones -->
            <div class="product-actions-admin">
                <a href="{{ route('admin.productos.edit', $producto->id) }}" class="btn-edit" title="Editar">
                    <i class="fas fa-edit"></i>
                    <span>Editar</span>
                </a>

                <form action="{{ route('admin.productos.destroy', $producto->id) }}"
                      method="POST"
                      class="inline"
                      onsubmit="return confirm('¿Estás seguro de eliminar este producto?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete" title="Eliminar">
                        <i class="fas fa-trash-alt"></i>
                        <span>Eliminar</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="empty-state">
        <i class="fas fa-box-open"></i>
        <p class="empty-title">No hay productos registrados</p>
        <p class="empty-subtitle">Empieza creando tu primer producto</p>
        <a href="{{ route('admin.productos.create') }}" class="btn-create-empty">
            <i class="fas fa-plus mr-2"></i>
            Crear Primer Producto
        </a>
    </div>
    @endforelse
</div>

<!-- Paginación -->
@if($productos->hasPages())
<div class="pagination-container">
    {{ $productos->links() }}
</div>
@endif

@endsection

@push('css')
<style>
    /* ===== HEADER CON ESTADÍSTICAS ===== */
    .products-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .header-stats {
        display: flex;
        gap: 1rem;
        flex: 1;
        flex-wrap: wrap;
    }

    .stat-card {
        flex: 1;
        min-width: 200px;
        background: var(--color-secondary);
        padding: 1.5rem;
        border-radius: 16px;
        border: 2px solid rgba(248, 250, 252, 0.1);
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        border-color: var(--color-accent);
        box-shadow: 0 8px 24px rgba(245, 158, 11, 0.2);
        transform: translateY(-2px);
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .stat-info {
        flex: 1;
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--color-text-muted);
        margin-bottom: 0.25rem;
    }

    .stat-value {
        font-size: 1.875rem;
        font-weight: 800;
        color: var(--color-text);
    }

    .btn-create {
        padding: 1rem 2rem;
        background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-accent-light) 100%);
        color: var(--color-primary);
        font-weight: 700;
        border-radius: 12px;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(245, 158, 11, 0.3);
        white-space: nowrap;
    }

    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 24px rgba(245, 158, 11, 0.5);
    }

    /* ===== ALERTAS ===== */
    .alert-success {
        background: rgba(16, 185, 129, 0.1);
        border: 2px solid var(--color-success);
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        color: var(--color-success);
        font-weight: 600;
    }

    /* ===== GRID DE PRODUCTOS ===== */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 768px) {
        .products-grid {
            grid-template-columns: 1fr;
        }
    }

    /* ===== TARJETA DE PRODUCTO ===== */
    .product-card-admin {
        background: var(--color-secondary);
        border-radius: 16px;
        overflow: hidden;
        border: 2px solid transparent;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
    }

    .product-card-admin:hover {
        border-color: var(--color-accent);
        box-shadow: 0 8px 32px rgba(245, 158, 11, 0.3);
        transform: translateY(-4px);
    }

    /* Imagen del producto */
    .product-image-admin {
        position: relative;
        width: 100%;
        padding-bottom: 66.67%; /* Aspect ratio 3:2 */
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        overflow: hidden;
    }

    .product-image-admin img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .product-card-admin:hover .product-image-admin img {
        transform: scale(1.1);
    }

    .image-placeholder {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: var(--color-text-muted);
        gap: 0.5rem;
    }

    .image-placeholder i {
        font-size: 3rem;
        opacity: 0.5;
    }

    .image-placeholder p {
        font-size: 0.875rem;
        opacity: 0.7;
    }

    /* Badges */
    .availability-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(8px);
    }

    .availability-badge.available {
        background: rgba(16, 185, 129, 0.9);
        color: white;
    }

    .availability-badge.unavailable {
        background: rgba(239, 68, 68, 0.9);
        color: white;
    }

    .category-badge {
        position: absolute;
        top: 1rem;
        left: 1rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        background: rgba(59, 130, 246, 0.9);
        color: white;
        font-size: 0.75rem;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(8px);
    }

    /* Información del producto */
    .product-info-admin {
        padding: 1.5rem;
    }

    .product-name-admin {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--color-text);
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }

    .product-description-admin {
        color: var(--color-text-muted);
        font-size: 0.875rem;
        line-height: 1.6;
        margin-bottom: 1rem;
        min-height: 2.8em;
    }

    .product-price-admin {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--color-accent);
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
    }

    /* Acciones */
    .product-actions-admin {
        display: flex;
        gap: 0.75rem;
    }

    .btn-edit, .btn-delete {
        flex: 1;
        padding: 0.75rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none;
        border: none;
    }

    .btn-edit {
        background: rgba(59, 130, 246, 0.1);
        color: var(--color-info);
        border: 2px solid rgba(59, 130, 246, 0.3);
    }

    .btn-edit:hover {
        background: var(--color-info);
        color: white;
        border-color: var(--color-info);
        transform: translateY(-2px);
    }

    .btn-delete {
        background: rgba(239, 68, 68, 0.1);
        color: var(--color-danger);
        border: 2px solid rgba(239, 68, 68, 0.3);
    }

    .btn-delete:hover {
        background: var(--color-danger);
        color: white;
        border-color: var(--color-danger);
        transform: translateY(-2px);
    }

    /* ===== ESTADO VACÍO ===== */
    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 4rem 2rem;
        background: var(--color-secondary);
        border-radius: 16px;
        border: 2px dashed rgba(248, 250, 252, 0.2);
    }

    .empty-state i {
        font-size: 5rem;
        color: var(--color-text-muted);
        opacity: 0.5;
        margin-bottom: 1.5rem;
    }

    .empty-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--color-text);
        margin-bottom: 0.5rem;
    }

    .empty-subtitle {
        font-size: 1rem;
        color: var(--color-text-muted);
        margin-bottom: 2rem;
    }

    .btn-create-empty {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 2rem;
        background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-accent-light) 100%);
        color: var(--color-primary);
        font-weight: 700;
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(245, 158, 11, 0.3);
    }

    .btn-create-empty:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 24px rgba(245, 158, 11, 0.5);
    }

    /* ===== PAGINACIÓN ===== */
    .pagination-container {
        display: flex;
        justify-content: center;
        margin-top: 2rem;
    }
</style>
@endpush

