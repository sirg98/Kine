<?php
include '../components/db.php';
session_start();

$id = $_POST['id'] ?? null;
$nombre = trim($_POST['nombre'] ?? '');
$apellidos = trim($_POST['apellidos'] ?? '');
$email = trim($_POST['email'] ?? '');
$fecha = trim($_POST['fecha_nacimiento'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$cp = trim($_POST['codigo_postal'] ?? '');
$ciudad = trim($_POST['ciudad'] ?? '');

if ($id && $nombre && $email) {
    $query = "UPDATE usuarios SET 
        nombre = '$nombre',
        apellidos = '$apellidos',
        email = '$email',
        fecha_nacimiento = '$fecha',
        telefono = '$telefono',
        codigo_postal = '$cp',
        ciudad = '$ciudad'
        WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        $_SESSION['nombre'] = $nombre;
        $_SESSION['email'] = $email;
        header('Location: /ajustes?tab=perfil&success=perfil');
        exit;
    }
}

header('Location: /ajustes?tab=perfil&error=perfil');
exit;
