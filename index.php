<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include 'components/db.php';
include 'auth/login.php';
$url = $_GET['url'] ?? '';
switch ($url) {
    case 'inicio':
    case '':
        $main = 'partials/hero.php';
        break;
    case 'servicios':
        $main = 'src/pages/servicios.php';
        break;
    case 'nosotros':
        $main = 'src/pages/nosotros.php';
        break;
    case 'cita':
        $main = 'src/pages/cita.php';
        break;
    case 'login':
        $main = 'src/pages/login.php';
        break;
    case 'logout':
        session_destroy();
        header('Location: /');
        exit;
    case 'paciente':
        $main = 'src/pages/paciente/index.php';
        break;
    case 'doctor':
        $main = 'src/pages/doctor/index.php';
        break;
    default:
        $main = 'partials/404.php';
        break;
}
?>
<!DOCTYPE html>
<html lang="es" class="dark:bg-gray-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KineticCare - Bienestar Holístico</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="tailwind-colors.css" rel="stylesheet">
</head>
<body class="bg-blue text-gray-900 dark:bg-gray-900 dark:text-gray-100 transition-colors duration-200">
    <?php include 'partials/navbar.php'; ?>
    <main class="dark:bg-gray-900">
    <?php include $main; ?>

    <?php if ($url == 'inicio' || $url == ''): ?>
        <?php include 'partials/tratamientos.php'; ?>
        <?php include 'partials/informacion.php'; ?>
        <?php include 'partials/opiniones.php'; ?>
        <?php include 'partials/cta.php'; ?>
    <?php endif; ?>

    </main>
    <?php include 'partials/footer.php'; ?>

    <script>
    // Verificar la preferencia guardada al cargar la página
    if (localStorage.getItem('darkMode') === 'true') {
        document.documentElement.classList.add('dark');
    }
    </script>
</body>
</html> 