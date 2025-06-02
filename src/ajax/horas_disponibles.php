<?php
include '../../components/db.php';

$fecha = $_GET['fecha'] ?? '';
$terapeuta_id = $_GET['terapeuta_id'] ?? 0;

if (!$fecha || !$terapeuta_id) {
    echo json_encode([]);
    exit;
}

$ocupadas = [];

$sql = "SELECT DATE_FORMAT(fecha, '%H:%i') as hora FROM citas 
        WHERE DATE(fecha) = '$fecha' AND terapeuta_id = $terapeuta_id AND estado != 'cancelada'";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $ocupadas[] = $row['hora'];
}

echo json_encode($ocupadas);
