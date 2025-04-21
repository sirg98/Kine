<?php
// components/send_mail.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

function send_mail($to, $subject, $body, $from = 'info@kinesiologia.com', $fromName = 'Clínica Kinesiología') {
    $mail = new PHPMailer(true);
    try {
        // Configuración SMTP para Gmail
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tu_gmail@gmail.com'; // Cambia por tu Gmail
        $mail->Password = 'tu_contraseña_de_aplicacion'; // Usa una contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom($from, $fromName);
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return 'Mailer Error: ' . $mail->ErrorInfo;
    }
}
