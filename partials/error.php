<?php
$code = isset($_GET['code']) ? (int)$_GET['code'] : 404;
http_response_code($code);

$errors = [
  403 => ['title' => '403 - Acceso denegado', 'message' => 'No tienes permiso para acceder a esta página.'],
  404 => ['title' => '404 - Página no encontrada', 'message' => 'Lo sentimos, la página que buscas no existe.'],
];

$title = $errors[$code]['title'] ?? 'Error';
$message = $errors[$code]['message'] ?? 'Ha ocurrido un error inesperado.';
?>

<main class="flex-grow flex items-center justify-center">
    <div class="text-center px-4">
        <h1 class="text-4xl font-bold mb-4"><?= htmlspecialchars($title) ?></h1>
        <p class="text-xl text-gray-600 mb-8"><?= $message ?></p>
        <a href="/" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
            Volver al inicio
        </a>
    </div>
</main>


