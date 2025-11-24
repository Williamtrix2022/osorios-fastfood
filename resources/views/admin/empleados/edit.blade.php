@extends('layouts.app-admin')

@section('title', 'Editar Empleado - Admin')
@section('page-title', 'Editar Empleado')
@section('page-subtitle', 'Actualiza la información del empleado')

@section('content')
<div class="container-form">

    <div class="form-card">
        <form action="{{ route('admin.empleados.update', $empleado) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <!-- NOMBRE -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-user mr-2"></i>
                        Nombre Completo
                    </label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $empleado->name) }}"
                        required
                        class="form-input @error('name') error @enderror"
                        placeholder="Juan Pérez"
                    >
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- EMAIL -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-envelope mr-2"></i>
                        Correo Electrónico
                    </label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $empleado->email) }}"
                        required
                        class="form-input @error('email') error @enderror"
                        placeholder="empleado@osorios.com"
                    >
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- TELÉFONO -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-phone mr-2"></i>
                        Teléfono <span class="optional">(Opcional)</span>
                    </label>
                    <input
                        type="text"
                        name="telefono"
                        value="{{ old('telefono', $empleado->telefono) }}"
                        class="form-input @error('telefono') error @enderror"
                        placeholder="3001234567"
                    >
                    @error('telefono')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- DIRECCIÓN -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        Dirección <span class="optional">(Opcional)</span>
                    </label>
                    <input
                        type="text"
                        name="direccion"
                        value="{{ old('direccion', $empleado->direccion) }}"
                        class="form-input @error('direccion') error @enderror"
                        placeholder="Calle 10 #20-30"
                    >
                    @error('direccion')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- ROL -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-user-tag mr-2"></i>
                        Rol
                    </label>
                    <select
                        name="role"
                        required
                        class="form-input @error('role') error @enderror"
                    >
                        <option value="">Selecciona un rol</option>
                        <option value="empleado" {{ old('role', $empleado->role) === 'empleado' ? 'selected' : '' }}>
                            Empleado (Cocina)
                        </option>
                        <option value="administrador" {{ old('role', $empleado->role) === 'administrador' ? 'selected' : '' }}>
                            Administrador (Control Total)
                        </option>
                    </select>
                    @error('role')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                    <small class="form-help">
                        <i class="fas fa-info-circle mr-1"></i>
                        Empleado: Gestiona pedidos. Admin: Control total del sistema.
                    </small>
                </div>

                <!-- CONTRASEÑA (OPCIONAL) -->
                <div class="form-group span-2">
                    <div class="password-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Cambiar Contraseña (Opcional):</strong> Deja estos campos vacíos si no deseas cambiar la contraseña.
                    </div>
                </div>

                <!-- NUEVA CONTRASEÑA -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-lock mr-2"></i>
                        Nueva Contraseña <span class="optional">(Opcional)</span>
                    </label>
                    <input
                        type="password"
                        name="password"
                        class="form-input @error('password') error @enderror"
                        placeholder="Dejar vacío si no cambias"
                    >
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- CONFIRMAR CONTRASEÑA -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-lock mr-2"></i>
                        Confirmar Nueva Contraseña
                    </label>
                    <input
                        type="password"
                        name="password_confirmation"
                        class="form-input"
                        placeholder="Confirma la nueva contraseña"
                    >
                </div>
            </div>

            <!-- BOTONES -->
            <div class="form-actions">
                <a href="{{ route('admin.empleados.index') }}" class="btn-cancel">
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </a>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save mr-2"></i>
                    Actualizar Empleado
                </button>
            </div>
        </form>
    </div>

</div>

@push('css')
<style>
    .container-form {
        max-width: 900px;
        margin: 0 auto;
        padding: 1rem;
    }

    .form-card {
        background: var(--color-secondary);
        border-radius: 12px;
        padding: 2rem;
        border: 2px solid rgba(248, 250, 252, 0.1);
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .span-2 {
        grid-column: 1 / -1;
    }

    .password-info {
        background: rgba(59, 130, 246, 0.1);
        border: 2px solid rgba(59, 130, 246, 0.3);
        color: #3b82f6;
        padding: 0.875rem 1rem;
        border-radius: 10px;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
    }

    .form-label {
        color: var(--color-text);
        font-weight: 600;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
    }

    .form-label i {
        color: var(--color-accent);
    }

    .optional {
        color: var(--color-text-muted);
        font-weight: 400;
        font-size: 0.75rem;
    }

    .form-input, select.form-input {
        padding: 0.75rem 1rem;
        background: var(--color-primary);
        border: 2px solid rgba(248, 250, 252, 0.1);
        border-radius: 10px;
        color: var(--color-text);
        font-size: 0.875rem;
        transition: all 0.3s ease;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--color-accent);
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
    }

    .form-input.error {
        border-color: var(--color-danger);
    }

    .error-message {
        color: var(--color-danger);
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .form-help {
        color: var(--color-text-muted);
        font-size: 0.75rem;
        display: flex;
        align-items: center;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        padding-top: 1.5rem;
        border-top: 2px solid rgba(248, 250, 252, 0.1);
    }

    .btn-cancel, .btn-submit {
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.875rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        text-decoration: none;
    }

    .btn-cancel {
        background: transparent;
        border: 2px solid rgba(248, 250, 252, 0.2);
        color: var(--color-text);
    }

    .btn-cancel:hover {
        background: rgba(248, 250, 252, 0.05);
    }

    .btn-submit {
        background: linear-gradient(135deg, var(--color-accent), var(--color-accent-light));
        color: var(--color-primary);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .form-card {
            padding: 1.5rem;
        }
    }
</style>
@endpush
@endsection

