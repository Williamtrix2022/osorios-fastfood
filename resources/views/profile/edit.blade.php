@php
    $user = Auth::user();
    $isAdmin = $user->role === 'administrador';
    $isEmpleado = $user->role === 'empleado';
    $isCliente = $user->role === 'cliente';

    // Determinar el layout según el rol
    if ($isAdmin) {
        $layout = 'layouts.app-admin';
        $pageTitle = 'Mi Perfil';
        $pageSubtitle = 'Administra tu información personal';
    } elseif ($isEmpleado) {
        $layout = 'layouts.app-empleado';
        $pageTitle = 'Mi Perfil';
        $pageSubtitle = 'Actualiza tu información';
    } else {
        $layout = 'layouts.app-cliente';
        $pageTitle = 'Mi Perfil';
        $pageSubtitle = 'Gestiona tu cuenta';
    }
@endphp

@extends($layout)

@section('title', 'Mi Perfil - OsoriosFoodApp')

@if($isAdmin || $isEmpleado)
    @section('page-title', $pageTitle)
    @section('page-subtitle', $pageSubtitle)
@endif

@section('content')
<div class="profile-page fade-in-up">

    <!-- HEADER DEL PERFIL -->
    <div class="profile-header mb-8">
        <div class="profile-avatar-section">
            <div class="profile-avatar">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div class="profile-info">
                <h1 class="profile-name">{{ $user->name }}</h1>
                <p class="profile-email">{{ $user->email }}</p>
                <span class="profile-role-badge {{ $isAdmin ? 'admin' : ($isEmpleado ? 'empleado' : 'cliente') }}">
                    <i class="fas fa-{{ $isAdmin ? 'crown' : ($isEmpleado ? 'user-tie' : 'user') }} mr-2"></i>
                    {{ ucfirst($user->role) }}
                </span>
            </div>
        </div>
    </div>

    <!-- GRID DE SECCIONES -->
    <div class="profile-sections-grid">

        <!-- SECCIÓN: INFORMACIÓN DEL PERFIL -->
        <div class="profile-section-card">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-user-circle text-accent mr-2"></i>
                    Información Personal
                </h2>
                <p class="section-subtitle">Actualiza tu información de perfil y correo electrónico</p>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" class="profile-form">
                @csrf
                @method('PATCH')

                <!-- Nombre -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-user mr-2"></i>
                        Nombre Completo
                    </label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        required
                        class="form-input"
                        placeholder="Tu nombre completo"
                    >
                    @error('name')
                        <span class="error-message">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-envelope mr-2"></i>
                        Correo Electrónico
                    </label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        class="form-input"
                        placeholder="tu@email.com"
                    >
                    @error('email')
                        <span class="error-message">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </span>
                    @enderror

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="verification-notice">
                            <i class="fas fa-info-circle mr-2"></i>
                            Tu correo no está verificado.
                            <button form="send-verification" class="verify-link">
                                Reenviar correo de verificación
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Botón Guardar -->
                <div class="form-actions">
                    <button type="submit" class="btn-save">
                        <i class="fas fa-save mr-2"></i>
                        Guardar Cambios
                    </button>
                </div>

                @if (session('status') === 'profile-updated')
                    <div class="success-message">
                        <i class="fas fa-check-circle mr-2"></i>
                        ¡Perfil actualizado exitosamente!
                    </div>
                @endif
            </form>
        </div>

        <!-- SECCIÓN: CAMBIAR CONTRASEÑA -->
        <div class="profile-section-card">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-lock text-accent mr-2"></i>
                    Actualizar Contraseña
                </h2>
                <p class="section-subtitle">Asegúrate de usar una contraseña segura</p>
            </div>

            <form method="POST" action="{{ route('password.update') }}" class="profile-form">
                @csrf
                @method('PUT')

                <!-- Contraseña Actual -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-key mr-2"></i>
                        Contraseña Actual
                    </label>
                    <input
                        type="password"
                        name="current_password"
                        required
                        class="form-input"
                        placeholder="Tu contraseña actual"
                    >
                    @error('current_password', 'updatePassword')
                        <span class="error-message">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Nueva Contraseña -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-lock mr-2"></i>
                        Nueva Contraseña
                    </label>
                    <input
                        type="password"
                        name="password"
                        required
                        class="form-input"
                        placeholder="Mínimo 8 caracteres"
                    >
                    @error('password', 'updatePassword')
                        <span class="error-message">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Confirmar Contraseña -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-lock mr-2"></i>
                        Confirmar Nueva Contraseña
                    </label>
                    <input
                        type="password"
                        name="password_confirmation"
                        required
                        class="form-input"
                        placeholder="Repite la contraseña"
                    >
                    @error('password_confirmation', 'updatePassword')
                        <span class="error-message">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Botón Guardar -->
                <div class="form-actions">
                    <button type="submit" class="btn-save">
                        <i class="fas fa-shield-alt mr-2"></i>
                        Actualizar Contraseña
                    </button>
                </div>

                @if (session('status') === 'password-updated')
                    <div class="success-message">
                        <i class="fas fa-check-circle mr-2"></i>
                        ¡Contraseña actualizada exitosamente!
                    </div>
                @endif
            </form>
        </div>

        <!-- SECCIÓN: ELIMINAR CUENTA (SOLO CLIENTES) -->
        @if($isCliente)
        <div class="profile-section-card danger-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-exclamation-triangle text-danger mr-2"></i>
                    Zona de Peligro
                </h2>
                <p class="section-subtitle">Una vez eliminada, toda tu información será permanentemente borrada</p>
            </div>

            <div class="danger-content">
                <p class="danger-text">
                    <i class="fas fa-info-circle mr-2"></i>
                    Esta acción es irreversible. Se eliminarán todos tus datos, pedidos e información personal.
                </p>

                <button
                    type="button"
                    onclick="confirmDelete()"
                    class="btn-danger"
                >
                    <i class="fas fa-trash-alt mr-2"></i>
                    Eliminar Mi Cuenta
                </button>
            </div>
        </div>
        @endif

    </div>
</div>

<!-- Modal de Confirmación de Eliminación (SOLO CLIENTES) -->
@if($isCliente)
<div id="deleteModal" class="delete-modal">
    <div class="delete-modal-content">
        <div class="delete-modal-header">
            <i class="fas fa-exclamation-triangle"></i>
            <h3>¿Estás absolutamente seguro?</h3>
        </div>

        <p class="delete-modal-text">
            Esta acción es permanente y no se puede deshacer. Se eliminarán:
        </p>

        <ul class="delete-modal-list">
            <li><i class="fas fa-times-circle mr-2"></i>Tu cuenta y toda tu información personal</li>
            <li><i class="fas fa-times-circle mr-2"></i>Todos tus pedidos e historial</li>
            <li><i class="fas fa-times-circle mr-2"></i>Cualquier dato asociado a tu cuenta</li>
        </ul>

        <form method="POST" action="{{ route('profile.destroy') }}" id="deleteForm">
            @csrf
            @method('DELETE')

            <div class="form-group">
                <label class="form-label">
                    Para confirmar, ingresa tu contraseña:
                </label>
                <input
                    type="password"
                    name="password"
                    required
                    class="form-input"
                    placeholder="Tu contraseña"
                >
            </div>

            <div class="delete-modal-actions">
                <button type="button" onclick="closeDeleteModal()" class="btn-cancel">
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </button>
                <button type="submit" class="btn-confirm-delete">
                    <i class="fas fa-trash-alt mr-2"></i>
                    Sí, Eliminar Mi Cuenta
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<!-- Formulario oculto para verificación de email -->
@if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
<form id="send-verification" method="POST" action="{{ route('verification.send') }}" style="display: none;">
    @csrf
</form>
@endif

@endsection

@push('css')
<style>
    /* ===== VARIABLES ===== */
    :root {
        --color-primary: #0f172a;
        --color-secondary: #1e293b;
        --color-accent: #f59e0b;
        --color-success: #10b981;
        --color-danger: #ef4444;
        --color-text: #f8fafc;
        --color-text-muted: #94a3b8;
    }

    /* ===== HEADER DEL PERFIL ===== */
    .profile-header {
        background: var(--color-secondary);
        border-radius: 16px;
        padding: 2rem;
        border: 2px solid rgba(248, 250, 252, 0.1);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
    }

    .profile-avatar-section {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, var(--color-accent) 0%, #fbbf24 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--color-primary);
        font-size: 2.5rem;
        font-weight: 800;
        box-shadow: 0 8px 24px rgba(245, 158, 11, 0.3);
    }

    .profile-info {
        flex: 1;
    }

    .profile-name {
        font-size: 2rem;
        font-weight: 800;
        color: var(--color-text);
        margin-bottom: 0.5rem;
    }

    .profile-email {
        font-size: 1rem;
        color: var(--color-text-muted);
        margin-bottom: 0.75rem;
    }

    .profile-role-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 700;
    }

    .profile-role-badge.admin {
        background: rgba(245, 158, 11, 0.15);
        color: var(--color-accent);
        border: 2px solid rgba(245, 158, 11, 0.3);
    }

    .profile-role-badge.empleado {
        background: rgba(59, 130, 246, 0.15);
        color: #3b82f6;
        border: 2px solid rgba(59, 130, 246, 0.3);
    }

    .profile-role-badge.cliente {
        background: rgba(16, 185, 129, 0.15);
        color: var(--color-success);
        border: 2px solid rgba(16, 185, 129, 0.3);
    }

    /* ===== GRID DE SECCIONES ===== */
    .profile-sections-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
    }

    @media (min-width: 1024px) {
        .profile-sections-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .danger-section {
            grid-column: 1 / -1;
        }
    }

    /* ===== TARJETAS DE SECCIÓN ===== */
    .profile-section-card {
        background: var(--color-secondary);
        border-radius: 14px;
        padding: 1.5rem;
        border: 2px solid rgba(248, 250, 252, 0.1);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }

    .profile-section-card:hover {
        border-color: var(--color-accent);
        box-shadow: 0 8px 24px rgba(245, 158, 11, 0.2);
    }

    .danger-section {
        border-color: rgba(239, 68, 68, 0.3);
    }

    .danger-section:hover {
        border-color: var(--color-danger);
        box-shadow: 0 8px 24px rgba(239, 68, 68, 0.2);
    }

    .section-header {
        margin-bottom: 1.25rem;
        padding-bottom: 0.875rem;
        border-bottom: 2px solid rgba(248, 250, 252, 0.1);
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--color-text);
        margin-bottom: 0.375rem;
        display: flex;
        align-items: center;
    }

    .section-subtitle {
        font-size: 0.8125rem;
        color: var(--color-text-muted);
    }

    /* ===== FORMULARIOS ===== */
    .profile-form {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-label {
        color: var(--color-text);
        font-weight: 600;
        font-size: 0.9375rem;
        display: flex;
        align-items: center;
    }

    .form-label i {
        color: var(--color-accent);
    }

    .form-input {
        width: 100%;
        padding: 0.875rem 1rem;
        background: var(--color-primary);
        color: var(--color-text);
        border: 2px solid rgba(248, 250, 252, 0.1);
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--color-accent);
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
    }

    .form-input::placeholder {
        color: var(--color-text-muted);
    }

    .error-message {
        color: var(--color-danger);
        font-size: 0.875rem;
        display: flex;
        align-items: center;
    }

    .success-message {
        background: rgba(16, 185, 129, 0.15);
        border: 2px solid var(--color-success);
        color: var(--color-success);
        padding: 1rem;
        border-radius: 12px;
        font-weight: 600;
        display: flex;
        align-items: center;
        animation: slideInUp 0.4s ease;
    }

    /* ===== BOTONES ===== */
    .form-actions {
        display: flex;
        gap: 1rem;
    }

    .btn-save {
        padding: 0.875rem 1.5rem;
        background: linear-gradient(135deg, var(--color-accent) 0%, #fbbf24 100%);
        color: var(--color-primary);
        border: none;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(245, 158, 11, 0.5);
    }

    /* ===== ZONA DE PELIGRO ===== */
    .danger-content {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .danger-text {
        color: var(--color-text-muted);
        display: flex;
        align-items: start;
        gap: 0.5rem;
        line-height: 1.6;
    }

    .btn-danger {
        padding: 0.875rem 1.5rem;
        background: rgba(239, 68, 68, 0.1);
        color: var(--color-danger);
        border: 2px solid var(--color-danger);
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        align-self: flex-start;
    }

    .btn-danger:hover {
        background: var(--color-danger);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
    }

    /* ===== MODAL DE ELIMINACIÓN ===== */
    .delete-modal {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.9);
        backdrop-filter: blur(8px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 1rem;
    }

    .delete-modal.active {
        display: flex;
    }

    .delete-modal-content {
        background: var(--color-secondary);
        border-radius: 20px;
        max-width: 500px;
        width: 100%;
        padding: 2rem;
        border: 2px solid var(--color-danger);
        box-shadow: 0 12px 48px rgba(0, 0, 0, 0.5);
        animation: modalSlideIn 0.3s ease;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .delete-modal-header {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .delete-modal-header i {
        font-size: 4rem;
        color: var(--color-danger);
        margin-bottom: 1rem;
        display: block;
    }

    .delete-modal-header h3 {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--color-text);
    }

    .delete-modal-text {
        color: var(--color-text-muted);
        margin-bottom: 1rem;
    }

    .delete-modal-list {
        list-style: none;
        margin-bottom: 2rem;
        padding-left: 0;
    }

    .delete-modal-list li {
        color: var(--color-danger);
        padding: 0.5rem 0;
        display: flex;
        align-items: center;
    }

    .delete-modal-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .btn-cancel, .btn-confirm-delete {
        flex: 1;
        padding: 0.875rem;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-cancel {
        background: transparent;
        color: var(--color-text);
        border: 2px solid rgba(248, 250, 252, 0.2);
    }

    .btn-cancel:hover {
        background: rgba(248, 250, 252, 0.05);
        border-color: rgba(248, 250, 252, 0.3);
    }

    .btn-confirm-delete {
        background: var(--color-danger);
        color: white;
        border: none;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .btn-confirm-delete:hover {
        background: #dc2626;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.5);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .profile-avatar-section {
            flex-direction: column;
            text-align: center;
        }

        .profile-name {
            font-size: 1.5rem;
        }
    }

    /* ===== VERIFICACIÓN EMAIL ===== */
    .verification-notice {
        background: rgba(59, 130, 246, 0.1);
        border: 2px solid rgba(59, 130, 246, 0.3);
        color: #3b82f6;
        padding: 1rem;
        border-radius: 12px;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .verify-link {
        background: none;
        border: none;
        color: #3b82f6;
        text-decoration: underline;
        cursor: pointer;
        font-weight: 600;
        margin-left: 0.5rem;
    }

    .verify-link:hover {
        color: #2563eb;
    }
</style>
@endpush

@push('scripts')
<script>
    function confirmDelete() {
        document.getElementById('deleteModal').classList.add('active');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('active');
    }

    // Cerrar modal con ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
    });

    // Cerrar modal al hacer click fuera
    document.getElementById('deleteModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });

    // Auto-hide success messages
    setTimeout(() => {
        document.querySelectorAll('.success-message').forEach(el => {
            el.style.transition = 'opacity 0.5s ease';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        });
    }, 5000);
</script>
@endpush

