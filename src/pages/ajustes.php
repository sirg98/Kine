<?php
// Determinar la pestaña activa
$tab = $_GET['tab'] ?? 'perfil';

function tabActive($name, $current) {
    return $name === $current ? 'bg-white shadow text-main' : 'bg-gray-100 text-gray-500 hover:bg-gray-200';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - ReflexioKineTP</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="/kine/tailwind-colors.css" rel="stylesheet">
</head>
<body class="bg-blue-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-10">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-main mb-1">Ajustes</h1>
                <p class="text-gray-600">Gestiona usuarios, tratamientos, citas y configuración del sistema</p>
            </div>
        </div>
        <!-- Tabs -->
        <div class="flex space-x-2 bg-gray-100 rounded-lg p-2 mb-8 justify-center">
            <a href="?tab=perfil" class="px-4 py-2 rounded-lg font-medium transition <?= tabActive('general', $tab) ?> flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                <span>Perfil</span>
            </a>
            <a href="?tab=contraseña" class="px-4 py-2 rounded-lg font-medium transition <?= tabActive('contraseña', $tab) ?> flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 0 0-3-3.87M9 20H4v-2a4 4 0 0 1 3-3.87M16 3.13a4 4 0 0 1 0 7.75M8 3.13a4 4 0 0 0 0 7.75"/></svg>
                <span>Contraseña</span>
            </a>
            <a href="?tab=avanzado" class="px-4 py-2 rounded-lg font-medium transition <?= tabActive('avanzado', $tab) ?> flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="20" height="14" x="2" y="5" rx="2"/><path d="M8 2v4M16 2v4"/></svg>
                <span>Configuración avanzada</span>
            </a>
        </div>
        <!-- Contenido de la pestaña -->
        <div class="bg-white rounded-xl shadow p-6">
            <?php
            $tabFile = __DIR__ . "/tabs/{$tab}.php";
            if (file_exists($tabFile)) {
                include $tabFile;
            } else {
                echo '<div class="text-gray-500">Sección no disponible.</div>';
            }
            ?>
        </div>
    </div>
</body>
</html> 