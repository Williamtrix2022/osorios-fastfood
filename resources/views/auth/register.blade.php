@extends('layouts.auth')

@section('title', 'Registrarse - OsoriosFoodApp')

@section('content')
<div class="logo">
    <i class="fas fa-utensils"></i>
    <h1>Osorios FoodApp</h1>
    <p>Crea tu cuenta gratis</p>
</div>

<form method="POST" action="{{ route('register') }}">
    @csrf

    <!-- Nombre completo -->
    <div class="form-group">
        <label for="name" class="form-label">
            <i class="fas fa-user"></i>
            Nombre Completo
        </label>
        <input id="name"
               type="text"
               name="name"
               value="{{ old('name') }}"
               required
               autofocus
               autocomplete="name"
               class="form-input"
               placeholder="Ingresa tu nombre completo">
        @error('name')
            <span class="error-message">
                <i class="fas fa-exclamation-circle" style="margin-right: 4px;"></i>
                {{ $message }}
            </span>
        @enderror
    </div>

    <!-- Email -->
    <div class="form-group">
        <label for="email" class="form-label">
            <i class="fas fa-envelope"></i>
            Correo Electrónico
        </label>
        <input id="email"
               type="email"
               name="email"
               value="{{ old('email') }}"
               required
               autocomplete="username"
               class="form-input"
               placeholder="ejemplo@correo.com">
        @error('email')
            <span class="error-message">
                <i class="fas fa-exclamation-circle" style="margin-right: 4px;"></i>
                {{ $message }}
            </span>
        @enderror
    </div>

    <!-- Teléfono -->
    <div class="form-group">
        <label for="telefono" class="form-label">
            <i class="fas fa-phone"></i>
            Teléfono <span style="color: #94a3b8; font-weight: 400;">(Opcional)</span>
        </label>
        <input id="telefono"
               type="text"
               name="telefono"
               value="{{ old('telefono') }}"
               autocomplete="tel"
               class="form-input"
               placeholder="ej: 3001234567">
        @error('telefono')
            <span class="error-message">
                <i class="fas fa-exclamation-circle" style="margin-right: 4px;"></i>
                {{ $message }}
            </span>
        @enderror
    </div>

    <!-- Dirección -->
    <div class="form-group">
        <label for="direccion" class="form-label">
            <i class="fas fa-map-marker-alt"></i>
            Dirección <span style="color: #94a3b8; font-weight: 400;">(Opcional)</span>
        </label>
        <input id="direccion"
               type="text"
               name="direccion"
               value="{{ old('direccion') }}"
               autocomplete="street-address"
               class="form-input"
        @error('direccion')
            <span class="error-message">
                <i class="fas fa-exclamation-circle" style="margin-right: 4px;"></i>
                {{ $message }}
            </span>
        @enderror
    </div>

    <!-- Contraseña -->
    <div class="form-group">
        <label for="password" class="form-label">
            <i class="fas fa-lock"></i>
            Contraseña
        </label>
        <div style="position: relative;">
            <input id="password"
                   type="password"
                   name="password"
                   required
                   autocomplete="new-password"
                   class="form-input"
                   placeholder="Al menos 8 caracteres"
                   style="padding-right: 45px;">
            <button type="button"
                    onclick="togglePassword('password')"
                    style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #94a3b8; cursor: pointer; padding: 8px; transition: color 0.2s;">
                <i class="fas fa-eye" id="toggleIconPassword"></i>
            </button>
        </div>
        @error('password')
            <span class="error-message">
                <i class="fas fa-exclamation-circle" style="margin-right: 4px;"></i>
                {{ $message }}
            </span>
        @enderror
    </div>

    <!-- Confirmar contraseña -->
    <div class="form-group">
        <label for="password_confirmation" class="form-label">
            <i class="fas fa-lock"></i>
            Confirmar Contraseña
        </label>
        <div style="position: relative;">
            <input id="password_confirmation"
                   type="password"
                   name="password_confirmation"
                   required
                   autocomplete="new-password"
                   class="form-input"
                   placeholder="Confirma tu contraseña"
                   style="padding-right: 45px;">
            <button type="button"
                    onclick="togglePassword('password_confirmation')"
                    style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #94a3b8; cursor: pointer; padding: 8px; transition: color 0.2s;">
                <i class="fas fa-eye" id="toggleIconPasswordConfirmation"></i>
            </button>
        </div>
        @error('password_confirmation')
            <span class="error-message">
                <i class="fas fa-exclamation-circle" style="margin-right: 4px;"></i>
                {{ $message }}
            </span>
        @enderror
    </div>

    <!-- Botón de envío -->
    <button type="submit" class="btn-primary">
        <i class="fas fa-user-plus"></i>
        Crear Cuenta
    </button>

    <!-- Divisor -->
    <div class="divider">
        <span>o</span>
    </div>

    <!-- Link a login -->
    <div class="text-center">
        ¿Ya tienes cuenta?
        <a href="{{ route('login') }}" class="link">Inicia sesión aquí</a>
    </div>
</form>

<script>
// Función para mostrar/ocultar contraseña
function togglePassword(inputId) {
    const passwordInput = document.getElementById(inputId);
    const toggleIcon = document.getElementById('toggleIcon' + inputId.charAt(0).toUpperCase() + inputId.slice(1).replace('_', ''));

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}
</script>
@endsection
