<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Admin - OsoriosFoodApp')</title>
    
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

        /* Layout con Sidebar */
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #2a2a2a 0%, #1a1a1a 100%);
            border-right: 2px solid var(--color-gold);
            padding: 30px 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
        }

        .sidebar-brand {
            padding: 0 30px 30px;
            border-bottom: 1px solid #404040;
            margin-bottom: 30px;
        }

        .sidebar-brand h1 {
            font-size: 24px;
            font-weight: bold;
            color: white;
        }

        .sidebar-brand .badge {
            font-size: 12px;
            color: var(--color-gold);
            font-weight: normal;
        }

        .sidebar-menu {
            padding: 0 15px;
        }

        .menu-item {
            display: block;
            padding: 12px 15px;
            color: #9ca3af;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .menu-item:hover {
            background-color: rgba(212, 175, 55, 0.1);
            color: var(--color-gold);
        }

        .menu-item.active {
            background-color: rgba(212, 175, 55, 0.2);
            color: var(--color-gold);
            border-left: 4px solid var(--color-gold);
        }

        .menu-item i {
            width: 25px;
            margin-right: 10px;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            flex: 1;
            padding: 30px;
            min-height: 100vh;
        }

        /* Top Bar */
        .top-bar {
            background: linear-gradient(135deg, #2a2a2a 0%, #1a1a1a 100%);
            border-radius: 15px;
            padding: 20px 30px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #404040;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            background-color: var(--color-gold);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: var(--color-dark);
            font-weight: bold;
        }

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

        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar.active {
                width: 280px;
            }
        }
    </style>
    @stack('css')
</head>

<body>
    <div class="admin-layout">
        
        <!-- SIDEBAR -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <h1>
                    <i class="fas fa-utensils" style="color: var(--color-gold);"></i>
                    Osorios FoodApp
                </h1>
                <span class="badge">Panel de Administración</span>
            </div>

            <nav class="sidebar-menu">
                <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>Dashboard
                </a>
                <a href="{{ route('admin.productos.index') }}" class="menu-item {{ request()->routeIs('admin.productos.*') ? 'active' : '' }}">
                    <i class="fas fa-hamburger"></i>Productos
                </a>
                <a href="{{ route('admin.pedidos.index') }}" class="menu-item {{ request()->routeIs('admin.pedidos.*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list"></i>Pedidos
                </a>
                <a href="{{ route('admin.reportes') }}" class="menu-item {{ request()->routeIs('admin.reportes') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice"></i>Reportes
                </a>
                
                <hr style="border-color: #404040; margin: 20px 15px;">
                
                <a href="{{ route('profile.edit') }}" class="menu-item">
                    <i class="fas fa-user-cog"></i>Mi Perfil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="menu-item w-full text-left">
                        <i class="fas fa-sign-out-alt"></i>Cerrar Sesión
                    </button>
                </form>
            </nav>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content">
            <!-- Top Bar -->
            <div class="top-bar">
                <div>
                    <h2 class="text-2xl font-bold text-white">@yield('page-title', 'Dashboard')</h2>
                    <p class="text-gray-400 text-sm">@yield('page-subtitle', 'Bienvenido al panel de administración')</p>
                </div>

                <div class="user-dropdown">
                    <div class="user-info" style="cursor: pointer;">
                        <div>
                            <p class="text-white font-semibold">{{ Auth::user()->name }}</p>
                            <p class="text-gray-400 text-sm">Administrador</p>
                        </div>
                        <div class="user-avatar">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
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

            <!-- Page Content -->
            @yield('content')
        </main>

    </div>

    @stack('scripts')
</body>

</html>