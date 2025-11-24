<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Admin - OsoriosFoodApp')</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="shortcut icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Ajuste global de tamaños -->
    <link rel="stylesheet" href="{{ asset('css/size-adjust.css') }}">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            --color-info: #3b82f6;
            --color-warning: #f59e0b;
            --color-text: #f8fafc;
            --color-text-muted: #94a3b8;
            --sidebar-width: 280px;
            --topbar-height: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, var(--color-primary) 0%, #0a0f1e 100%);
            color: var(--color-text);
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ===== SCROLLBAR PERSONALIZADO ===== */
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
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, var(--color-accent-light) 0%, var(--color-accent) 100%);
        }

        /* ===== LAYOUT PRINCIPAL ===== */
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        aside.sidebar {
            width: var(--sidebar-width) !important;
            background: var(--color-secondary);
            border-right: 2px solid rgba(245, 158, 11, 0.2);
            position: fixed !important;
            left: 0 !important;
            top: 0 !important;
            height: 100vh !important;
            overflow-y: auto;
            z-index: 1000 !important;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 2px solid rgba(248, 250, 252, 0.1);
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0.5rem;
        }

        .brand-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-accent-light) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-primary);
            font-size: 1.5rem;
            font-weight: 800;
        }

        .brand-text h1 {
            font-size: 1.375rem;
            font-weight: 800;
            color: var(--color-text);
            line-height: 1.2;
        }

        .brand-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background: rgba(245, 158, 11, 0.15);
            color: var(--color-accent);
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 50px;
            margin-top: 0.375rem;
        }

        /* Menú del sidebar */
        .sidebar-menu {
            padding: 1.5rem 1rem;
        }

        .menu-section-title {
            padding: 0.5rem 1rem;
            color: var(--color-text-muted);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.875rem 1rem;
            color: var(--color-text-muted);
            text-decoration: none;
            border-radius: 12px;
            margin-bottom: 0.375rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 600;
            font-size: 0.9375rem;
        }

        .menu-item i {
            width: 24px;
            font-size: 1.125rem;
            text-align: center;
        }

        .menu-item:hover {
            background: rgba(245, 158, 11, 0.1);
            color: var(--color-accent);
            transform: translateX(4px);
        }

        .menu-item.active {
            background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-accent-light) 100%);
            color: var(--color-primary);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .menu-item.active i {
            color: var(--color-primary);
        }

        /* ===== CONTENIDO PRINCIPAL ===== */
        .main-content {
            flex: 1 !important;
            margin-left: var(--sidebar-width) !important;
            min-height: 100vh !important;
            width: calc(100% - var(--sidebar-width)) !important;
        }

        /* Topbar */
        .topbar {
            height: var(--topbar-height);
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 2px solid rgba(248, 250, 252, 0.1);
            padding: 0 2rem;
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
            gap: 2rem;
        }

        .page-title-section h2 {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--color-text);
            margin-bottom: 0.25rem;
        }

        .page-subtitle {
            font-size: 0.875rem;
            color: var(--color-text-muted);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .topbar-btn {
            width: 44px;
            height: 44px;
            background: rgba(248, 250, 252, 0.05);
            border: 2px solid rgba(248, 250, 252, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-text-muted);
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .topbar-btn:hover {
            background: rgba(245, 158, 11, 0.1);
            border-color: var(--color-accent);
            color: var(--color-accent);
        }

        .topbar-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            width: 20px;
            height: 20px;
            background: var(--color-danger);
            color: white;
            border-radius: 50%;
            font-size: 0.625rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.5);
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            background: rgba(248, 250, 252, 0.05);
            border: 2px solid rgba(248, 250, 252, 0.1);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .user-menu:hover {
            background: rgba(245, 158, 11, 0.1);
            border-color: var(--color-accent);
        }

        /* Dropdown de usuario */
        .user-dropdown {
            position: relative;
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

        .user-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-accent-light) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-primary);
            font-weight: 700;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--color-text);
            line-height: 1.2;
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--color-accent);
        }

        /* Contenedor de páginas */
        .page-container {
            padding: 1.5rem;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .topbar {
                padding: 0 1rem;
            }

            .page-title-section h2 {
                font-size: 1.25rem;
            }

            .user-info {
                display: none;
            }

            .page-container {
                padding: 1.5rem 1rem;
            }
        }

        /* Botón toggle para móvil */
        .mobile-menu-toggle {
            display: none;
            width: 44px;
            height: 44px;
            background: rgba(245, 158, 11, 0.1);
            border: 2px solid rgba(245, 158, 11, 0.3);
            border-radius: 12px;
            color: var(--color-accent);
            font-size: 1.25rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .mobile-menu-toggle:hover {
            background: var(--color-accent);
            color: var(--color-primary);
        }

        @media (max-width: 1024px) {
            .mobile-menu-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }

        /* Overlay para móvil */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(4px);
            z-index: 999;
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* ===== UTILIDADES ===== */
        .text-accent { color: var(--color-accent); }
        .text-success { color: var(--color-success); }
        .text-danger { color: var(--color-danger); }
        .text-info { color: var(--color-info); }

        /* Animaciones */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }
    </style>

    @stack('css')
</head>

<body>
    <div class="admin-layout">

        <!-- Overlay para cerrar sidebar en móvil -->
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

        <!-- ===== SIDEBAR ===== -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-brand">
                    <div class="brand-icon">
                        <i class="fas fa-fire-flame-curved"></i>
                    </div>
                    <div class="brand-text">
                        <h1>Osorios</h1>
                        <span class="brand-badge">
                            <i class="fas fa-shield-check mr-1"></i>Admin Panel
                        </span>
                    </div>
                </div>
            </div>

            <nav class="sidebar-menu">
                <div class="menu-section-title">
                    <i class="fas fa-grip-horizontal mr-2"></i>Principal
                </div>
                <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.pedidos.index') }}" class="menu-item {{ request()->routeIs('admin.pedidos.*') ? 'active' : '' }}">
                    <i class="fas fa-receipt"></i>
                    <span>Pedidos</span>
                </a>
                <a href="{{ route('admin.pagos.index') }}" class="menu-item {{ request()->routeIs('admin.pagos.*') ? 'active' : '' }}">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Pagos</span>
                </a>

                <div class="menu-section-title mt-6">
                    <i class="fas fa-boxes mr-2"></i>Gestión
                </div>
                <a href="{{ route('admin.productos.index') }}" class="menu-item {{ request()->routeIs('admin.productos.*') ? 'active' : '' }}">
                    <i class="fas fa-hamburger"></i>
                    <span>Productos</span>
                </a>
                <a href="{{ route('admin.empleados.index') }}" class="menu-item {{ request()->routeIs('admin.empleados.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Empleados</span>
                </a>
                <a href="{{ route('admin.reportes') }}" class="menu-item {{ request()->routeIs('admin.reportes') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i>
                    <span>Reportes</span>
                </a>
            </nav>
        </aside>

        <!-- ===== CONTENIDO PRINCIPAL ===== -->
        <div class="main-content">

            <!-- Topbar -->
            <header class="topbar">
                <div class="topbar-left">
                    <button class="mobile-menu-toggle" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="page-title-section">
                        <h2>@yield('page-title', 'Panel de Administración')</h2>
                        <p class="page-subtitle">@yield('page-subtitle', 'Gestiona tu negocio')</p>
                    </div>
                </div>

                <div class="topbar-right">
                    <!-- Dropdown de Usuario -->
                    <div class="user-dropdown">
                        <div class="user-menu" onclick="toggleUserDropdown()">
                            <div class="user-avatar">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="user-info">
                                <span class="user-name">{{ Auth::user()->name }}</span>
                                <span class="user-role">Administrador</span>
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
            </header>

            <!-- Contenido de la página -->
            <main class="page-container fade-in">
                @yield('content')
            </main>
        </div>

    </div>

    <!-- Scripts globales -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
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

        // Cerrar sidebar al hacer click en un link (móvil)
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function() {
                if (window.innerWidth <= 1024) {
                    toggleSidebar();
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>

