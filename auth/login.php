<?php
$logDir = 'src/pages/admin/logs';
$logFile = $logDir . '/admin.log';

if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}

function logAlerta($mensaje) {
    global $logFile;
    $fecha = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'];
    $linea = "[$fecha][$ip] ALERTA: $mensaje" . PHP_EOL;
    file_put_contents($logFile, $linea, FILE_APPEND);
}
if (!isset($_SESSION['login_fails'])) {
    $_SESSION['login_fails'] = [];
}

$ip = $_SERVER['REMOTE_ADDR'];

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
            // Fallo por contraseña incorrecta
            $_SESSION['login_fails'][$ip] = ($_SESSION['login_fails'][$ip] ?? 0) + 1;
            if ($_SESSION['login_fails'][$ip] >= 5) {
                logAlerta("Múltiples intentos fallidos de login desde IP $ip para el usuario $email");
            }

            header("Location: /login?error=contraseña");
            exit;
        }
    } else {
        $_SESSION['login_fails'][$ip] = ($_SESSION['login_fails'][$ip] ?? 0) + 1;
        if ($_SESSION['login_fails'][$ip] >= 5) {
            logAlerta("Múltiples intentos fallidos de login desde IP $ip con email inexistente: $email");
        }
        header("Location: /login?error=usuario");
        exit;
    }
}

?>
