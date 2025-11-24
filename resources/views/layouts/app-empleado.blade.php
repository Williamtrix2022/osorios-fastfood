<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Empleado - OsoriosFoodApp')</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="shortcut icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Ajuste global de tamaños -->
    <link rel="stylesheet" href="{{ asset('css/size-adjust.css') }}">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        /* ===== VARIABLES GLOBALES ===== */
        :root {
            --color-primary: #0f172a;
            --color-secondary: #1e293b;
            --color-accent: #f59e0b;
            --color-accent-light: #fbbf24;
            --color-success: #10b981;
            --color-danger: #ef4444;
            --color-text: #f8fafc;
            --color-text-muted: #94a3b8;
            --sidebar-width: 250px;
            --topbar-height: 60px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--color-primary);
            color: var(--color-text);
            overflow-x: hidden;
        }

        /* ===== LAYOUT PRINCIPAL ===== */
        .app-layout {
            display: flex;
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        aside.sidebar {
            width: var(--sidebar-width) !important;
            background: var(--color-secondary);
            border-right: 2px solid rgba(248, 250, 252, 0.1);
            position: fixed !important;
            left: 0 !important;
            top: 0 !important;
            height: 100vh !important;
            overflow-y: auto;
            z-index: 1000 !important;
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            padding: 1.25rem 1rem;
            border-bottom: 2px solid rgba(248, 250, 252, 0.1);
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .brand-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-accent-light) 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-primary);
            font-size: 1.125rem;
            font-weight: 800;
        }

        .brand-text h1 {
            font-size: 1.125rem;
            font-weight: 800;
            color: var(--color-text);
            line-height: 1.2;
        }

        .brand-badge {
            display: inline-block;
            padding: 0.125rem 0.5rem;
            background: rgba(59, 130, 246, 0.15);
            color: #3b82f6;
            font-size: 0.625rem;
            font-weight: 600;
            border-radius: 50px;
            margin-top: 0.1875rem;
        }

        /* Menú del sidebar */
        .sidebar-menu {
            padding: 1rem 0.75rem;
        }

        .menu-section-title {
            padding: 0.25rem 0.75rem;
            color: var(--color-text-muted);
            font-size: 0.625rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.25rem;
            margin-top: 1rem;
        }

        .menu-section-title:first-child {
            margin-top: 0;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 0.75rem;
            color: var(--color-text-muted);
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 0.1875rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 600;
            font-size: 0.8125rem;
        }

        .menu-item i {
            width: 18px;
            font-size: 0.875rem;
            text-align: center;
        }

        .menu-item:hover {
            color: var(--color-accent);
            background: rgba(245, 158, 11, 0.1);
            transform: translateX(4px);
        }

        .menu-item.active {
            color: var(--color-accent);
            background: rgba(245, 158, 11, 0.15);
            border-left: 3px solid var(--color-accent);
        }

        /* ===== CONTENIDO PRINCIPAL ===== */
        .main-wrapper {
            margin-left: var(--sidebar-width) !important;
            width: calc(100% - var(--sidebar-width)) !important;
            min-height: 100vh !important;
            display: flex !important;
            flex-direction: column !important;
        }

        /* Topbar */
        .topbar {
            height: var(--topbar-height);
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 2px solid rgba(248, 250, 252, 0.1);
            padding: 0 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .page-title-section h2 {
            font-size: 1.375rem;
            font-weight: 800;
            color: var(--color-text);
            margin-bottom: 0.125rem;
        }

        .page-subtitle {
            font-size: 0.75rem;
            color: var(--color-text-muted);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* Dropdown de usuario */
        .user-dropdown {
            position: relative;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.5rem 0.875rem;
            background: rgba(248, 250, 252, 0.05);
            border-radius: 50px;
            border: 2px solid rgba(248, 250, 252, 0.1);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .user-menu:hover {
            background: rgba(245, 158, 11, 0.1);
            border-color: var(--color-accent);
        }

        .dropdown-menu {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            min-width: 220px;
            background: var(--color-secondary);
            border: 2px solid rgba(248, 250, 252, 0.1);
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 9999;
        }

        .dropdown-menu.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.25rem;
            color: var(--color-text);
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.875rem;
            font-weight: 500;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }

        .dropdown-item:first-child {
            border-radius: 10px 10px 0 0;
        }

        .dropdown-item:last-child {
            border-radius: 0 0 10px 10px;
        }

        .dropdown-item:hover {
            background: rgba(245, 158, 11, 0.1);
            color: var(--color-accent);
        }

        .dropdown-item i {
            width: 20px;
            text-align: center;
            color: var(--color-accent);
        }

        .dropdown-divider {
            height: 1px;
            background: rgba(248, 250, 252, 0.1);
            margin: 0.25rem 0;
        }

        .user-info-text {
            display: flex;
            flex-direction: column;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.75rem;
            color: white;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.8125rem;
            color: var(--color-text);
            line-height: 1.2;
        }

        .user-role {
            font-size: 0.6875rem;
            color: #3b82f6;
            line-height: 1.2;
        }

        /* Contenedor de páginas */
        .page-container {
            padding: 1.25rem;
            flex: 1;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            aside.sidebar {
                transform: translateX(-100%);
            }

            aside.sidebar.active {
                transform: translateX(0);
            }

            .main-wrapper {
                margin-left: 0 !important;
                width: 100% !important;
            }

            .mobile-menu-btn {
                display: block !important;
            }
        }

        .mobile-menu-btn {
            display: none;
            background: rgba(245, 158, 11, 0.1);
            border: none;
            color: var(--color-accent);
            padding: 0.5rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.125rem;
        }
    </style>

    @stack('css')
</head>

<body>
    <div class="app-layout">
        <!-- ===== SIDEBAR ===== -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-brand">
                    <div class="brand-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <div class="brand-text">
                        <h1>Osorios Food</h1>
                        <span class="brand-badge">EMPLEADO</span>
                    </div>
                </div>
            </div>

            <nav class="sidebar-menu">
                <div class="menu-section-title">
                    <i class="fas fa-home mr-2"></i>Principal
                </div>
                <a href="{{ route('empleado.dashboard') }}" class="menu-item {{ request()->routeIs('empleado.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>

                <div class="menu-section-title">
                    <i class="fas fa-receipt mr-2"></i>Pedidos
                </div>
                <a href="{{ route('empleado.pedidos') }}" class="menu-item {{ request()->routeIs('empleado.pedidos') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Pedidos Activos</span>
                </a>
                <a href="{{ route('empleado.pedidos.completados') }}" class="menu-item {{ request()->routeIs('empleado.pedidos.completados') ? 'active' : '' }}">
                    <i class="fas fa-check-double"></i>
                    <span>Pedidos Completados</span>
                </a>
            </nav>
        </aside>

        <!-- ===== CONTENIDO PRINCIPAL ===== -->
        <div class="main-wrapper">
            <!-- Topbar -->
            <div class="topbar">
                <div class="topbar-left">
                    <button class="mobile-menu-btn" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="page-title-section">
                        <h2>@yield('page-title', 'Panel Empleado')</h2>
                        <p class="page-subtitle">@yield('page-subtitle', 'Gestión de cocina y pedidos')</p>
                    </div>
                </div>
                <div class="topbar-right">
                    <!-- Dropdown de Usuario -->
                    <div class="user-dropdown">
                        <div class="user-menu" onclick="toggleUserDropdown()">
                            <div class="user-avatar">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                            <div class="user-info-text">
                                <span class="user-name">{{ Auth::user()->name }}</span>
                                <span class="user-role">Empleado</span>
                            </div>
                            <i class="fas fa-chevron-down text-gray-500"></i>
                        </div>

                        <!-- Menú Dropdown -->
                        <div class="dropdown-menu" id="userDropdown">
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                <i class="fas fa-user-circle"></i>
                                <span>Mi Perfil</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Cerrar Sesión</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido de la página -->
            <div class="page-container">
                @yield('content')
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }

        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('active');
        }

        // Cerrar dropdown al hacer click fuera
        document.addEventListener('click', function(event) {
            const userDropdown = document.querySelector('.user-dropdown');
            const dropdown = document.getElementById('userDropdown');

            if (dropdown && userDropdown && !userDropdown.contains(event.target)) {
                dropdown.classList.remove('active');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>

