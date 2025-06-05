<?php
ini_set('display_errors', 0);        // No mostrar errores al usuario
ini_set('display_startup_errors', 0);
error_reporting(0);                  // No reportar errores

require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$host = $_ENV['DB_HOST'];
$db   = $_ENV['DB_NAME'];
$user = $_ENV['DB_USER'];
$pass = $_ENV['DB_PASS'];

// Función para registrar errores
function logErrorBBDD($mensaje) {

    $logFile = __DIR__ . '/../src/pages/admin/logs/admin.log';
    $timestamp = date('[Y-m-d H:i:s]');
    file_put_contents($logFile, "$timestamp $mensaje" . PHP_EOL, FILE_APPEND);
}

// Deshabilitar excepciones automáticas de mysqli
mysqli_report(MYSQLI_REPORT_OFF);

// Crear conexión
$conn = mysqli_connect($host, $user, $pass, $db);
if ($conn) {
    $conn->set_charset("utf8mb4");
    return;
}

// Si falla la conexión, registrar y mostrar página bonita
logErrorBBDD("Error al conectar con la base de datos: " . mysqli_connect_error());

http_response_code(500);
?>
<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>Sitio Temporalmente Inhabilitado</title>
    <link href="/tailwind-colors.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class='bg-blue text-gray-800 min-h-screen flex items-center justify-center'>
    <main class="text-center px-4">
        <h1 class='text-4xl font-bold text-red-600 mb-4'>Sitio Temporalmente Inhabilitado</h1>
        <p class='text-lg text-gray-700 mb-6'>Estamos experimentando un problema con la base de datos.<br>Por favor, vuelve a intentarlo más tarde.</p>
        <a href="/" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Volver al inicio</a>
    </main>
</body>
</html>
<?php
exit;
?>
