@extends('layouts.app-admin')

@section('title', 'Gestión de Empleados - Admin')
@section('page-title', 'Gestión de Empleados')
@section('page-subtitle', 'Administra el personal del sistema')

@section('content')
<div class="container-empleados">

    <!-- HEADER CON ESTADÍSTICAS -->
    <div class="stats-header mb-6">
        <div class="stat-card">
            <div class="stat-icon bg-blue">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $empleados->total() }}</h3>
                <p>Total Personal</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-yellow">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $empleados->where('role', 'empleado')->count() }}</h3>
                <p>Empleados</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-purple">
                <i class="fas fa-crown"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $empleados->where('role', 'administrador')->count() }}</h3>
                <p>Administradores</p>
            </div>
        </div>

        <a href="{{ route('admin.empleados.create') }}" class="btn-create">
            <i class="fas fa-user-plus mr-2"></i>
            Crear Empleado
        </a>
    </div>

    <!-- MENSAJES -->
    @if(session('success'))
    <div class="alert alert-success mb-4">
        <i class="fas fa-check-circle mr-2"></i>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger mb-4">
        <i class="fas fa-exclamation-circle mr-2"></i>
        {{ session('error') }}
    </div>
    @endif

    <!-- TABLA DE EMPLEADOS -->
    <div class="empleados-table-container">
        <table class="empleados-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Rol</th>
                    <th>Fecha Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($empleados as $empleado)
                <tr>
                    <td>{{ $empleado->id }}</td>
                    <td>
                        <div class="empleado-name">
                            <div class="empleado-avatar">
                                {{ strtoupper(substr($empleado->name, 0, 2)) }}
                            </div>
                            <span>{{ $empleado->name }}</span>
                        </div>
                    </td>
                    <td>{{ $empleado->email }}</td>
                    <td>{{ $empleado->telefono ?? 'N/A' }}</td>
                    <td>
                        @if($empleado->role === 'administrador')
                        <span class="badge badge-admin">
                            <i class="fas fa-crown mr-1"></i>
                            Administrador
                        </span>
                        @else
                        <span class="badge badge-empleado">
                            <i class="fas fa-user-tie mr-1"></i>
                            Empleado
                        </span>
                        @endif
                    </td>
                    <td>{{ $empleado->created_at->format('d/m/Y') }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.empleados.edit', $empleado) }}" class="btn-action btn-edit" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($empleado->id !== auth()->id())
                            <form action="{{ route('admin.empleados.destroy', $empleado) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" title="Eliminar"
                                    onclick="return confirm('¿Estás seguro de eliminar a {{ $empleado->name }}?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-8">
                        <i class="fas fa-users text-4xl text-gray-500 mb-3"></i>
                        <p class="text-gray-400">No hay empleados registrados</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINACIÓN -->
    @if($empleados->hasPages())
    <div class="pagination-container mt-4">
        {{ $empleados->links() }}
    </div>
    @endif

</div>

@push('css')
<style>
    .container-empleados {
        padding: 1rem;
    }

    /* ESTADÍSTICAS */
    .stats-header {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .stat-card {
        background: var(--color-secondary);
        border-radius: 12px;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        border: 2px solid rgba(248, 250, 252, 0.1);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        border-color: var(--color-accent);
        transform: translateY(-2px);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .stat-icon.bg-blue { background: linear-gradient(135deg, #3b82f6, #2563eb); }
    .stat-icon.bg-yellow { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .stat-icon.bg-purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }

    .stat-info h3 {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--color-text);
        margin: 0;
    }

    .stat-info p {
        font-size: 0.875rem;
        color: var(--color-text-muted);
        margin: 0;
    }

    .btn-create {
        background: linear-gradient(135deg, var(--color-accent), var(--color-accent-light));
        color: var(--color-primary);
        padding: 0.875rem 1.5rem;
        border-radius: 10px;
        font-weight: 700;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
    }

    /* ALERTAS */
    .alert {
        padding: 1rem;
        border-radius: 10px;
        display: flex;
        align-items: center;
    }

    .alert-success {
        background: rgba(16, 185, 129, 0.15);
        border: 2px solid var(--color-success);
        color: var(--color-success);
    }

    .alert-danger {
        background: rgba(239, 68, 68, 0.15);
        border: 2px solid var(--color-danger);
        color: var(--color-danger);
    }

    /* TABLA */
    .empleados-table-container {
        background: var(--color-secondary);
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid rgba(248, 250, 252, 0.1);
    }

    .empleados-table {
        width: 100%;
        border-collapse: collapse;
    }

    .empleados-table thead {
        background: var(--color-primary);
    }

    .empleados-table th {
        padding: 1rem;
        text-align: left;
        font-weight: 700;
        color: var(--color-accent);
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .empleados-table td {
        padding: 1rem;
        color: var(--color-text);
        border-bottom: 1px solid rgba(248, 250, 252, 0.05);
    }

    .empleados-table tbody tr:hover {
        background: rgba(245, 158, 11, 0.05);
    }

    .empleado-name {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .empleado-avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--color-accent), var(--color-accent-light));
        color: var(--color-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.75rem;
    }

    /* BADGES */
    .badge {
        padding: 0.375rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
    }

    .badge-admin {
        background: rgba(245, 158, 11, 0.15);
        color: var(--color-accent);
        border: 1px solid var(--color-accent);
    }

    .badge-empleado {
        background: rgba(59, 130, 246, 0.15);
        color: #3b82f6;
        border: 1px solid #3b82f6;
    }

    /* BOTONES DE ACCIÓN */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.875rem;
    }

    .btn-edit {
        background: rgba(59, 130, 246, 0.15);
        color: #3b82f6;
        text-decoration: none;
    }

    .btn-edit:hover {
        background: #3b82f6;
        color: white;
        transform: translateY(-2px);
    }

    .btn-delete {
        background: rgba(239, 68, 68, 0.15);
        color: var(--color-danger);
    }

    .btn-delete:hover {
        background: var(--color-danger);
        color: white;
        transform: translateY(-2px);
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .empleados-table {
            font-size: 0.75rem;
        }

        .empleados-table th,
        .empleados-table td {
            padding: 0.625rem;
        }

        .stats-header {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush
@endsection

