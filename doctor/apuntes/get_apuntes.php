<?php
$carpeta = $_GET['carpeta'] ?? '';
$path = 'uploads/apuntes/' . $carpeta;

if (!is_dir($path)) {
    echo json_encode([]);
    exit;
}

$archivos = array_values(array_filter(glob("$path/*.{pdf,png}", GLOB_BRACE), 'is_file'));
$archivos = array_map('basename', $archivos);

header('Content-Type: application/json');
echo json_encode($archivos);
