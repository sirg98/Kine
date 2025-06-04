<?php
require_once __DIR__ . '/../../../../components/db.php';
session_start();
header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);
$clave = $input['clave'] ?? '';
$valido = false;

// Verificar sesión y tipo
if (isset($_SESSION['id'], $_SESSION['tipo']) && $_SESSION['tipo'] === 'terapeuta' && $clave) {
    $id = intval($_SESSION['id']);
    $res = mysqli_query($conn, "SELECT contraseña FROM usuarios WHERE id = $id AND tipo = 'terapeuta'");

    if ($fila = mysqli_fetch_assoc($res)) {
        $valido = password_verify($clave, $fila['contraseña']);
    }
}

echo json_encode(['valido' => $valido]);
