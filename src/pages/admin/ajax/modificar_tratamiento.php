<?php
require '../../../../components/db.php';

$id = intval($_POST['id'] ?? 0);
if ($id <= 0) {
    echo json_encode(['success' => false, 'error' => 'ID inválido']);
    exit;
}

// Obtener los valores actuales del tratamiento
$consulta = mysqli_prepare($conn, "SELECT nombre, descripcion, duracion, precio, icon, beneficios, imagen FROM tratamientos WHERE id = ?");
mysqli_stmt_bind_param($consulta, 'i', $id);
mysqli_stmt_execute($consulta);
mysqli_stmt_bind_result($consulta, $nombreActual, $descripcionActual, $duracionActual, $precioActual, $iconActual, $beneficiosActual, $imagenActual);
mysqli_stmt_fetch($consulta);
mysqli_stmt_close($consulta);

// Obtener valores nuevos o usar los actuales si están vacíos
$nombre = trim($_POST['nombre'] ?? '') ?: $nombreActual;
$descripcion = trim($_POST['descripcion'] ?? '') ?: $descripcionActual;
$duracion = intval($_POST['duracion'] ?? 0) ?: $duracionActual;
$precio = floatval($_POST['precio'] ?? 0) ?: $precioActual;
$icon = trim($_POST['icon'] ?? '') ?: $iconActual;
$beneficios = trim($_POST['beneficios'] ?? '') ?: $beneficiosActual;
$imagen = trim($_POST['imagen'] ?? '') ?: $imagenActual;

// Si beneficios no es JSON válido, convertirlo desde texto plano
if (json_decode($beneficios) === null) {
    $beneficiosArray = explode(PHP_EOL, str_replace(',', PHP_EOL, $beneficios));
    $beneficiosArray = array_map('trim', $beneficiosArray);
    $beneficiosArray = array_filter($beneficiosArray);
    $beneficios = json_encode($beneficiosArray, JSON_UNESCAPED_UNICODE);
}

$sql = "UPDATE tratamientos 
        SET nombre = ?, descripcion = ?, duracion = ?, precio = ?, icon = ?, beneficios = ?, imagen = ? 
        WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'ssidsssi', $nombre, $descripcion, $duracion, $precio, $icon, $beneficios, $imagen, $id);
$ok = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

echo json_encode(['success' => $ok]);
?>
