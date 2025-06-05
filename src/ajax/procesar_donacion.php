<?php 
require __DIR__ . '/../../components/db.php';
require __DIR__ . '/../../components/mail.php';

header('Content-Type: application/json');

$email = trim($_POST['email'] ?? '');
$cantidad = floatval($_POST['cantidad'] ?? 0);
$newsletter = isset($_POST['newsletter']) ? 1 : 0;

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $cantidad <= 0) {
    echo json_encode(['success' => false, 'message' => 'Email o cantidad no válidos']);
    exit;
}

$subject = "🙏 Gracias por tu donación a ReflexioKineTP";

$body = "
    <h2 style='color: #0c4a6e;'>¡Gracias por tu apoyo!</h2>
    <p>Hola,</p>
    <p>Hemos recibido tu donación de <strong>$cantidad €</strong>. Gracias por ayudarnos a continuar ofreciendo tratamientos personalizados y mejorar la salud de muchas personas.</p>
    <p style='margin-top: 20px;'>Tu generosidad marca la diferencia. 💙</p>
    <p>— El equipo de <strong>ReflexioKineTP</strong></p>
";

$correoOk = enviarEmail($email, $subject, $body);

if ($correoOk === true) {
    $stmt = mysqli_prepare($conn, "INSERT INTO pagos (email, cantidad, pagado, fecha_pago, newsletter) VALUES (?, ?, 1, NOW(), ?)");
    mysqli_stmt_bind_param($stmt, 'sdi', $email, $cantidad, $newsletter);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($ok) {
        echo json_encode(['success' => true, 'message' => '✅ Donación procesada con éxito. ¡Gracias!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al guardar en la base de datos']);
    }
} else {
    echo json_encode(['success' => false, 'message' => '❌ Error al enviar el correo: ' . $correoOk]);
}
