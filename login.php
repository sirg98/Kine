<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" type="image/jpeg" href="img/favicon.jpg">

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

        h2 {
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

        input[type="text"], 
        input[type="password"] {
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

        input[type="text"] { animation-delay: 0.7s; }
        input[type="password"] { animation-delay: 0.8s; }

        input[type="text"]:focus, 
        input[type="password"]:focus {
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
            animation-delay: 1.0s;
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
            animation-delay: 1.1s;
        }

        .main-button:hover {
            background-color: #53a252;
        }
    </style>
</head>
<body>
    <div class="content">
        <form action="login2.php" method="post">
            <h2>Iniciar sesión</h2>
            <input type="text" name="email" id="email" placeholder="Correo Electrónico" required>

            <input type="password" name="contraseña" id="contraseña" placeholder="Contraseña" required>
    
            <input type="submit" value="Login">
        </form>
    
        <a href="index.php" class="main-button">Volver al inicio</a>
    </div>
</body>
</html>