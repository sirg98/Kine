<?php
require '../../../../components/db.php';

$id = intval($_POST['id'] ?? 0);
$nuevoEstado = $_POST['estado'] ?? '';
$nuevaFecha = $_POST['fecha'] ?? '';

$estadosPermitidos = ['pendiente', 'completada', 'cancelada'];

if ($id > 0) {
    // Obtener los valores actuales
    $consultaActual = mysqli_prepare($conn, "SELECT estado, fecha FROM citas WHERE id = ?");
    mysqli_stmt_bind_param($consultaActual, 'i', $id);
    mysqli_stmt_execute($consultaActual);
    mysqli_stmt_bind_result($consultaActual, $estadoActual, $fechaActual);
    mysqli_stmt_fetch($consultaActual);
    mysqli_stmt_close($consultaActual);

    // Usar los valores actuales si no se envían nuevos
    if (!in_array($nuevoEstado, $estadosPermitidos)) {
        $nuevoEstado = $estadoActual;
    }

    if (!$nuevaFecha) {
        $nuevaFecha = $fechaActual;
    }

    // Actualizar con los valores definitivos
    $sql = "UPDATE citas SET estado = ?, fecha = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ssi', $nuevoEstado, $nuevaFecha, $id);
    $ok = mysqli_stmt_execute($stmt);

    echo json_encode(['success' => $ok]);
} else {
    echo json_encode(['success' => false, 'error' => 'ID inválido']);
}
