<?php
$host = 'localhost';
$db   = 'kine';
$user = 'root';
$pass = '';

// Deshabilitar excepciones de mysqli para manejar errores manualmente
mysqli_report(MYSQLI_REPORT_OFF);

// Crear conexión con mysqli
$conn = mysqli_connect($host, $user, $pass, $db);

// Verificar conexión
if (!$conn) {
    // Mostrar una página de error simple si la conexión falla
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <title>Error de Conexión</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                text-align: center;
                margin-top: 50px;
            }
            h1 {
                color: red;
            }
        </style>
    </head>
    <body>
        <h1>Error de Conexión a la Base de Datos</h1>
        <p>Por favor, verifica la configuración de la base de datos.</p>
    </body>
    </html>";
    exit;
}
?>