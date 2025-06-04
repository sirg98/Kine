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
// Redirigir si usuario ya autenticado intenta acceder a /login
if ($url === 'login' && isset($_SESSION['tipo'])) {
    switch ($_SESSION['tipo']) {
        case 'admin':
            header('Location: /admin');
            exit;
        case 'terapeuta':
            header('Location: /terapeuta');
            exit;
        case 'paciente':
            header('Location: /paciente');
            exit;
        default:
            header('Location: /logout');
            exit;
    }
}

$main = $routes[$url];
// Títulos y descripciones por ruta
$meta = [
    '' => [
        'title' => 'Centro de Kinesiología y Bienestar - ReflexioKineTP',
        'description' => 'Bienvenido a ReflexioKineTP, especialistas en kinesiología, salud y movimiento para el bienestar físico y emocional.'
    ],
    'inicio' => [
        'title' => 'Inicio | Kinesiología y Salud Integral - ReflexioKineTP',
        'description' => 'Descubre cómo mejorar tu salud y reducir el estrés con nuestros tratamientos personalizados de kinesiología.'
    ],
    'servicios' => [
        'title' => 'Servicios Terapéuticos en Movimiento y Dolor - ReflexioKineTP',
        'description' => 'Explora nuestras terapias centradas en movimiento, reducción del dolor, bienestar y salud integral.'
    ],
    'nosotros' => [
        'title' => 'Sobre Nosotros - Especialistas en Bienestar y Salud',
        'description' => 'Conoce al equipo de ReflexioKineTP, expertos en estrés, salud emocional y tratamientos de kinesiología.'
    ],
    'contacto' => [
        'title' => 'Contacto - Agenda tu sesión de kinesiología hoy',
        'description' => '¿Tienes dudas o quieres pedir cita? Contacta con ReflexioKineTP y recibe atención personalizada por parte de nuestros profesionales.'
    ],
    'login' => [
        'title' => 'Iniciar Sesión - Área Personal ReflexioKineTP',
        'description' => 'Accede a tu cuenta para ver citas, informes o gestionar tu tratamiento terapéutico.'
    ],
    'paciente' => [
        'title' => 'Área del Paciente - Historial de Citas y Salud',
        'description' => 'Consulta tus informes médicos, agenda nuevas sesiones y sigue tu evolución terapéutica.'
    ],
    'terapeuta' => [
        'title' => 'Panel del Terapeuta - Gestión de Pacientes y Bienestar',
        'description' => 'Administra sesiones, informes y tratamientos personalizados centrados en la salud y el movimiento.'
    ],
    'admin' => [
        'title' => 'Panel de Administración - ReflexioKineTP',
        'description' => 'Gestión del sistema, usuarios, terapeutas y control general de los servicios ofrecidos.'
    ]
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
    <!-- Open Graph -->
    <meta property="og:title" content="<?= htmlspecialchars($page_title) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($page_description) ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://<?= $_SERVER['HTTP_HOST'] ?>/<?= htmlspecialchars($url) ?>">
    <meta property="og:image" content="https://reflexiokinetp.es/assets/img/logo-og.jpg">

    <link rel="canonical" href="https://<?= $_SERVER['HTTP_HOST'] ?>/<?= htmlspecialchars($url) ?>" />
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
    <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "ReflexioKineTP",
  "url": "https://reflexiokine.es",
  "logo": "https://reflexiokine.es/assets/img/logo-og.jpg",
  "description": "Centro de kinesiología, salud integral y bienestar físico-emocional.",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "123 Wellness Avenue",
    "addressLocality": "Healing City",
    "postalCode": "HC 12345",
    "addressCountry": "ES"
  },
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "+34 600 132 456",
    "contactType": "customer support",
    "areaServed": "ES",
    "availableLanguage": ["Spanish", "English"]
  },
  "sameAs": [
    "https://www.facebook.com/ReflexioKineTP",
    "https://www.instagram.com/ReflexioKineTP"
  ]
}
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