<?php
session_start();
include '../../components/db.php';

// Verificar que el usuario es un paciente
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'paciente') {
    die('No autorizado');
}

$paciente_id = $_SESSION['id'];

// Obtener mensajes
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $contacto_id = $_GET['contacto_id'] ?? null;
    $tipo = $_GET['tipo'] ?? null;

    if (!$contacto_id || !$tipo) {
        die(json_encode([]));
    }

    // Obtener mensajes según el tipo de contacto
    $sql = "SELECT m.*, 
            CASE 
                WHEN m.emisor_id = $paciente_id THEN 'paciente'
                ELSE 'contacto'
            END as emisor
            FROM mensajes m
            WHERE (m.emisor_id = $paciente_id AND m.receptor_id = $contacto_id)
               OR (m.emisor_id = $contacto_id AND m.receptor_id = $paciente_id)
            ORDER BY m.fecha ASC";

    $result = mysqli_query($conn, $sql);
    $mensajes = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
    echo json_encode($mensajes);
    exit;
}

// Enviar mensaje
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contacto_id = $_POST['contacto_id'] ?? null;
    $tipo = $_POST['tipo'] ?? null;
    $mensaje = $_POST['mensaje'] ?? '';

    if (!$contacto_id || !$tipo || !$mensaje) {
        die('Faltan datos');
    }

    // Insertar el mensaje
    $sql = "INSERT INTO mensajes (emisor_id, receptor_id, mensaje, fecha) VALUES (?, ?, ?, NOW())";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iis", $paciente_id, $contacto_id, $mensaje);
    
    if (mysqli_stmt_execute($stmt)) {
        echo 'ok';
    } else {
        echo 'Error al enviar el mensaje: ' . mysqli_error($conn);
    }
    exit;
}

echo 'error'; 