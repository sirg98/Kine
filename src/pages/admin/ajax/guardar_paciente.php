<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../../../components/db.php';
require_once __DIR__ . '/../../../../components/qr.php';
require_once __DIR__ . '/../../../../components/mail.php';

function generarPasswordTemporal($longitud = 10) {
    return bin2hex(random_bytes($longitud / 2));
}

function logError($mensaje) {
    $logDir = __DIR__ . '/../../../../logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    $logFile = $logDir . '/admin.log';
    $timestamp = date('[Y-m-d H:i:s]');
    file_put_contents($logFile, "$timestamp $mensaje" . PHP_EOL, FILE_APPEND);
}

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoge y limpia
    $nombre          = mysqli_real_escape_string($conn, $_POST['nombre'] ?? '');
    $apellidos       = mysqli_real_escape_string($conn, $_POST['apellidos'] ?? '');
    $email           = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $telefono        = mysqli_real_escape_string($conn, $_POST['telefono'] ?? '');
    $ciudad          = mysqli_real_escape_string($conn, $_POST['ciudad'] ?? '');
    $codigo_postal   = mysqli_real_escape_string($conn, $_POST['codigo_postal'] ?? '');
    $fecha_nacimiento= mysqli_real_escape_string($conn, $_POST['fecha_nacimiento'] ?? '');
    $tratamiento_id  = (int) ($_POST['tratamiento_id'] ?? 0);
    $terapeuta_id    = (int) ($_POST['terapeuta_id'] ?? 0);
    $fecha           = mysqli_real_escape_string($conn, $_POST['fecha'] ?? '');
    $hora            = mysqli_real_escape_string($conn, $_POST['hora'] ?? '');
    $motivo          = mysqli_real_escape_string($conn, $_POST['motivo'] ?? '');

    if (
        !$nombre || !$apellidos || !$email || !$telefono || !$ciudad || !$codigo_postal || !$fecha_nacimiento ||
        !$tratamiento_id || !$terapeuta_id || !$fecha || !$hora
    ) {
        $response['message'] = '❌ Todos los campos obligatorios deben estar completos.';
        echo json_encode($response);
        exit;
    }

    $password_temporal = generarPasswordTemporal();
    $password_hash = password_hash($password_temporal, PASSWORD_DEFAULT);

    $sql_usuario = "INSERT INTO usuarios (nombre, apellidos, email, contraseña, fecha_nacimiento,
        telefono, codigo_postal, ciudad, tipo, fecha_creacion)
        VALUES ('$nombre', '$apellidos', '$email', '$password_hash', '$fecha_nacimiento',
        '$telefono', '$codigo_postal', '$ciudad', 'paciente', NOW())";

    if (!mysqli_query($conn, $sql_usuario)) {
        $response['message'] = '❌ Error al registrar el paciente: ' . mysqli_error($conn);
        logError("Error al registrar paciente ($email): " . mysqli_error($conn));
        echo json_encode($response);
        exit;
    }

    $paciente_id = mysqli_insert_id($conn);
    $fecha_hora = "$fecha $hora";

    $sql_cita = "INSERT INTO citas (paciente_id, terapeuta_id, tratamiento_id, fecha, motivo, estado)
                 VALUES ($paciente_id, $terapeuta_id, $tratamiento_id, '$fecha_hora', '$motivo', 'pendiente')";

    if (!mysqli_query($conn, $sql_cita)) {
        $response['message'] = '❌ Error al registrar la cita: ' . mysqli_error($conn);
        logError("Error al registrar cita para paciente $paciente_id: " . mysqli_error($conn));
        echo json_encode($response);
        exit;
    }

    $cita_id = mysqli_insert_id($conn);
    $tratamiento = 'Tratamiento';
    $res_trat = mysqli_query($conn, "SELECT nombre FROM tratamientos WHERE id = $tratamiento_id LIMIT 1");
    if ($res_trat && mysqli_num_rows($res_trat) > 0) {
        $tratamiento = mysqli_fetch_assoc($res_trat)['nombre'];
    }

    $url = "https://reflexiokine.es/cita.php?id={$cita_id}";
    $qr_binary = generateQRBinary($url);
    $tmp_qr_path = tempnam(sys_get_temp_dir(), 'qr_') . '.png';
    file_put_contents($tmp_qr_path, $qr_binary);

    $subject = "Datos de tu cita y acceso a ReflexioKine";
    $enlace = "https://reflexiokine.es/ajustes?tab=contraseña";
    $body = "
        <h2 style='color: #0c4a6e;'>👤 ¡Bienvenido/a a ReflexioKineTP!</h2>
        <p>Hola <strong>$nombre</strong>,</p>

        <p>Hemos creado tu cuenta en <strong>ReflexioKineTP</strong>. Aquí tienes tus credenciales iniciales:</p>
        <ul style='line-height: 1.8;'>
            <li>📧 <strong>Email:</strong> $email</li>
            <li>🔑 <strong>Contraseña temporal:</strong> $password_temporal</li>
        </ul>

        <p>
            👉 Puedes cambiar tu contraseña ahora haciendo clic en este enlace:<br>
            <a href='$enlace' target='_blank' style='color: #1d4ed8; text-decoration: underline;'>Cambiar mi contraseña</a>
        </p>

        <p style='margin-top: 20px;'>🗓️ Además, aquí tienes el código QR para acceder a los detalles de tu cita:</p>
        <img src='cid:qrimage' alt='QR Code' style='max-width: 200px;' />
        <p style='margin-top: 30px;'>Gracias por confiar en nosotros.<br><strong>El equipo de ReflexioKineTP</strong></p>
    ";

    $mail_result = enviarEmail($email, $subject, $body, $tmp_qr_path);
    if ($mail_result !== true) {
        logError("Error al enviar correo a $email: $mail_result");
        $response['message'] = '❌ Error al enviar el correo:';
    } else {
        $response['success'] = true;
        $response['message'] = '✅ Cita registrada correctamente. El paciente ha sido creado y notificado.';
    }

    echo json_encode($response);
    exit;
}
?>
