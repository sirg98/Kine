<?php
header('Content-Type: application/xml; charset=utf-8');

// Configuración
$base_url = 'https://reflexiokinetp.es'; // Cambia esto por tu dominio real
$lastmod = date('Y-m-d');

// Rutas públicas que queremos incluir en el sitemap
$public_routes = [
    '' => '1.0', // Página principal
    'inicio' => '1.0',
    'servicios' => '0.8',
    'nosotros' => '0.8',
    'contacto' => '0.8',
    'login' => '0.5',
];

// Generar el XML
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php foreach ($public_routes as $route => $priority): ?>
    <url>
        <loc><?= $base_url . '/' . $route ?></loc>
        <lastmod><?= $lastmod ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority><?= $priority ?></priority>
    </url>
    <?php endforeach; ?>
</urlset> 