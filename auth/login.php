<?php
// Handle login POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
    $email = trim($_POST['email']);
    $pass = trim($_POST['password']);

    // Escapar el email para seguridad
    $email = mysqli_real_escape_string($conn, $email);
    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($pass, $row['contraseña'])) {
            // Si la cuenta estaba marcada como eliminada, revertirlo
            if ($row['eliminado'] == 1) {
                $id = $row['id'];
                mysqli_query($conn, "UPDATE usuarios SET eliminado = 0, fecha_eliminacion = NULL WHERE id = $id");
            }

            // Guardar datos en sesión
            $_SESSION['nombre'] = $row['nombre'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['tipo'] = $row['tipo'];
            $_SESSION['id'] = $row['id'];

            // Redirigir según tipo
            switch ($row['tipo']) {
                case 'admin':
                    header("Location: /admin");
                    exit;
                case 'paciente':
                    header("Location: /paciente");
                    exit;
                case 'terapeuta':
                    header("Location: /terapeuta");
                    exit;
                default:
                    header("Location: /login?error=tipo-desconocido");
                    exit;
            }
        } else {
            header("Location: /login?error=contraseña");
            exit;
        }
    } else {
        header("Location: /login?error=usuario");
        exit;
    }
}

?>
