<?php
include '../components/db.php';
session_start();

$id = $_SESSION['id'] ?? null;
if (!$id) {
    header('Location: /login?error=unauthorized');
    exit;
}

$redirect = $_GET['redirect'] ?? 'ajustes';
$tab = $_GET['tab'] ?? 'general';

$actual = $_POST['actual'] ?? '';
$nueva = $_POST['nueva'] ?? '';
$confirmar = $_POST['confirmar'] ?? '';

if (!$actual || !$nueva || !$confirmar || $nueva !== $confirmar) {
    header("Location: /$redirect?tab=$tab&error=contraseña");
    exit;
}

$result = mysqli_query($conn, "SELECT contraseña FROM usuarios WHERE id = $id");
$data = mysqli_fetch_assoc($result);

if (!password_verify($actual, $data['contraseña'])) {
    header("Location: /$redirect?tab=$tab&error=incorrecta");
    exit;
}

$hash = password_hash($nueva, PASSWORD_DEFAULT);
mysqli_query($conn, "UPDATE usuarios SET contraseña = '$hash' WHERE id = $id");

header("Location: /$redirect?tab=$tab&success=contraseña");
exit;
