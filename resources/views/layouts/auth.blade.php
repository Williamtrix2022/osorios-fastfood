<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'OsoriosFoodApp')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Reset básico */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Fondo con degradado suave */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Efecto de círculos decorativos en el fondo */
        body::before,
        body::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(212, 175, 55, 0.05);
            z-index: 0;
        }

        body::before {
            width: 500px;
            height: 500px;
            top: -250px;
            right: -250px;
        }

        body::after {
            width: 400px;
            height: 400px;
            bottom: -200px;
            left: -200px;
        }

        /* Contenedor principal mejorado */
        .auth-container {
            width: 100%;
            max-width: 440px;
            background: rgba(30, 41, 59, 0.8);
            border-radius: 24px;
            padding: 45px 35px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(212, 175, 55, 0.15);
            backdrop-filter: blur(10px);
            position: relative;
            z-index: 1;
            animation: slideInUp 0.5s ease-out;
        }

        /* Animación de entrada */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Logo y título */
        .logo {
            text-align: center;
            margin-bottom: 35px;
        }

        .logo i {
            font-size: 55px;
            color: #d4af37;
            margin-bottom: 12px;
            display: block;
        }

        .logo h1 {
            font-size: 28px;
            font-weight: 700;
            color: white;
            margin-bottom: 6px;
            letter-spacing: -0.5px;
        }

        .logo p {
            color: #94a3b8;
            font-size: 14px;
        }

        /* Grupos de formulario */
        .form-group {
            margin-bottom: 20px;
        }

        /* Labels mejorados */
        .form-label {
            display: flex;
            align-items: center;
            color: #e2e8f0;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-label i {
            margin-right: 8px;
            color: #d4af37;
            font-size: 14px;
        }

        /* Inputs mejorados con transiciones suaves */
        .form-input {
            width: 100%;
            padding: 13px 16px;
            background: rgba(51, 65, 85, 0.6);
            border: 2px solid rgba(71, 85, 105, 0.4);
            border-radius: 12px;
            color: white;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #d4af37;
            background: rgba(51, 65, 85, 0.9);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
            transform: translateY(-1px);
        }

        .form-input::placeholder {
            color: #94a3b8;
        }

        /* Mensajes de error */
        .error-message {
            color: #f87171;
            font-size: 12px;
            margin-top: 6px;
            display: block;
            animation: shake 0.3s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        /* Botón principal mejorado */
        .btn-primary {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #d4af37 0%, #c49930 100%);
            color: #1e293b;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 8px;
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(212, 175, 55, 0.3);
            background: linear-gradient(135deg, #e0bc47 0%, #d4af37 100%);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-primary i {
            margin-right: 8px;
        }

        /* Divisor */
        .divider {
            display: flex;
            align-items: center;
            margin: 25px 0;
            color: #64748b;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(71, 85, 105, 0.5);
        }

        .divider span {
            padding: 0 12px;
            font-size: 13px;
            color: #94a3b8;
        }

        /* Enlaces */
        .link {
            color: #d4af37;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .link:hover {
            color: #e0bc47;
            text-decoration: underline;
        }

        /* Texto centrado */
        .text-center {
            text-align: center;
            color: #94a3b8;
            font-size: 14px;
            margin-top: 20px;
        }

        /* Grupo de checkbox */
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .checkbox-group input[type="checkbox"] {
            width: 17px;
            height: 17px;
            border-radius: 4px;
            cursor: pointer;
            accent-color: #d4af37;
        }

        .checkbox-group label {
            color: #cbd5e1;
            font-size: 14px;
            cursor: pointer;
            user-select: none;
        }

        /* Mensajes de estado/éxito */
        .status-message {
            background: rgba(34, 197, 94, 0.15);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #86efac;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            animation: slideInUp 0.4s ease;
        }

        /* Responsive mejorado */
        @media (max-width: 480px) {
            .auth-container {
                padding: 35px 25px;
            }

            .logo h1 {
                font-size: 24px;
            }

            .logo i {
                font-size: 48px;
            }

            .form-input {
                font-size: 16px; /* Evita zoom en iOS */
            }

            body::before,
            body::after {
                width: 300px;
                height: 300px;
            }
        }

        /* Pequeña animación para hacer el sitio más vivo */
        @media (prefers-reduced-motion: no-preference) {
            .logo i {
                animation: floatIcon 3s ease-in-out infinite;
            }

            @keyframes floatIcon {
                0%, 100% {
                    transform: translateY(0px);
                }
                50% {
                    transform: translateY(-8px);
                }
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        @yield('content')
    </div>
</body>
</html>