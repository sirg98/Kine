<?php
require __DIR__ . '/../components/db.php';

$logDir = __DIR__ . '/../logs';
$logFile = $logDir . '/purgar_cuentas.log';

// Asegúrate de que el directorio exista
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}

$timestamp = date('[Y-m-d H:i:s]');

$sql = "DELETE FROM usuarios WHERE eliminado = 1 AND fecha_eliminacion <= NOW()";
$result = mysqli_query($conn, $sql);

if ($result) {
    $msg = "$timestamp Usuarios eliminados permanentemente: " . mysqli_affected_rows($conn) . PHP_EOL;
} else {
    $msg = "$timestamp Error al purgar cuentas: " . mysqli_error($conn) . PHP_EOL;
}

file_put_contents($logFile, $msg, FILE_APPEND);
