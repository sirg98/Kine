<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "rootroot";
$dbname = "kine";
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

$email = trim($_REQUEST['email']);
$pass = trim($_REQUEST['contraseña']);

// Consulta sin stmt
$email = mysqli_real_escape_string($conn, $email);
$sql = "SELECT * FROM usuarios WHERE email = '$email'";
$result = mysqli_query($conn, $sql);

if ($row = mysqli_fetch_assoc($result)) {
    // Verificar si la contraseña proporcionada coincide con la almacenada (hash)
    if (password_verify($pass, $row['contraseña'])) {
        // La contraseña es correcta
        $_SESSION['nombre'] = $row['nombre'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['tipo'] = $row['tipo'];
        $_SESSION['id'] = $row['id'];

        // Redirección según el tipo de usuario
        switch ($row['tipo']) {
            case 'admin':
                header("Location: ./admin/index.php");
                exit();
            case 'paciente':
                header("Location: ./paciente/index.php");
                exit();
            case 'doctor':
                header("Location: ./doctor/index.php");
                exit();
            default:
                die("Error: Tipo de usuario no reconocido.");
        }
    } else {
        // Contraseña incorrecta
        $error_message = "Contraseña incorrecta.";
    }
} else {
    // Usuario no encontrado
    $error_message = "Usuario no encontrado.";
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuario No Encontrado</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: url('img/fondo.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #FFFFFF;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        /* Añadimos un overlay oscuro sobre la imagen de fondo */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5); /* Fondo oscuro con opacidad */
            z-index: -1; /* Asegura que esté detrás del contenido */
        }

        .message-container {
            text-align: center;
            background-color: rgba(29, 29, 29, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
        }

        .error-message {
            font-size: 1.5rem;
            color: #FF5722;
            margin-bottom: 20px;
        }

        .back-button {
            display: inline-block;
            padding: 12px 20px;
            color: #FFFFFF;
            background-color: #2a5329;
            text-decoration: none;
            font-size: 1.2rem;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.2s;
        }

        .back-button:hover {
            background-color: #53a252;
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<div class="message-container">
    <p class="error-message"><?php echo isset($error_message) ? $error_message : "Ha ocurrido un error."; ?></p>
    <a href="login.php" class="back-button">Volver al Login</a>
</div>

</body>
</html>
