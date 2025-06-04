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
    '' => 'src/pages/home.php',
    'inicio' => 'src/pages/home.php',
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
// Títulos y descripciones por ruta
$meta = [
    '' => ['title' => 'Inicio - ReflexioKineTP', 'description' => 'Bienvenido a ReflexioKineTP, tu centro de terapias.'],
    'inicio' => ['title' => 'Inicio - ReflexioKineTP', 'description' => 'Bienvenido a ReflexioKineTP, tu centro de terapias.'],
    'servicios' => ['title' => 'Servicios - ReflexioKineTP', 'description' => 'Conoce los servicios terapéuticos que ofrecemos.'],
    'nosotros' => ['title' => 'Nosotros - ReflexioKineTP', 'description' => 'Aprende más sobre nuestro equipo y filosofía.'],
    'contacto' => ['title' => 'Contacto - ReflexioKineTP', 'description' => 'Contáctanos para más información o agendar tu cita.'],
    'login' => ['title' => 'Iniciar Sesión - ReflexioKineTP', 'description' => 'Accede a tu cuenta de paciente o terapeuta.'],
    'paciente' => ['title' => 'Área del Paciente - ReflexioKineTP', 'description' => 'Gestión y seguimiento de tus informes y citas.'],
    'terapeuta' => ['title' => 'Panel del Terapeuta - ReflexioKineTP', 'description' => 'Gestión de pacientes, informes y seguimiento.'],
    'admin' => ['title' => 'Panel de Administración - ReflexioKineTP', 'description' => 'Gestión completa del sistema.'],
    // Agrega más según tus rutas
];

// Valores por defecto si la ruta no está en $meta
$page_title = $meta[$url]['title'] ?? 'ReflexioKineTP';
$page_description = $meta[$url]['description'] ?? 'Centro de terapias personalizadas.';

?>
<!DOCTYPE html>
<html lang="es" class="dark:bg-gray-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    <meta name="description" content="<?= htmlspecialchars($page_description) ?>">
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

    <?php if (empty($NO_FOOTER)) include 'partials/footer.php'; ?>
</body>
</html> 