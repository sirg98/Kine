<?php
require_once __DIR__ . '/../../../../components/db.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cita_id']) && isset($_SESSION['id'])) {
    $cita_id = intval($_POST['cita_id']);
    $paciente_id = $_SESSION['id'];

    $stmt = $conn->prepare("UPDATE citas SET estado = 'cancelada' WHERE id = ? AND paciente_id = ? AND estado = 'pendiente'");
    $stmt->bind_param('ii', $cita_id, $paciente_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo cancelar. ¿Ya estaba cancelada?']);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Parámetros inválidos']);
