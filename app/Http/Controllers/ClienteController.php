<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    /**
     * Mostrar menú digital del cliente
     */
    public function menu()
    {
        // Obtener todas las categorías con sus productos
        $categorias = Categoria::with('productos')->get();
        $productos = Producto::where('disponible', true)->get();
        
        return view('cliente.menu', compact('categorias', 'productos'));
    }

    /**
     * Mostrar carrito de compras
     */
    public function carrito()
    {
        return view('cliente.carrito');
    }

    /**
     * Mostrar historial de pedidos del cliente
     */
    public function misPedidos()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $pedidos = $user->pedidos()
                            ->with('detalles.producto', 'pago')
                            ->latest()
                            ->paginate(10);
        
        // Asumiendo que esta es una vista para el cliente, no para el admin
        return view('cliente.pedidos', compact('pedidos'));

    }

    /**
     * Mostrar detalles de un pedido específico
     */
    public function verPedido($id)
    {
          /** @var \App\Models\User $user */
          $user = Auth::user();
        $pedido = $user ->pedidos()
                            ->with('detalles.producto', 'pago')
                            ->findOrFail($id);
        
        return view('cliente.pedido-detalle', compact('pedido'));
    }

    /**
     * API: Obtener categorías para el menú
     */
    public function getCategorias()
    {
        $categorias = Categoria::all();
        return response()->json($categorias);
    }

    /**
     * API: Obtener productos disponibles
     */
    public function getProductos()
    {
        $productos = Producto::where('disponible', true)
                            ->with('categoria')
                            ->get();
        
        return response()->json($productos);
    }

    /**
     * API: Obtener detalles de un producto
     */
    public function getProductoDetalle($id)
    {
        $producto = Producto::findOrFail($id);
        return response()->json($producto);
    }
}