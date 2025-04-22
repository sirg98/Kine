<?php
include '../../components/db.php';

$paciente_id = intval($_GET['id'] ?? 0);

$sql = "SELECT informes.id, informes.contenido, informes.fecha, u.nombre AS doctor_nombre
        FROM informes 
        JOIN usuarios u ON informes.doctor_id = u.id
        WHERE informes.paciente_id = $paciente_id
        ORDER BY informes.fecha DESC";

$res = mysqli_query($conn, $sql) or die("Error en la consulta: " . mysqli_error($conn));


if (mysqli_num_rows($res) === 0) {
    echo "<p class='text-center text-muted'>Este paciente no tiene informes registrados.</p>";
} else {
    echo "<div class='list-group'>";
    while ($row = mysqli_fetch_assoc($res)) {
        $titulo = substr(strip_tags($row['contenido']), 0, 40) . '...';
        $fecha = date("d/m/Y", strtotime($row['fecha']));
        echo "
        <a href='../informes/ver.php?id={$row['id']}' class='list-group-item list-group-item-action'>
            <strong>$titulo</strong><br>
            <small class='text-muted'>Dr. {$row['doctor_nombre']} - $fecha</small>
        </a>";
    }
    echo "</div>";
}
?>
