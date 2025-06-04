<?php
include '../components/mail.php';
include '../components/db.php';

header('Content-Type: application/json');

$email = $_POST['email'] ?? '';
$email = trim($email);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Correo inválido']);
    exit;
}

$sql = "SELECT id, nombre FROM usuarios WHERE email = '$email' LIMIT 1";
$res = mysqli_query($conn, $sql);

if (!$res || mysqli_num_rows($res) === 0) {
    echo json_encode(['success' => false, 'message' => 'Correo no registrado']);
    exit;
}

$user = mysqli_fetch_assoc($res);
$token = bin2hex(random_bytes(20));
$url = "https://reflexiokine.es/restablecer?token=$token";

// Guardar token en BD (debes tener columna 'token_recuperacion')
mysqli_query($conn, "UPDATE usuarios SET token_recuperacion = '$token' WHERE id = {$user['id']}");

// Enviar correo
$subject = "Recuperación de contraseña";
$body = "
    <h2 style='color: #0c4a6e;'>🔐 Solicitud para restablecer tu contraseña</h2>
    <p>Hola <strong>{$user['nombre']}</strong>,</p>

    <p>Hemos recibido una solicitud para restablecer tu contraseña en <strong>ReflexioKineTP</strong>.</p>

    <p>👉 Para continuar, haz clic en el siguiente enlace:</p>
    <p>
        <a href='$url' target='_blank' style='color: #1d4ed8; text-decoration: underline; font-weight: bold;'>Restablecer mi contraseña</a>
    </p>
    <p style='margin-top: 10px; font-size: 14px; color: #555;'>
        Si el botón anterior no funciona, copia y pega este enlace en tu navegador:<br>
        <span style='color:#1e40af;'>$url</span>
    </p>

    <p style='margin-top: 24px; font-size: 14px; color: #555;'>
        Si no solicitaste este cambio, puedes ignorar este correo. Tu contraseña actual seguirá siendo válida.
    </p>

    <p style='margin-top: 40px;'>Gracias por confiar en nosotros.<br><strong>El equipo de ReflexioKineTP</strong></p>
";

enviarEmail($email, $subject, $body);

echo json_encode(['success' => true, 'message' => '📧 Se ha enviado un enlace de recuperación a tu correo']);
