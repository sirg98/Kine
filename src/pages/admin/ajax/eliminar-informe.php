<?php
require '../../../../components/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Método no permitido. Se requiere POST.']);
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
    echo json_encode(['success' => false, 'error' => 'Error al preparar la consulta: ' . $conn->error]);
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
        echo json_encode(['success' => false, 'error' => 'Error al preparar la eliminación: ' . $conn->error]);
        exit;
    }

    $deleteStmt->bind_param("i", $id);
    $success = $deleteStmt->execute();
    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al eliminar el informe.']);
    }
    $deleteStmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Informe no encontrado.']);
}

$conn->close();
?>
