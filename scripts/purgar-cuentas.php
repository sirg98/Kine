<?php
require __DIR__ . '/../components/db.php';

$logDir = '../src/pages/admin/logs';
$logFile = $logDir . '/purga.log';

// Crear directorio de logs si no existe
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}

$timestamp = date('[Y-m-d H:i:s]');

// Mostrar usuarios pendientes de eliminar
$pendientes_sql = "SELECT COUNT(*) AS total FROM usuarios WHERE eliminado = 1 AND fecha_eliminacion > NOW()";
$pendientes_result = mysqli_query($conn, $pendientes_sql);
$pendientes = mysqli_fetch_assoc($pendientes_result)['total'] ?? 0;

// Ejecutar purga
$sql = "DELETE FROM usuarios WHERE eliminado = 1 AND fecha_eliminacion <= NOW()";
$result = mysqli_query($conn, $sql);

if ($result) {
    $filas = mysqli_affected_rows($conn);
    $msg = "$timestamp Usuario(s) eliminados permanentemente: $filas, usuarios pendientes de eliminar en el futuro: $pendientes" . PHP_EOL;
} else {
    $msg = "$timestamp ERROR al purgar cuentas: " . mysqli_error($conn) . PHP_EOL;
}

// Guardar en el log
file_put_contents($logFile, $msg, FILE_APPEND);
?>
