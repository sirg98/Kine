<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="icon" type="image/jpeg" href="img/favicon.jpg">
    
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes buttonHover {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        body {
            font-family: 'Arial', sans-serif;
            background: url('./img/fondo.jpg') no-repeat center fixed;
            background-size: cover;
            position: relative;
            height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: #fff;
        }

        /* Capa oscura sobre la imagen de fondo */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .container {
            position: relative;
            z-index: 2;
            max-width: 600px;
            padding: 20px;
            opacity: 0;
            animation: fadeIn 1s ease-out forwards;
            animation-delay: 0.5s;
        }

        .container h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            opacity: 0;
            animation: fadeIn 1s ease-out forwards;
            animation-delay: 0.7s;
        }

        .container p {
            font-size: 1.2rem;
            margin-bottom: 20px;
            opacity: 0;
            animation: fadeIn 1s ease-out forwards;
            animation-delay: 0.9s;
        }

        .auth-buttons {
            text-align: center;
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .auth-buttons button {
            background-color: #2a5329;
            color: #fff;
            font-size: 1.2rem;
            border: none;
            padding: 15px;
            cursor: pointer;
            margin: 5px;
            border-radius: 5px;
            transition: all 0.3s ease;
            opacity: 0;
            animation: fadeIn 1s ease-out forwards, buttonHover 2s ease-in-out infinite;
            animation-delay: 1.1s;
        }

        .auth-buttons button:hover {
            background-color: #53a252;
            transform: scale(1.05);
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
        }

        /* Hover effect for buttons */
        .auth-buttons button:nth-child(1) {
            animation-delay: 1.1s;
        }

        .auth-buttons button:nth-child(2) {
            animation-delay: 1.3s;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenido a nuestra clínica</h1>
        <p>Descubre un nuevo enfoque en la medicina alternativa y naturista. Nuestro equipo de especialistas está aquí para ayudarte a encontrar el equilibrio y bienestar que necesitas.</p>
        
        <div class="auth-buttons">
            <button onclick="location.href='login.php'">Iniciar Sesión</button>
            <button onclick="location.href='register.php'">Regístrate</button>
        </div>
    </div>
</body>
</html>
