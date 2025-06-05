<?php
session_start();
// Optional: restrict to admin users
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    http_response_code(403);
    exit('Access denied');
}

$logFile = basename($_GET['file'] ?? '');
$logPath = __DIR__ . "/../logs/$logFile";

if (!preg_match('/\.log$/', $logFile) || !file_exists($logPath)) {
    http_response_code(404);
    exit('Log not found');
}

header('Content-Type: text/plain');
readfile($logPath);
