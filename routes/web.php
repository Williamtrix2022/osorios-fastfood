<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmpleadoAdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Ruta principal redirige al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard genérico (Breeze) - redirige según rol
Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->role === 'administrador') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'empleado') {
        return redirect()->route('empleado.dashboard');
    } else {
        return redirect()->route('cliente.menu');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas de perfil (para todos los usuarios autenticados)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ============================================
// RUTAS PARA CLIENTES
// ============================================
Route::middleware(['auth', 'cliente'])->prefix('cliente')->name('cliente.')->group(function () {
    Route::get('/menu', [ClienteController::class, 'menu'])->name('menu');
    Route::get('/carrito', [ClienteController::class, 'carrito'])->name('carrito');
    Route::get('/mis-pedidos', [ClienteController::class, 'misPedidos'])->name('pedidos');
    Route::get('/pedido/{id}', [ClienteController::class, 'verPedido'])->name('pedido.detalle');

    // APIs para cliente
    Route::get('/api/categorias', [ClienteController::class, 'getCategorias']);
    Route::get('/api/productos', [ClienteController::class, 'getProductos']);
    Route::get('/api/producto/{id}', [ClienteController::class, 'getProductoDetalle']);
    Route::get('/api/mis-pedidos', [PedidoController::class, 'getMisPedidos']);
    Route::get('/api/pedido/{pedido}', [PedidoController::class, 'getPedidoDetalle']);

    // Crear pedido
    Route::post('/crear-pedido', [PedidoController::class, 'store']);
});

// ============================================
// RUTAS PARA EMPLEADOS
// ============================================
Route::middleware(['auth', 'empleado'])->prefix('empleado')->name('empleado.')->group(function () {
    Route::get('/dashboard', [EmpleadoController::class, 'dashboard'])->name('dashboard');
    Route::get('/pedidos', [EmpleadoController::class, 'pedidos'])->name('pedidos');
    Route::get('/pedidos-completados', [EmpleadoController::class, 'pedidosCompletados'])->name('pedidos.completados');
    Route::get('/pedido/{id}', [EmpleadoController::class, 'verPedido'])->name('pedido.detalle');
    Route::patch('/pedido/{pedido}/estado', [EmpleadoController::class, 'cambiarEstado'])->name('pedido.estado');

    // APIs para empleado
    Route::get('/api/pendientes', [EmpleadoController::class, 'getPedidosPendientes']);
    Route::get('/api/en-preparacion', [EmpleadoController::class, 'getPedidosEnPreparacion']);
    Route::get('/api/pedidos', [EmpleadoController::class, 'getPedidosActivos']);
    Route::get('/api/pedidos-completados', [EmpleadoController::class, 'getPedidosCompletadosAPI']);
});

// ============================================
// RUTAS PARA ADMINISTRADORES
// ============================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/reportes', [AdminController::class, 'reportes'])->name('reportes');

    // CRUD de productos
    Route::resource('productos', ProductoController::class);

    // CRUD de empleados (solo para admin)
    Route::resource('empleados', EmpleadoAdminController::class)->except(['show']);

    // Gestión de pedidos
    Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
    Route::get('/pedido/{pedido}', [PedidoController::class, 'show'])->name('pedidos.show');
    Route::patch('/pedido/{pedido}', [PedidoController::class, 'update'])->name('pedidos.update');
    Route::delete('/pedido/{pedido}', [PedidoController::class, 'destroy'])->name('pedidos.destroy');

    // Gestión de pagos
    Route::get('/pagos', [PagoController::class, 'index'])->name('pagos.index');
    Route::get('/pago/{pago}', [PagoController::class, 'show'])->name('pagos.show');
    Route::patch('/pago/{pago}/estado', [PagoController::class, 'actualizarEstado'])->name('pagos.actualizar');

    // APIs para admin
    Route::get('/api/estadisticas', [AdminController::class, 'getEstadisticas']);
    Route::get('/api/productos', [ProductoController::class, 'getProductos']);
    Route::get('/api/productos/categoria/{categoriaId}', [ProductoController::class, 'getProductosPorCategoria']);
    Route::get('/api/pagos/pendientes', [PagoController::class, 'getPagosPendientes']);
});

require __DIR__.'/auth.php';
