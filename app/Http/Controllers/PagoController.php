<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagoController extends Controller
{
    /**
     * Actualizar estado del pago (Admin)
     */
    public function actualizarEstado(Request $request, Pago $pago)
    {
        $validated = $request->validate([
            'estado_pago' => 'required|in:pendiente,completado,rechazado',
        ]);

        try {
            DB::beginTransaction();

            $pago->update([
                'estado_pago' => $validated['estado_pago']
            ]);

            // Si el pago se completa, actualizar el pedido a listo si está en preparación
            if ($validated['estado_pago'] === 'completado') {
                $pedido = $pago->pedido;
                if ($pedido->estado === 'en_preparacion') {
                    $pedido->update(['estado' => 'listo']);
                }
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Estado del pago actualizado',
                    'pago' => $pago->fresh(),
                ]);
            }

            return redirect()->back()->with('success', 'Estado del pago actualizado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->back()->with('error', 'Error al actualizar el pago');
        }
    }

    /**
     * Ver detalles de un pago (Admin)
     */
    public function show(Pago $pago)
    {
        $pago->load('pedido.user', 'pedido.detalles.producto');

        return view('admin.pagos.show', compact('pago'));
    }

    /**
     * Listar todos los pagos (Admin)
     */
    public function index(Request $request)
    {
        $query = Pago::with('pedido.user');

        // Filtro por estado de pago
        if ($request->filled('estado_pago') && $request->estado_pago != '') {
            $query->where('estado_pago', $request->estado_pago);
        }

        // Filtro por método de pago
        if ($request->filled('metodo_pago') && $request->metodo_pago != '') {
            $query->where('metodo_pago', $request->metodo_pago);
        }

        // Filtro por fecha
        if ($request->filled('fecha')) {
            $query->whereDate('created_at', $request->fecha);
        }

        $pagos = $query->latest()->paginate(15);
        $pagos->appends($request->all());

        // Estadísticas
        $totalPagos = $pagos->total();
        $pagosCompletados = Pago::where('estado_pago', 'completado')->count();
        $pagosPendientes = Pago::where('estado_pago', 'pendiente')->count();
        $totalRecaudado = Pago::where('estado_pago', 'completado')->sum('monto');

        return view('admin.pagos.index', compact(
            'pagos',
            'totalPagos',
            'pagosCompletados',
            'pagosPendientes',
            'totalRecaudado'
        ));
    }

    /**
     * API: Obtener pagos pendientes (Admin)
     */
    public function getPagosPendientes()
    {
        $pagos = Pago::where('estado_pago', 'pendiente')
                    ->with('pedido.user')
                    ->latest()
                    ->get();

        return response()->json($pagos);
    }
}

