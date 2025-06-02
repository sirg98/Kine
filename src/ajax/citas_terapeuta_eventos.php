<?php
session_start();
include '../../components/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'terapeuta') {
    echo json_encode([]);
    exit;
}
$terapeuta_id = $_SESSION['id'];

// Obtener todas las citas del terapeuta
$sql = "SELECT c.id, c.fecha, c.motivo, c.tratamiento_id, u.nombre AS paciente_nombre, u.apellidos AS paciente_apellidos, t.nombre AS tratamiento
        FROM citas c
        JOIN usuarios u ON c.paciente_id = u.id
        LEFT JOIN tratamientos t ON c.tratamiento_id = t.id
        WHERE c.terapeuta_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $terapeuta_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$eventos = [];
while ($row = mysqli_fetch_assoc($result)) {
    $start = $row['fecha'];
    // Suponemos que cada cita dura 30 minutos
    $end = date('Y-m-d\TH:i:s', strtotime($row['fecha'] . ' +30 minutes'));
    $eventos[] = [
        'id' => $row['id'],
        'title' => $row['paciente_apellidos'] . ', ' . $row['paciente_nombre'],
        'start' => $start,
        'end' => $end,
        'extendedProps' => [
            'motivo' => $row['motivo'],
            'tratamiento' => $row['tratamiento']
        ]
    ];
}
echo json_encode($eventos); 