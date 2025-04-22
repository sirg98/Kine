<?php
session_start();
include '../../components/db.php';

echo "<pre>";
echo "=== REQUEST ===\n";
$paciente_id = $_REQUEST['paciente_id'] ?? null;
$notas = $_POST['notas'] ?? [];
$fecha = $_REQUEST['fecha'] ?? null;
$doctor_id = $_SESSION['id'];

print_r($_REQUEST);

// Verificar que el paciente_id y las notas existan
if ($paciente_id && !empty($notas)) {
    // Concatenar todas las notas en un solo string, separadas por tabulaciones
    $contenido_completo = '';
    foreach ($notas as $apartado => $contenido) {
        // Limpiar los datos
        $apartado = mysqli_real_escape_string($conn, $apartado);
        $contenido = mysqli_real_escape_string($conn, $contenido);

        // Formato: "Apartado: Nota"
        $contenido_completo .= "$apartado: $contenido; ";
    }

    // Eliminar el último tabulador
    $contenido_completo = rtrim($contenido_completo, "\t");

    // Insertar todas las notas concatenadas en la base de datos
    $sqlInsert = "INSERT INTO informes (paciente_id, doctor_id, contenido) VALUES ('$paciente_id', '$doctor_id', '$contenido_completo')";
    $result = mysqli_query($conn, $sqlInsert);

    if ($result) {
        echo "Notas guardadas correctamente.";
    } else {
        echo "Error al guardar las notas: " . mysqli_error($conn);
    }
} else {
    echo "Error: No se enviaron datos válidos.";
}

echo "\n=== POST ===\n";
print_r($_POST);

echo "\n=== GET ===\n";
print_r($_GET);

echo "</pre>";
?>