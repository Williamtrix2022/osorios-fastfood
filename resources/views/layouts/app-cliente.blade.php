<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'OsoriosFoodApp')</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="shortcut icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    <!-- Tailwind CSS (incluido con Laravel) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Ajuste global de tamaños -->
    <link rel="stylesheet" href="{{ asset('css/size-adjust.css') }}">

    <!-- SweetAlert2 para alertas bonitas -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- CSRF Token (necesario para enviar datos POST) -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        /* ===== VARIABLES DE COLOR MODERNAS ===== */
        :root {
            --color-primary: #0f172a;      /* Azul oscuro (fondo principal) */
            --color-secondary: #1e293b;    /* Azul gris oscuro */
            --color-accent: #f59e0b;       /* Ámbar (color principal) */
            --color-accent-light: #fbbf24; /* Ámbar claro */
            --color-success: #10b981;      /* Verde esmeralda */
            --color-danger: #ef4444;       /* Rojo */
            --color-info: #3b82f6;         /* Azul */
            --color-warning: #f59e0b;      /* Ámbar */
            --color-text: #f8fafc;         /* Blanco casi puro */
            --color-text-muted: #94a3b8;   /* Gris azulado */
            --border-radius: 16px;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.2);
            --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.3);
            --shadow-xl: 0 12px 48px rgba(0, 0, 0, 0.4);
        }

        /* ===== RESET Y ESTILOS GLOBALES ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            width: 100%;
        }

        body {
            background: linear-gradient(135deg, var(--color-primary) 0%, #0a0f1e 100%);
            color: var(--color-text);
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* ===== SCROLLBAR PERSONALIZADO MODERNO ===== */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--color-secondary);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, var(--color-accent) 0%, var(--color-accent-light) 100%);
            border-radius: 5px;
            border: 2px solid var(--color-secondary);
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, var(--color-accent-light) 0%, var(--color-accent) 100%);
        }

        /* ===== LAYOUT PRINCIPAL CON NAVBAR ===== */
        .navbar-top {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(248, 250, 252, 0.1);
            box-shadow: var(--shadow-md);
        }

        .navbar-content {
            max-width: 1600px;
            margin: 0 auto;
            padding: 0.625rem 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1.25rem;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--color-text);
            text-decoration: none;
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
        }

        .navbar-brand i {
            color: var(--color-accent);
            font-size: 1.375rem;
        }

        .navbar-menu {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            flex: 1;
            justify-content: flex-end;
        }

        .navbar-link {
            color: var(--color-text-muted);
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-link:hover {
            color: var(--color-accent);
            background: rgba(245, 158, 11, 0.1);
        }

        .navbar-link.active {
            color: var(--color-accent);
            background: rgba(245, 158, 11, 0.15);
        }

        /* Botón de cerrar sesión */
        .logout-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
            font-size: inherit;
        }

        .logout-btn:hover {
            color: var(--color-danger) !important;
            background: rgba(239, 68, 68, 0.1) !important;
        }

        .cart-button {
            position: relative;
            background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-accent-light) 100%);
            color: var(--color-primary);
            padding: 0.625rem 1.25rem;
            border-radius: 50px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .cart-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--color-danger);
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.5);
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .container-app {
            min-height: calc(100vh - 80px);
        }

        /* Contenido principal (ocupa todo el espacio disponible) */
        .main-content {
            max-width: 1600px;
            margin: 0 auto;
            padding: 1.25rem 1rem;
        }

        /* Sidebar del carrito (móvil/tablet - overlay) */
        .sidebar-carrito {
            position: fixed;
            top: 0;
            right: -100%;
            width: 100%;
            max-width: 420px;
            height: 100vh;
            background: var(--color-secondary);
            box-shadow: var(--shadow-xl);
            z-index: 2000;
            overflow-y: auto;
            transition: right 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            border-left: 2px solid rgba(245, 158, 11, 0.2);
        }

        .sidebar-carrito.active {
            right: 0;
        }

        .cart-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(4px);
            z-index: 1999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .cart-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* ===== RESPONSIVO ===== */
        @media (min-width: 1280px) {
            .navbar-content {
                padding: 1.25rem 2rem;
            }

            .main-content {
                padding: 2.5rem 2rem;
            }
        }

        @media (max-width: 768px) {
            .navbar-content {
                padding: 0.875rem 1rem;
            }

            .navbar-brand {
                font-size: 1.25rem;
            }

            .navbar-link span {
                display: none;
            }

            .cart-button span {
                display: none;
            }

            .main-content {
                padding: 1.5rem 1rem;
            }
        }

        /* ===== ANIMACIONES MODERNAS ===== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        .slide-in-right {
            animation: slideInRight 0.4s ease-out;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

        /* ===== UTILIDADES MODERNAS ===== */
        .text-accent {
            color: var(--color-accent);
        }

        .bg-accent {
            background: var(--color-accent);
        }

        .gradient-accent {
            background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-accent-light) 100%);
        }

        .border-accent {
            border-color: var(--color-accent);
        }

        .hover-accent:hover {
            color: var(--color-accent);
        }

        .glass-effect {
            background: rgba(30, 41, 59, 0.6);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(248, 250, 252, 0.1);
        }

        /* ===== TRANSICIONES SUAVES ===== */
        button, a, input, select, textarea, .card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ===== UTILIDADES DE VISIBILIDAD ===== */
        .hidden {
            display: none !important;
        }

        .visible {
            display: block !important;
        }

        /* ===== MOBILE MENU TOGGLE ===== */
        .mobile-menu-btn {
            display: none;
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.3);
            color: var(--color-accent);
            padding: 0.5rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.25rem;
        }

        @media (max-width: 640px) {
            .mobile-menu-btn {
                display: block;
            }
        }
    </style>

    <!-- Stack para CSS adicional de cada vista -->
    @stack('css')
</head>

<body>
    <!-- NAVBAR SUPERIOR -->
    <nav class="navbar-top">
        <div class="navbar-content">
            <a href="{{ route('cliente.menu') }}" class="navbar-brand">
                <i class="fas fa-fire-flame-curved"></i>
                <span>Osorios Fast Food</span>
            </a>

            <div class="navbar-menu">
                <a href="{{ route('cliente.menu') }}" class="navbar-link {{ request()->routeIs('cliente.menu') ? 'active' : '' }}">
                    <i class="fas fa-utensils"></i>
                    <span>Menú</span>
                </a>
                <a href="{{ route('cliente.pedidos') }}" class="navbar-link {{ request()->routeIs('cliente.pedidos') ? 'active' : '' }}">
                    <i class="fas fa-receipt"></i>
                    <span>Mis Pedidos</span>
                </a>
                <a href="{{ route('profile.edit') }}" class="navbar-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <i class="fas fa-user-circle"></i>
                    <span>Perfil</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline; margin: 0;">
                    @csrf
                    <button type="submit" class="navbar-link logout-btn" title="Cerrar Sesión">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Salir</span>
                    </button>
                </form>
                <button onclick="toggleCartSidebar()" class="cart-button">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Carrito</span>
                    <span id="cartCount" class="cart-badge">0</span>
                </button>
            </div>
        </div>
    </nav>

    <!-- OVERLAY DEL CARRITO -->
    <div id="cartOverlay" class="cart-overlay" onclick="toggleCartSidebar()"></div>

    <!-- CONTENEDOR PRINCIPAL DEL APP -->
    <div class="container-app">
        <!-- CONTENIDO PRINCIPAL -->
        <div class="main-content">
            @yield('content')
        </div>

        <!-- SIDEBAR DEL CARRITO -->
        <div class="sidebar-carrito" id="cartSidebar">
            @include('cliente.partials.carrito-sidebar')
        </div>
    </div>

    <!-- FUNCIÓN PARA TOGGLE DEL CARRITO -->
    <script>
        function toggleCartSidebar() {
            const sidebar = document.getElementById('cartSidebar');
            const overlay = document.getElementById('cartOverlay');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');

            // Prevenir scroll del body cuando está abierto
            if (sidebar.classList.contains('active')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }

        // Cerrar con tecla ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const sidebar = document.getElementById('cartSidebar');
                const overlay = document.getElementById('cartOverlay');
                if (sidebar.classList.contains('active')) {
                    toggleCartSidebar();
                }
            }
        });
    </script>

    <!-- Stack para JavaScript adicional de cada vista -->
    @stack('scripts')
</body>

</html>
