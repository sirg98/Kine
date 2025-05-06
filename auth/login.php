<?php
// Handle login POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
    $email = trim($_POST['email']);
    $pass = trim($_POST['password']);

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
                    header("Location: /admin");
                    exit();
                case 'paciente':
                    header("Location: /paciente");
                    exit();
                case 'doctor':
                    header("Location: /doctor");
                    exit();
                default:
                    $error = "Error: Tipo de usuario no reconocido.";
            }
        } else {
            // Contraseña incorrecta
            $error = "Contraseña incorrecta.";
        }
    } else {
        // Usuario no encontrado
        $error = "Usuario no encontrado.";
    }
}
?>