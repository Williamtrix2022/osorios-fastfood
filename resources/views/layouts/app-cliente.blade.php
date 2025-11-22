<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'OsoriosFoodApp')</title>
    
    <!-- Tailwind CSS (incluido con Laravel) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- SweetAlert2 para alertas bonitas -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- CSRF Token (necesario para enviar datos POST) -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        /* ===== VARIABLES DE COLOR ===== */
        :root {
            --color-dark: #1a1a1a;        /* Negro oscuro (fondo principal) */
            --color-gold: #d4af37;        /* Dorado (color principal) */
            --color-accent: #f59e0b;      /* Naranja (acentos) */
            --color-success: #10b981;     /* Verde (éxito) */
            --color-danger: #ef4444;      /* Rojo (peligro) */
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
            background-color: var(--color-dark);
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        /* ===== SCROLLBAR PERSONALIZADO ===== */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #2a2a2a;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--color-gold);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--color-accent);
        }

        /* ===== LAYOUT PRINCIPAL ===== */
        .container-app {
            display: flex;
            height: 100vh;
            overflow: hidden;
            gap: 0;
        }

        /* Contenido principal (ocupa todo el espacio disponible) */
        .main-content {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        /* Sidebar del carrito (solo visible en desktop) */
        .sidebar-carrito {
            width: 380px;
            background-color: #252525;
            border-left: 1px solid #404040;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.5);
        }

        /* ===== RESPONSIVO ===== */
        @media (max-width: 1024px) {
            .sidebar-carrito {
                display: none;
            }

            .container-app {
                flex-direction: column;
            }

            .main-content {
                padding: 15px;
            }
        }

        /* ===== ANIMACIONES ===== */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

        /* ===== UTILIDADES ===== */
        .text-gold {
            color: var(--color-gold);
        }

        .bg-gold {
            background-color: var(--color-gold);
        }

        .border-gold {
            border-color: var(--color-gold);
        }

        .hover-gold:hover {
            color: var(--color-gold);
        }

        /* ===== TRANSICIONES ===== */
        button, a, input, select, textarea {
            transition: all 0.3s ease;
        }

        /* ===== OCULTAR/MOSTRAR ===== */
        .hidden {
            display: none !important;
        }

        .visible {
            display: block !important;
        }
    </style>

    <!-- Stack para CSS adicional de cada vista -->
    @stack('css')
</head>

<body>
    <!-- CONTENEDOR PRINCIPAL DEL APP -->
    <div class="container-app">
        
        <!-- CONTENIDO PRINCIPAL (Menú, carrito, etc.) -->
        <div class="main-content">
            @yield('content')
        </div>

        <!-- SIDEBAR DEL CARRITO (Derecha en desktop) -->
        <div class="sidebar-carrito" id="cartSidebar">
            @include('cliente.partials.carrito-sidebar')
        </div>

    </div>

    <!-- Stack para JavaScript adicional de cada vista -->
    @stack('scripts')
</body>

</html>