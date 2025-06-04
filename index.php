<?php
session_start();
include 'components/db.php';
include 'components/mail.php';
include 'auth/login.php';

$url = $_GET['url'] ?? '';
$url = ltrim($url, '/');

// Rutas protegidas que requieren autenticación
$protected_routes = [
    'terapeuta'     => ['terapeuta'],
    'informe'       => ['terapeuta'],
    'nuevoinforme'  => ['terapeuta'],
    'apuntes'       => ['terapeuta'],
    'admin'         => ['admin'],
    'paciente'      => ['paciente'],
    'ajustes'       => ['paciente', 'admin', 'terapeuta'],
];


// Verificar autenticación para rutas protegidas
if (isset($protected_routes[$url])) {
    $required_role = $protected_routes[$url];
    if (!isset($_SESSION['id']) || !in_array($_SESSION['tipo'], $required_role)) {
        header('Location: /login?error=unauthorized');
        exit;
    }
}

$routes = [
    '' => 'partials/hero.php',
    'inicio' => 'partials/hero.php',
    'servicios' => 'src/pages/servicios.php',
    'nosotros' => 'src/pages/nosotros.php',
    'contacto' => 'src/pages/contacto.php',
    'login' => 'src/pages/login.php',
    'restablecer' => 'src/pages/restablecer.php',
    'paciente' => 'src/pages/paciente/index.php',
    'terapeuta' => 'src/pages/terapeuta/index.php',
    'informe' => 'src/pages/terapeuta/informe.php',
    'nuevoinforme' => 'src/pages/terapeuta/nuevo_informe.php',
    'apuntes' => 'src/pages/terapeuta/apuntes.php',
    'admin' => 'src/pages/admin/index.php',
    'ajustes' => 'src/pages/ajustes.php',
    'error' => 'partials/error.php',
];

if ($url === 'logout') {
    session_destroy();
    header('Location: /');
    exit;
}

if (!array_key_exists($url, $routes)) {
    header("Location: /error");
    exit;
}

$main = $routes[$url];

?>
<!DOCTYPE html>
<html lang="es" class="dark:bg-gray-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReflexioKineTP</title>
    <script>
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
<body class="flex flex-col min-h-screen bg-blue text-gray-900 dark:bg-gray-900 dark:text-gray-100 transition-colors duration-200">
    <?php if (empty($NO_NAVBAR)) include 'partials/navbar.php'; ?>
    <?php include $main; ?>

    <?php if ($url == 'inicio' || $url == ''): ?>
        <?php include 'partials/tratamientos.php'; ?>
        <?php include 'partials/informacion.php'; ?>
        <?php include 'partials/opiniones.php'; ?>
        <?php include 'partials/cta.php'; ?>
    <?php endif; ?>
    <?php if (empty($NO_FOOTER)) include 'partials/footer.php'; ?>
</body>
</html> 