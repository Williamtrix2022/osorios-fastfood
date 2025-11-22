@extends('layouts.auth')

@section('title', 'Iniciar Sesión - OsoriosFoodApp')

@section('content')
<div class="logo">
    <i class="fas fa-utensils"></i>
    <h1>Osorios FoodApp</h1>
    <p>Bienvenido de vuelta</p>
</div>

<!-- Mensaje de estado (si existe) -->
@if (session('status'))
<div class="status-message">
    <i class="fas fa-check-circle" style="margin-right: 6px;"></i>
    {{ session('status') }}
</div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf

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
               autofocus 
               autocomplete="username"
               class="form-input"
               placeholder="tu@email.com">
        @error('email')
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
                   autocomplete="current-password"
                   class="form-input"
                   placeholder="••••••••"
                   style="padding-right: 45px;">
            <button type="button" 
                    onclick="togglePassword()" 
                    style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #94a3b8; cursor: pointer; padding: 8px; transition: color 0.2s;">
                <i class="fas fa-eye" id="toggleIcon"></i>
            </button>
        </div>
        @error('password')
            <span class="error-message">
                <i class="fas fa-exclamation-circle" style="margin-right: 4px;"></i>
                {{ $message }}
            </span>
        @enderror
    </div>

    <!-- Recordar y olvidó contraseña -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div class="checkbox-group">
            <input id="remember_me" type="checkbox" name="remember">
            <label for="remember_me">Recuérdame</label>
        </div>

        @if (Route::has('password.request'))
        <a href="{{ route('password.request') }}" class="link" style="font-size: 13px;">
            ¿Olvidaste tu contraseña?
        </a>
        @endif
    </div>

    <!-- Botón de envío -->
    <button type="submit" class="btn-primary">
        <i class="fas fa-sign-in-alt"></i>
        Iniciar Sesión
    </button>

    <!-- Divisor -->
    <div class="divider">
        <span>o</span>
    </div>

    <!-- Link a registro -->
    <div class="text-center">
        ¿No tienes cuenta? 
        <a href="{{ route('register') }}" class="link">Regístrate aquí</a>
    </div>
</form>

<script>
// Función simple para mostrar/ocultar contraseña
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
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