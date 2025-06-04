<?php
require_once __DIR__ . '/../../../../components/mail.php';
require_once __DIR__ . '/../../../../components/qr.php';
require_once __DIR__ . '/../../../../components/db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$cita_id = $data['cita_id'] ?? null;

if (!$cita_id) {
    echo json_encode(['success' => false, 'message' => 'ID de cita no proporcionado']);
    exit;
}

// Obtener cita
$sql = "SELECT c.*, u.email, u.nombre, u.apellidos, t.nombre as tratamiento_nombre 
        FROM citas c
        JOIN usuarios u ON c.paciente_id = u.id
        JOIN tratamientos t ON c.tratamiento_id = t.id
        WHERE c.id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $cita_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$cita = mysqli_fetch_assoc($result);

if (!$cita) {
    echo json_encode(['success' => false, 'message' => 'Cita no encontrada']);
    exit;
}

// Generar QR
$url = "https://reflexiokine.es/cita.php?id={$cita_id}";
$qr_binary = generateQRBinary($url);
$tmp_qr_path = tempnam(sys_get_temp_dir(), 'qr_') . '.png';
file_put_contents($tmp_qr_path, $qr_binary);

// Email
$subject = 'Tu código QR para la cita en ReflexioKine';
$body = "
    <h2>¡Hola {$cita['nombre']}!</h2>
    <p>Tu cita ha sido agendada correctamente.</p>
    <p><strong>Detalles de la cita:</strong></p>
    <ul>
        <li>Tratamiento: {$cita['tratamiento_nombre']}</li>
        <li>Fecha: " . date('d/m/Y', strtotime($cita['fecha'])) . "</li>
        <li>Hora: " . date('H:i', strtotime($cita['fecha'])) . "</li>
    </ul>
    <p>Aquí tienes tu código QR para la cita:</p>
    <img src='cid:qrimage' alt='QR Code' style='max-width: 200px;' />
    <p>Por favor, guarda este código QR y preséntalo el día de tu cita.</p>
    <p>Saludos,<br>El equipo de ReflexioKine</p>
";

try {
    $success = enviarEmail($cita['email'], $subject, $body, $tmp_qr_path);
    if ($success === true) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error desconocido al enviar el correo']);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Excepción: ' . $e->getMessage()
    ]);
}
