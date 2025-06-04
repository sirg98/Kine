<?php
require '../../../../components/db.php';

header('Content-Type: application/json');
function logError($mensaje) {
    $logDir = __DIR__ . '/../../../../logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    $logFile = $logDir . '/admin.log';
    $timestamp = date('[Y-m-d H:i:s]');
    file_put_contents($logFile, "$timestamp $mensaje" . PHP_EOL, FILE_APPEND);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Método no permitido.']);
    exit;
}

if (!isset($_POST['id']) || !isset($_POST['password'])) {
    echo json_encode(['success' => false, 'error' => 'Faltan parámetros: id y/o password.']);
    exit;
}

$id = intval($_POST['id']);
$password = $_POST['password'];

// Verificar que existe el informe y obtener el hash de la contraseña del terapeuta
$stmt = $conn->prepare("
    SELECT u.contraseña 
    FROM informes i 
    JOIN usuarios u ON i.terapeuta_id = u.id 
    WHERE i.id = ?
");
if (!$stmt) {
    logError("Error al preparar consulta de verificación (informe_id=$id): " . $conn->error);
    echo json_encode(['success' => false, 'error' => 'Error al preparar la consulta']);
    exit;
}

$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($hashedPassword);

if ($stmt->fetch()) {
    $stmt->close();

    if (!password_verify($password, $hashedPassword)) {
        echo json_encode(['success' => false, 'error' => 'Contraseña incorrecta.']);
        $conn->close();
        exit;
    }

    // Eliminar el informe
    $deleteStmt = $conn->prepare("DELETE FROM informes WHERE id = ?");
    if (!$deleteStmt) {
        logError("Error al preparar eliminación de informe (id=$id): " . $conn->error);
        echo json_encode(['success' => false, 'error' => 'Error al preparar la eliminación']);
        exit;
    }

    $deleteStmt->bind_param("i", $id);
    $success = $deleteStmt->execute();
    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        logError("Error al ejecutar eliminación de informe (id=$id): " . $deleteStmt->error);
        echo json_encode(['success' => false, 'error' => 'Error al eliminar el informe.']);
    }
    $deleteStmt->close();
} else {
    logError("Informe no encontrado para eliminación (id=$id).");
    echo json_encode(['success' => false, 'error' => 'Informe no encontrado.']);
}

$conn->close();
?>
