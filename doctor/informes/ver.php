<?php
session_start();
include_once '../../components/db.php';

if (!isset($_GET['id'])) {
    die("Informe no especificado.");
}

$id = intval($_GET['id']);

$sql = "SELECT i.contenido, i.fecha, 
               p.nombre AS paciente_nombre, p.apellidos AS paciente_apellidos,
               d.nombre AS doctor_nombre, d.apellidos AS doctor_apellidos
        FROM informes i
        INNER JOIN usuarios p ON i.paciente_id = p.id
        INNER JOIN usuarios d ON i.doctor_id = d.id
        WHERE i.id = $id
        LIMIT 1";

$res = mysqli_query($conn, $sql);
if (!$res || mysqli_num_rows($res) === 0) {
    die("Informe no encontrado.");
}

$info = mysqli_fetch_assoc($res);
$fecha = date('d/m/Y H:i', strtotime($info['fecha']));
$paciente = $info['paciente_nombre'] . ' ' . $info['paciente_apellidos'];
$doctor = $info['doctor_nombre'] . ' ' . $info['doctor_apellidos'];
$contenido = '';
$bloques = explode(';', $info['contenido']);

foreach ($bloques as $bloque) {
    $bloque = trim($bloque);
    if ($bloque === '') continue;

    $partes = explode(':', $bloque, 2);
    if (count($partes) === 2) {
        $titulo = htmlspecialchars(trim($partes[0]));
        $texto = htmlspecialchars(trim($partes[1]));
        $contenido .= "<p><strong>$titulo:</strong> $texto</p>";
    } else {
        $contenido .= "<p>" . htmlspecialchars($bloque) . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe del Paciente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: white;
            color: black;
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 0;
        }
        .wrapper {
            max-width: 800px;
            margin: 60px auto;
            padding: 40px;
            border: 1px solid #000;
        }
        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #000;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .empresa {
            font-weight: bold;
            font-size: 1.3rem;
        }
        .doctor-box {
            text-align: right;
            border: 1px solid #000;
            padding: 10px 15px;
            font-size: 0.95rem;
        }
        .paciente {
            text-align: center;
            font-size: 1.6rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .fecha {
            text-align: center;
            font-size: 0.9rem;
            margin-bottom: 30px;
        }
        .contenido {
            white-space: pre-wrap;
            font-size: 1rem;
            line-height: 1.6;
        }
        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 999;
        }
        @media print {
            .print-btn {
                display: none;
            }
        }
    </style>
</head>
<body>
    <button class="btn btn-dark btn-sm print-btn" onclick="window.print()">
        <i class="bi bi-printer"></i> Imprimir PDF
    </button>

    <div class="wrapper">
        <div class="header">
            <div class="empresa">Centro Vital Balance</div>
            <div class="doctor-box">
                Doctor:<br>
                <?php echo htmlspecialchars($doctor); ?>
            </div>
        </div>

        <div class="paciente"><?php echo htmlspecialchars($paciente); ?></div>
        <div class="fecha"><?php echo $fecha; ?></div>

        <div class="contenido">
            <?php echo $contenido; ?>
        </div>
    </div>
</body>
</html>
