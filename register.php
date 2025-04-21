<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        body {
            font-family: 'Arial', sans-serif;
            background: url('./img/fondo.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #FFFFFF;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            position: relative;
        }

        /* Dark overlay for background */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);  /* Slightly darker overlay */
            z-index: 1;
        }

        .content {
            position: relative;
            z-index: 2;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            font-size: 2.5rem;
            color: #ffffff;
            text-align: center;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            opacity: 0;
            animation: fadeIn 1s ease-out forwards;
            animation-delay: 0.3s;
        }
        
        form {
            background-color: rgba(29, 29, 29, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            text-align: center;
            opacity: 0;
            animation: fadeIn 1s ease-out forwards;
            animation-delay: 0.5s;
        }

        label {
            font-size: 1rem;
            color: #FFFFFF;
            display: block;
            margin-bottom: 5px;
            text-align: left;
        }

        input[type="text"], 
        input[type="email"], 
        input[type="password"], 
        input[type="date"], 
        input[type="tel"], 
        select {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 20px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #2D2D2D;
            color: #FFFFFF;
            font-size: 1rem;
            box-sizing: border-box;
            transition: all 0.3s ease;
            opacity: 0;
            animation: fadeIn 1s ease-out forwards;
        }

        input[type="text"]:nth-child(1), 
        input[type="text"]:nth-child(2) { animation-delay: 0.6s; }
        input[type="email"] { animation-delay: 0.7s; }
        input[type="password"] { animation-delay: 0.8s; }
        input[type="date"] { animation-delay: 0.9s; }
        input[type="tel"] { animation-delay: 1.0s; }
        input[type="text"]:nth-child(3), 
        input[type="text"]:nth-child(4) { animation-delay: 1.1s; }

        input[type="text"]:focus, 
        input[type="email"]:focus, 
        input[type="password"]:focus, 
        input[type="date"]:focus, 
        input[type="tel"]:focus, 
        select:focus {
            outline: none;
            border-color: #2a5329;
            box-shadow: 0 0 5px rgba(106, 13, 173, 0.5);
        }

        input[type="submit"] {
            background-color: #2a5329; 
            color: #FFFFFF;
            border: none;
            padding: 12px;
            font-size: 1rem;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
            transition: background-color 0.3s, transform 0.2s;
            opacity: 0;
            animation: fadeIn 1s ease-out forwards;
            animation-delay: 1.2s;
        }

        input[type="submit"]:hover {
            background-color: #53a252;
            transform: scale(1.05);
        }

        .main-button {
            background-color: #2a5329;
            color: #fff;
            font-size: 1.2rem;
            text-align: center;
            border: none;
            padding: 12px;
            cursor: pointer;
            width: 100%;
            max-width: 400px;
            margin-top: 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            opacity: 0;
            animation: fadeIn 1s ease-out forwards;
            animation-delay: 1.3s;
        }

        .main-button:hover {
            background-color: #53a252;
        }

        .form-row {
            display: flex;
            gap: 10px;
        }

        .form-row > * {
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="content">
        <form action="register2.php" method="post">
            <h1>Registro de Usuario</h1>
            
            <div class="form-row">
                <input type="text" name="nombre" id="nombre" placeholder="Nombre" required>
                <input type="text" name="apellido" id="apellido" placeholder="Apellido" required>
            </div>

            <input type="email" name="email" id="email" placeholder="Correo Electrónico" required>

            <input type="password" name="contraseña" id="contraseña" placeholder="Contraseña" required>

            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento">

            <input type="tel" name="telefono" id="telefono" placeholder="Teléfono">

            <div class="form-row">
                <input type="text" name="codigo_postal" id="codigo_postal" placeholder="Código Postal">
                <input type="text" name="ciudad" id="ciudad" placeholder="Ciudad">
            </div>

            <input type="submit" value="Registrarse">
        </form>

        <a href="index.php" class="main-button">Volver al Inicio</a>
    </div>
</body>
</html>