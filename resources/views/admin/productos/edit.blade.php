@extends('layouts.app-admin')

@section('title', 'Editar Producto - OsoriosFoodApp')
@section('page-title', 'Editar Producto')
@section('page-subtitle', 'Modifica la información del producto')

@section('content')

<!-- Breadcrumb -->
<div class="mb-8">
    <a href="{{ route('admin.productos.index') }}" class="text-gold hover:text-accent transition">
        <i class="fas fa-arrow-left mr-2"></i>Volver a Productos
    </a>
</div>

<!-- Formulario -->
<div class="max-w-3xl">
    <div class="bg-gray-800 rounded-xl p-8 border-2 border-gray-700">
        
        <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-3">
            <i class="fas fa-edit text-gold"></i>
            Editar: {{ $producto->nombre }}
        </h2>

        <form action="{{ route('admin.productos.update', $producto->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Nombre -->
            <div class="mb-6">
                <label for="nombre" class="block text-white font-semibold mb-2">
                    <i class="fas fa-hamburger mr-2 text-gold"></i>Nombre del Producto *
                </label>
                <input type="text" 
                       id="nombre" 
                       name="nombre" 
                       value="{{ old('nombre', $producto->nombre) }}"
                       required
                       class="w-full px-4 py-3 rounded-lg bg-gray-700 text-white border-2 border-gray-600 focus:border-gold focus:outline-none transition"
                       placeholder="Ej: Hamburguesa Clásica">
                @error('nombre')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Categoría -->
            <div class="mb-6">
                <label for="categoria_id" class="block text-white font-semibold mb-2">
                    <i class="fas fa-tags mr-2 text-gold"></i>Categoría *
                </label>
                <select id="categoria_id" 
                        name="categoria_id" 
                        required
                        class="w-full px-4 py-3 rounded-lg bg-gray-700 text-white border-2 border-gray-600 focus:border-gold focus:outline-none transition">
                    <option value="">-- Selecciona una categoría --</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" 
                                {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('categoria_id')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Descripción -->
            <div class="mb-6">
                <label for="descripcion" class="block text-white font-semibold mb-2">
                    <i class="fas fa-align-left mr-2 text-gold"></i>Descripción
                </label>
                <textarea id="descripcion" 
                          name="descripcion" 
                          rows="3"
                          class="w-full px-4 py-3 rounded-lg bg-gray-700 text-white border-2 border-gray-600 focus:border-gold focus:outline-none transition resize-none"
                          placeholder="Describe el producto...">{{ old('descripcion', $producto->descripcion) }}</textarea>
                @error('descripcion')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Precio -->
            <div class="mb-6">
                <label for="precio" class="block text-white font-semibold mb-2">
                    <i class="fas fa-dollar-sign mr-2 text-gold"></i>Precio *
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gold font-bold text-lg">$</span>
                    <input type="number" 
                           id="precio" 
                           name="precio" 
                           value="{{ old('precio', $producto->precio) }}"
                           step="0.01" 
                           min="0.01"
                           required
                           class="w-full pl-10 pr-4 py-3 rounded-lg bg-gray-700 text-white border-2 border-gray-600 focus:border-gold focus:outline-none transition"
                           placeholder="0.00">
                </div>
                @error('precio')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Imagen Actual -->
            @if($producto->imagen)
            <div class="mb-6">
                <label class="block text-white font-semibold mb-2">
                    <i class="fas fa-image mr-2 text-gold"></i>Imagen Actual
                </label>
                <div class="flex items-center gap-4">
                    <img src="/{{ $producto->imagen }}" 
                         alt="{{ $producto->nombre }}" 
                         class="w-32 h-32 rounded-lg object-cover border-2 border-gray-600">
                    <div>
                        <p class="text-gray-400 text-sm">Si subes una nueva imagen, reemplazará esta</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Nueva Imagen -->
            <div class="mb-6">
                <label for="imagen" class="block text-white font-semibold mb-2">
                    <i class="fas fa-upload mr-2 text-gold"></i>{{ $producto->imagen ? 'Cambiar Imagen' : 'Subir Imagen' }}
                </label>
                <div class="flex items-center gap-4">
                    <label for="imagen" class="cursor-pointer">
                        <div class="w-32 h-32 bg-gray-700 border-2 border-dashed border-gray-600 hover:border-gold rounded-lg flex flex-col items-center justify-center transition">
                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-500 mb-2"></i>
                            <span class="text-gray-400 text-sm">Subir imagen</span>
                        </div>
                    </label>
                    <div>
                        <p class="text-gray-400 text-sm mb-2">Formatos: JPG, PNG, GIF</p>
                        <p class="text-gray-400 text-sm">Tamaño máximo: 2MB</p>
                        <p id="fileName" class="text-gold text-sm mt-2"></p>
                    </div>
                </div>
                <input type="file" 
                       id="imagen" 
                       name="imagen" 
                       accept="image/*"
                       class="hidden"
                       onchange="showFileName(this)">
                @error('imagen')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Disponible -->
            <div class="mb-8">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" 
                           id="disponible" 
                           name="disponible" 
                           value="1"
                           {{ old('disponible', $producto->disponible) ? 'checked' : '' }}
                           class="w-6 h-6 rounded border-2 border-gray-600 bg-gray-700 text-gold focus:ring-gold focus:ring-2">
                    <span class="text-white font-semibold">
                        <i class="fas fa-check-circle mr-2 text-gold"></i>Producto disponible
                    </span>
                </label>
                <p class="text-gray-400 text-sm ml-9 mt-2">Si está desmarcado, no aparecerá en el menú del cliente</p>
            </div>

            <!-- Botones -->
            <div class="flex gap-4">
                <button type="submit" 
                        class="flex-1 py-3 rounded-lg font-bold text-white transition hover:opacity-90"
                        style="background-color: var(--color-gold); color: var(--color-dark);">
                    <i class="fas fa-save mr-2"></i>Actualizar Producto
                </button>
                <a href="{{ route('admin.productos.index') }}" 
                   class="flex-1 py-3 rounded-lg border-2 border-gray-600 text-white font-bold hover:bg-gray-700 transition text-center">
                    <i class="fas fa-times mr-2"></i>Cancelar
                </a>
            </div>

        </form>

    </div>
</div>

@endsection

@push('scripts')
<script>
    function showFileName(input) {
        const fileName = input.files[0]?.name;
        if (fileName) {
            document.getElementById('fileName').textContent = `📁 ${fileName}`;
        }
    }
</script>
@endpush

@push('css')
<style>
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