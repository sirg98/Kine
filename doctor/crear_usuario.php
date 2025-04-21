<?php
// Este archivo es el antiguo register2.php, adaptado para que solo el doctor pueda crear usuarios
// Solo el doctor puede crear usuarios
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'doctor') {
    header('Location: ../auth/login.php');
    exit();
}

include_once '../components/db.php';
// ... (resto del código de register2.php, con controles de acceso para el doctor)
?>
