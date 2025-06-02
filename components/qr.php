<?php
require __DIR__ . '/../vendor/autoload.php';

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

function generateQRBinary($url) {
    $options = new QROptions([
        'version' => 5,
        'outputType' => QRCode::OUTPUT_IMAGE_PNG,
        'imageBase64' => false,
    ]);

    return (new QRCode($options))->render($url);
}

function generateQR($url) {
    $options = new QROptions([
        'version' => 5,
        'outputType' => QRCode::OUTPUT_IMAGE_PNG,

    ]);

    return (new QRCode($options))->render($url);
}

?>
