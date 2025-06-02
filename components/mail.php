<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

/**
 * Envía un correo electrónico con formato HTML
 *
 * @param string $to Correo del destinatario
 * @param string $subject Asunto del correo
 * @param string $body Contenido HTML del mensaje
 * @return bool true si se envió correctamente, false si falló
 */
function enviarEmail($to, $subject, $body, $embeddedImagePath = null, $embeddedCid = 'qrimage') {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->CharSet    = 'UTF-8';
        $mail->Host       = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['MAIL_USERNAME'];
        $mail->Password   = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION'] === 'ssl'
                            ? PHPMailer::ENCRYPTION_SMTPS
                            : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $_ENV['MAIL_PORT'];
        $mail->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_FROM_NAME']);
        $mail->addAddress($to);

        if ($embeddedImagePath && file_exists($embeddedImagePath)) {
            $mail->addEmbeddedImage($embeddedImagePath, $embeddedCid, 'qr.png', 'base64', 'image/png');
        }

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = strip_tags($body);

        $mail->send();
        return true;
    } catch (Exception $e) {
        throw new Exception("Mailer Error: " . $mail->ErrorInfo);
    }
}

