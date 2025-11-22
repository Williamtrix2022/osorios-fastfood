<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Empleado - OsoriosFoodApp')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        :root {
            --color-dark: #1a1a1a;
            --color-gold: #d4af37;
            --color-accent: #f59e0b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--color-dark);
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #2a2a2a;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--color-gold);
            border-radius: 4px;
        }

        /* Navbar Superior */
        .navbar {
            background: linear-gradient(135deg, #2a2a2a 0%, #1a1a1a 100%);
            border-bottom: 2px solid var(--color-gold);
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-brand {
            font-size: 28px;
            font-weight: bold;
            color: white;
        }

        .nav-menu {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .nav-link {
            color: #9ca3af;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            padding: 8px 16px;
            border-radius: 8px;
        }

        .nav-link:hover {
            color: var(--color-gold);
            background-color: rgba(212, 175, 55, 0.1);
        }

        .nav-link.active {
            color: var(--color-gold);
            background-color: rgba(212, 175, 55, 0.2);
        }

        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .hidden {
            display: none !important;
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Dropdown del usuario */
        .user-dropdown {
            position: relative;
        }

        .user-menu {
            position: absolute;
            right: 0;
            top: 100%;
            margin-top: 10px;
            background-color: #2a2a2a;
            border: 1px solid #404040;
            border-radius: 8px;
            min-width: 200px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            display: none;
        }

        .user-dropdown:hover .user-menu {
            display: block;
        }

        .user-menu a,
        .user-menu button {
            display: block;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }

        .user-menu a:hover,
        .user-menu button:hover {
            background-color: rgba(212, 175, 55, 0.2);
            color: var(--color-gold);
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 15px;
            }

            .nav-menu {
                flex-direction: column;
                gap: 10px;
                width: 100%;
            }

            .nav-link {
                width: 100%;
                text-align: center;
            }
        }
    </style>
    @stack('css')
</head>

<body>
    <!-- NAVBAR SUPERIOR -->
    <nav class="navbar">
        <div class="nav-brand">
            <i class="fas fa-utensils" style="color: var(--color-gold);"></i>
            Osorios FoodApp
            <span style="color: var(--color-gold); font-size: 14px; font-weight: normal;">| Empleado</span>
        </div>

        <div class="nav-menu">
            <a href="{{ route('empleado.dashboard') }}" class="nav-link {{ request()->routeIs('empleado.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home mr-2"></i>Dashboard
            </a>
            <a href="{{ route('empleado.pedidos') }}" class="nav-link {{ request()->routeIs('empleado.pedidos') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list mr-2"></i>Pedidos
            </a>

            <!-- Usuario Dropdown -->
            <div class="user-dropdown">
                <button class="nav-link" style="cursor: pointer;">
                    <i class="fas fa-user-circle text-2xl"></i>
                    {{ Auth::user()->name }}
                    <i class="fas fa-chevron-down ml-2 text-sm"></i>
                </button>
                <div class="user-menu">
                    <a href="{{ route('profile.edit') }}">
                        <i class="fas fa-user mr-2"></i>Mi Perfil
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">
                            <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="main-container">
        @yield('content')
    </div>

    @stack('scripts')
</body>

</html>