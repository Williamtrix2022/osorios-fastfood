@extends('layouts.app-admin')

@section('title', 'Productos - OsoriosFoodApp')
@section('page-title', 'Gestión de Productos')
@section('page-subtitle', 'Administra el menú de tu restaurante')

@section('content')

<!-- ===== HEADER CON BOTÓN CREAR ===== -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-3xl font-bold text-white">
            <i class="fas fa-hamburger" style="color: var(--color-gold);"></i>
            Todos los Productos
        </h2>
        <p class="text-gray-400 mt-2">{{ $productos->total() }} productos registrados</p>
    </div>
    <a href="{{ route('admin.productos.create') }}" 
       class="px-6 py-3 rounded-lg font-bold text-white transition hover:opacity-90 flex items-center gap-2"
       style="background-color: var(--color-gold); color: var(--color-dark);">
        <i class="fas fa-plus"></i>
        Crear Producto
    </a>
</div>

<!-- ===== MENSAJES DE ÉXITO ===== -->
@if(session('success'))
<div class="bg-green-900 border border-green-700 text-white px-6 py-4 rounded-lg mb-6 flex items-center gap-3">
    <i class="fas fa-check-circle text-2xl"></i>
    <span>{{ session('success') }}</span>
</div>
@endif

<!-- ===== TABLA DE PRODUCTOS ===== -->
<div class="bg-gray-800 rounded-xl border-2 border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-900">
                <tr>
                    <th class="text-left text-gold font-bold p-4">Imagen</th>
                    <th class="text-left text-gold font-bold p-4">Nombre</th>
                    <th class="text-left text-gold font-bold p-4">Categoría</th>
                    <th class="text-left text-gold font-bold p-4">Precio</th>
                    <th class="text-left text-gold font-bold p-4">Disponible</th>
                    <th class="text-left text-gold font-bold p-4">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos as $producto)
                <tr class="border-b border-gray-700 hover:bg-gray-700 transition">
                    <!-- Imagen -->
                    <td class="p-4">
                        <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-700 flex items-center justify-center">
                            @if($producto->imagen)
                                <img src="/{{ $producto->imagen }}" alt="{{ $producto->nombre }}" class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-image text-3xl text-gray-500"></i>
                            @endif
                        </div>
                    </td>

                    <!-- Nombre -->
                    <td class="p-4">
                        <p class="text-white font-semibold text-lg">{{ $producto->nombre }}</p>
                        <p class="text-gray-400 text-sm line-clamp-1">{{ $producto->descripcion }}</p>
                    </td>

                    <!-- Categoría -->
                    <td class="p-4">
                        <span class="px-3 py-1 bg-blue-900 text-blue-300 rounded-full text-sm font-semibold">
                            {{ $producto->categoria->nombre }}
                        </span>
                    </td>

                    <!-- Precio -->
                    <td class="p-4">
                        <span class="text-gold font-bold text-xl">${{ number_format($producto->precio, 2) }}</span>
                    </td>

                    <!-- Disponibilidad -->
                    <td class="p-4">
                        @if($producto->disponible)
                            <span class="px-3 py-1 bg-green-900 text-green-300 rounded-full text-sm font-semibold">
                                <i class="fas fa-check-circle mr-1"></i>Disponible
                            </span>
                        @else
                            <span class="px-3 py-1 bg-red-900 text-red-300 rounded-full text-sm font-semibold">
                                <i class="fas fa-times-circle mr-1"></i>No disponible
                            </span>
                        @endif
                    </td>

                    <!-- Acciones -->
                    <td class="p-4">
                        <div class="flex gap-2">
                            <!-- Editar -->
                            <a href="{{ route('admin.productos.edit', $producto->id) }}" 
                               class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded transition text-sm"
                               title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>

                            <!-- Eliminar -->
                            <form action="{{ route('admin.productos.destroy', $producto->id) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('¿Estás seguro de eliminar este producto?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded transition text-sm"
                                        title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-12">
                        <i class="fas fa-inbox text-5xl text-gray-500 mb-3 block"></i>
                        <p class="text-gray-400 text-lg">No hay productos registrados</p>
                        <a href="{{ route('admin.productos.create') }}" 
                           class="inline-block mt-4 px-6 py-2 rounded-lg text-white font-semibold"
                           style="background-color: var(--color-gold); color: var(--color-dark);">
                            Crear primer producto
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    @if($productos->hasPages())
    <div class="p-4 bg-gray-900">
        {{ $productos->links() }}
    </div>
    @endif
</div>

@endsection

@push('css')
<style>
 .line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-clamp: 1; /* Propiedad estándar futura */
}

    table {
        border-collapse: separate;
        border-spacing: 0;
    }

    /* Personalizar paginación */
    .pagination {
        display: flex;
        gap: 5px;
    }

    .pagination a,
    .pagination span {
        padding: 8px 12px;
        background-color: #374151;
        color: white;
        border-radius: 6px;
        text-decoration: none;
        transition: all 0.3s;
    }

    .pagination a:hover {
        background-color: var(--color-gold);
        color: var(--color-dark);
    }

    .pagination .active span {
        background-color: var(--color-gold);
        color: var(--color-dark);
    }
</style>
@endpush