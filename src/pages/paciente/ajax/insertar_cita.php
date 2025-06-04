<?php
session_start();
require_once __DIR__ . '/../../../../components/mail.php';
require_once __DIR__ . '/../../../../components/qr.php';
require_once __DIR__ . '/../../../../components/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'No has iniciado sesión.']);
    exit;
}

$paciente_id = $_SESSION['id'];
$terapeuta_id = $_POST['terapeuta_id'] ?? null;
$tratamiento_id = $_POST['tratamiento_id'] ?? null;
$fecha = $_POST['fecha'] ?? null;
$hora = $_POST['hora'] ?? null;
$motivo = $_POST['motivo'] ?? '';

if (!$terapeuta_id || !$tratamiento_id || !$fecha || !$hora) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos.']);
    exit;
}

$fecha_completa = "$fecha $hora:00";
$stmt = $conn->prepare("INSERT INTO citas (paciente_id, terapeuta_id, tratamiento_id, fecha, motivo, estado) VALUES (?, ?, ?, ?, ?, 'pendiente')");
$stmt->bind_param("iiiss", $paciente_id, $terapeuta_id, $tratamiento_id, $fecha_completa, $motivo);

if ($stmt->execute()) {
    $cita_id = $conn->insert_id;
    $url = "https://reflexiokine.es/cita.php?id=$cita_id";
    $qr_binary = generateQRBinary($url);
    $qr_code = 'data:image/png;base64,' . base64_encode($qr_binary);

    ob_start();
    ?>
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md border-card bg-card text-kinetic-900 dark:bg-gray-800 scale-95 transition-transform duration-300">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-kinetic-900 dark:text-white mt-4">¡Tu Código QR!</h3>
            <div class="mt-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">Presenta este código QR en tu cita:</p>
                <div class="mt-2">
                    <img src="<?= $qr_code ?>" alt="QR Code" class="mx-auto" />
                </div>
            </div>
            <div class="mt-6 space-y-3">
                <button onclick="enviarQRPorCorreoDesdeProxima(<?= $cita_id ?>)"
                        class="w-full px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-600">
                    Enviar por correo
                </button>
                <button onclick="closeSuccessPanel()"
                        class="w-full px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-600">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
    <?php
    $html = ob_get_clean();
    echo json_encode(['success' => true, 'html' => $html]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al registrar la cita: ' . $conn->error]);
}
?>
