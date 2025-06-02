<?php
session_start();
require '../components/db.php';

$isAdmin = ($_SESSION['tipo'] ?? '') === 'admin';
$sessionId = $_SESSION['id'] ?? null;

// Si es admin, permitir pasar el ID por POST
if ($isAdmin && isset($_POST['id']) && is_numeric($_POST['id'])) {
    $id = (int) $_POST['id'];
} else {
    // Si no es admin, solo puede eliminarse a sí mismo
    $id = $sessionId;
}

if (!$id) {
    header('Location: /login?error=unauthorized');
    exit;
}

$fechaLimite = date('Y-m-d H:i:s', strtotime('+30 days'));

mysqli_query($conn, "UPDATE usuarios SET eliminado = 1, fecha_eliminacion = '$fechaLimite' WHERE id = $id");

// Si el usuario se está autoeliminando, cerrar sesión
if (!$isAdmin || $sessionId == $id) {
    session_destroy();
    header('Location: /?success=cuenta_eliminada_temporal');
    exit;
}

// Si lo hace un admin, redirigir a gestión
header('Location: /admin?tab=usuarios&success=eliminado');
exit;
