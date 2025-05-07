<?php
header('Content-Type: application/json');

$folder = $_GET['folder'] ?? '';
$base_dir = 'uploads/apuntes/';

if (empty($folder)) {
    echo json_encode([]);
    exit;
}

$folder_path = $base_dir . $folder;
if (!is_dir($folder_path)) {
    echo json_encode([]);
    exit;
}

$files = array_filter(glob("$folder_path/*.{png,pdf}", GLOB_BRACE), 'is_file');
echo json_encode($files); 