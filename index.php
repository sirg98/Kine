<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include 'components/db.php';
include 'auth/login.php';

$url = $_GET['url'] ?? '';
$url = ltrim($url, '/');

$routes = [
    '' => 'partials/hero.php',
    'inicio' => 'partials/hero.php',
    'servicios' => 'src/pages/servicios.php',
    'nosotros' => 'src/pages/nosotros.php',
    'contacto' => 'src/pages/contacto.php',
    'login' => 'src/pages/login.php',
    'paciente' => 'src/pages/paciente/index.php',
    'doctor' => 'src/pages/doctor/index.php',
    'informe' => 'src/pages/doctor/informe.php',
    'nuevoinforme' => 'src/pages/doctor/nuevo_informe.php',
    'apuntes' => 'src/pages/doctor/apuntes.php',
    'admin' => 'src/pages/admin/index.php',
    'ajustes' => 'src/pages/ajustes.php',
];

if ($url === 'logout') {
    session_destroy();
    header('Location: /');
    exit;
}

$main = $routes[$url] ?? 'partials/404.php';

?>
<!DOCTYPE html>
<html lang="es" class="dark:bg-gray-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KineticCare - Bienestar Holístico</title>
    <script>n
        (function(){
            let shouldUseDark = false;

            const savedPreference = localStorage.getItem('darkMode');

            if (savedPreference === 'true') {
                shouldUseDark = true;
            } else if (savedPreference === 'false') {
                shouldUseDark = false;
            } else {
                // No hay preferencia guardada, usar la del sistema operativo
                shouldUseDark = window.matchMedia &&
                                window.matchMedia('(prefers-color-scheme: dark)').matches;
            }

            if (shouldUseDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="tailwind-colors.css" rel="stylesheet">
</head>
<body class="bg-blue text-gray-900 dark:bg-gray-900 dark:text-gray-100 transition-colors duration-200">
    <?php if (empty($NO_NAVBAR)) include 'partials/navbar.php'; ?>
    <main class="dark:bg-gray-900">
    <?php include $main; ?>

    <?php if ($url == 'inicio' || $url == ''): ?>
        <?php include 'partials/tratamientos.php'; ?>
        <?php include 'partials/informacion.php'; ?>
        <?php include 'partials/opiniones.php'; ?>
        <?php include 'partials/cta.php'; ?>
    <?php endif; ?>

    </main>
    <?php if (empty($NO_FOOTER)) include 'partials/footer.php'; ?>
</body>
</html> 