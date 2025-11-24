<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class EmpleadoAdminController extends Controller
{
    /**
     * Display a listing of empleados.
     */
    public function index()
    {
        $empleados = User::whereIn('role', ['empleado', 'administrador'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.empleados.index', compact('empleados'));
    }

    /**
     * Show the form for creating a new empleado.
     */
    public function create()
    {
        return view('admin.empleados.create');
    }

    /**
     * Store a newly created empleado in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:empleado,administrador'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'direccion' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'telefono' => $validated['telefono'] ?? null,
            'direccion' => $validated['direccion'] ?? null,
        ]);

        return redirect()->route('admin.empleados.index')
            ->with('success', 'Empleado creado exitosamente.');
    }

    /**
     * Show the form for editing the specified empleado.
     */
    public function edit(User $empleado)
    {
        // Verificar que es empleado o admin
        if (!in_array($empleado->role, ['empleado', 'administrador'])) {
            abort(404);
        }

        return view('admin.empleados.edit', compact('empleado'));
    }

    /**
     * Update the specified empleado in storage.
     */
    public function update(Request $request, User $empleado)
    {
        // Verificar que es empleado o admin
        if (!in_array($empleado->role, ['empleado', 'administrador'])) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $empleado->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:empleado,administrador'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'direccion' => ['nullable', 'string', 'max:255'],
        ]);

        $empleado->name = $validated['name'];
        $empleado->email = $validated['email'];
        $empleado->role = $validated['role'];
        $empleado->telefono = $validated['telefono'] ?? null;
        $empleado->direccion = $validated['direccion'] ?? null;

        if ($request->filled('password')) {
            $empleado->password = Hash::make($validated['password']);
        }

        $empleado->save();

        return redirect()->route('admin.empleados.index')
            ->with('success', 'Empleado actualizado exitosamente.');
    }

    /**
     * Remove the specified empleado from storage.
     */
    public function destroy(User $empleado)
    {
        // Verificar que es empleado o admin
        if (!in_array($empleado->role, ['empleado', 'administrador'])) {
            abort(404);
        }

        // No permitir eliminar al usuario actual
        if ($empleado->id === auth()->id()) {
            return redirect()->route('admin.empleados.index')
                ->with('error', 'No puedes eliminar tu propia cuenta desde aquí.');
        }

        $empleado->delete();

        return redirect()->route('admin.empleados.index')
            ->with('success', 'Empleado eliminado exitosamente.');
    }
}

