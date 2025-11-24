<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    /**
     * Mostrar lista de productos (Admin)
     */
    public function index()
    {
        // Obtener todos los productos con su categoría
        $productos = Producto::with('categoria')->paginate(10);

        return view('admin.productos.index', compact('productos'));
    }

    /**
     * Mostrar formulario para crear producto
     */
    public function create()
    {
        // Obtener todas las categorías para el formulario
        $categorias = Categoria::all();

        return view('admin.productos.create', compact('categorias'));
    }

    /**
     * Guardar nuevo producto en base de datos
     */
    public function store(Request $request)
    {
        // Validar datos
        $validated = $request->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0.01',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'disponible' => 'boolean',
        ]);

        // Manejar carga de imagen
        if ($request->hasFile('imagen')) {
            $validated['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        // Crear el producto
        Producto::create($validated);

        return redirect()->route('admin.productos.index')
                        ->with('success', 'Producto creado exitosamente');
    }

    /**
     * Mostrar detalles de un producto específico
     */
    public function show(Producto $producto)
    {
        return view('admin.productos.show', compact('producto'));
    }

    /**
     * Mostrar formulario para editar producto
     */
    public function edit(Producto $producto)
    {
        $categorias = Categoria::all();

        return view('admin.productos.edit', compact('producto', 'categorias'));
    }

    /**
     * Actualizar producto en base de datos
     */
    public function update(Request $request, Producto $producto)
    {
        // Validar datos
        $validated = $request->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0.01',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'disponible' => 'boolean',
        ]);

        // Manejar carga de imagen
        if ($request->hasFile('imagen')) {
            // Eliminar imagen antigua si existe
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $validated['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        // Actualizar el producto
        $producto->update($validated);

        return redirect()->route('admin.productos.index')
                        ->with('success', 'Producto actualizado exitosamente');
    }

    /**
     * Eliminar producto
     */
    public function destroy(Producto $producto)
    {
        // Eliminar imagen si existe
        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }

        $producto->delete();

        return redirect()->route('admin.productos.index')
                        ->with('success', 'Producto eliminado exitosamente');
    }

    /**
     * API: Obtener todos los productos (para el menú del cliente)
     */
    public function getProductos()
    {
        $productos = Producto::with('categoria')
                            ->where('disponible', true)
                            ->get();

        return response()->json($productos);
    }

    /**
     * API: Obtener productos por categoría
     */
    public function getProductosPorCategoria($categoriaId)
    {
        $productos = Producto::where('categoria_id', $categoriaId)
                            ->where('disponible', true)
                            ->get();

        return response()->json($productos);
    }
}
