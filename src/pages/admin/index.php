<?php
// Determinar la pestaña activa
$tab = $_GET['tab'] ?? 'general';

function tabActive($name, $current) {
    return $name === $current ? 'bg-card shadow text-main' : 'bg-gray-100 text-gray-500 hover:bg-gray-200';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - KineticCare</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="/kine/tailwind-colors.css" rel="stylesheet">
</head>
<body class="bg-blue-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-10">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-main mb-1">Panel de Administración</h1>
                <p class="text-gray-600">Gestiona usuarios, tratamientos, citas y configuración del sistema</p>
            </div>
        </div>
        <!-- Tabs -->
        <div class="flex flex-wrap sm:flex-nowrap gap-2 bg-gray-100 rounded-lg p-2 mb-8 justify-center">
            <a href="?tab=general" class="px-4 py-2 rounded-lg font-medium transition <?= tabActive('general', $tab) ?> flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                <span>General</span>
            </a>
            <a href="?tab=usuarios" class="px-4 py-2 rounded-lg font-medium transition <?= tabActive('usuarios', $tab) ?> flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 0 0-3-3.87M9 20H4v-2a4 4 0 0 1 3-3.87M16 3.13a4 4 0 0 1 0 7.75M8 3.13a4 4 0 0 0 0 7.75"/></svg>
                <span>Usuarios</span>
            </a>
            <a href="?tab=tratamientos" class="px-4 py-2 rounded-lg font-medium transition <?= tabActive('tratamientos', $tab) ?> flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="20" height="14" x="2" y="5" rx="2"/><path d="M8 2v4M16 2v4"/></svg>
                <span>Tratamientos</span>
            </a>
            <a href="?tab=citas" class="px-4 py-2 rounded-lg font-medium transition <?= tabActive('citas', $tab) ?> flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                <span>Citas</span>
            </a>
            <a href="?tab=informes" class="px-4 py-2 rounded-lg font-medium transition <?= tabActive('informes', $tab) ?> flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="20" height="14" x="2" y="5" rx="2"/><path d="M8 9h8M8 13h6"/></svg>
                <span>Informes</span>
            </a>
            <a href="?tab=nuevo_paciente" class="px-4 py-2 rounded-lg font-medium transition <?= tabActive('nuevo_paciente', $tab) ?> flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                <span>Nuevo Paciente</span>
            </a>
            <a href="?tab=logs" class="px-4 py-2 rounded-lg font-medium transition <?= tabActive('logs', $tab) ?> flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                <span>Nuevo Paciente</span>
            </a>
        </div>
        <!-- Contenido de la pestaña -->
        <div class="bg-card rounded-xl shadow p-6">
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