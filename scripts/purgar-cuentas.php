<?php
require __DIR__ . '/../components/db.php';

$logDir = __DIR__ . '/../logs';
$logFile = $logDir . '/purga.log';

// Crear directorio de logs si no existe
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}

$timestamp = date('[Y-m-d H:i:s]');

$sql = "DELETE FROM usuarios WHERE eliminado = 1 AND fecha_eliminacion <= NOW()";
$result = mysqli_query($conn, $sql);

if ($result) {
    $filas = mysqli_affected_rows($conn);
    $msg = "$timestamp Usuarios eliminados permanentemente: $filas" . PHP_EOL;
    echo "✅ $filas usuario(s) eliminados permanentemente.";
} else {
    $msg = "$timestamp Error al purgar cuentas: " . mysqli_error($conn) . PHP_EOL;
    echo "❌ Error al purgar cuentas. Consulta el log para más detalles.";
}

// Guardar en el log
file_put_contents($logFile, $msg, FILE_APPEND);
?>
