<?php
session_start();
include_once '../../components/db.php';

$buscar = trim($_GET['buscar'] ?? '');
$buscar_sql = '';

if ($buscar !== '') {
    $buscar_esc = mysqli_real_escape_string($conn, $buscar);
    $buscar_sql = " AND (
        p.nombre LIKE '%$buscar_esc%' OR
        p.apellidos LIKE '%$buscar_esc%' OR
        d.nombre LIKE '%$buscar_esc%' OR
        d.apellidos LIKE '%$buscar_esc%' OR
        i.fecha LIKE '%$buscar_esc%'
    )";
}

$sql = "SELECT i.id, i.fecha, p.nombre AS paciente_nombre, p.apellidos AS paciente_apellidos,
               d.nombre AS doctor_nombre, d.apellidos AS doctor_apellidos
        FROM informes i
        INNER JOIN usuarios p ON i.paciente_id = p.id
        INNER JOIN usuarios d ON i.doctor_id = d.id
        WHERE 1 $buscar_sql
        ORDER BY i.fecha DESC";

$res = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informes - Panel Doctor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../img/favicon.jpg" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/clinica/styles/root.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
    <style>
        body {
            background: url('../../assets/img/fondo.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #212529;
            min-height: 100vh;
        }
        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(0,0,0,0.7), rgba(0,0,0,0.8));
            z-index: -1;
        }
        .main-wrapper {
            max-width: 900px;
            margin: 0 auto;
            padding: 32px 16px;
            margin-top: 88px;
        }
        .greeting {
            font-size: 2rem;
            font-weight: 400;
            color: var(--secondary-color, #53a252);
            text-shadow: 1px 1px 6px #0005;
            margin-bottom: 24px;
        }
        .table {
            background-color: #fff;
            border-radius: 1rem;
            overflow: hidden;
        }
        .table th {
            color: #212529;
            background-color: #e9ecef;
            vertical-align: middle;
        }
        .table td {
            color: #212529;
            vertical-align: middle;
        }
        .table-striped > tbody > tr:nth-of-type(odd) {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
<?php include '../../partials/navbar_doctor.php'; ?>
<div class="main-wrapper">
    <div class="greeting">
        <i class="bi bi-journal-medical"></i> Informes
    </div>

    <!-- Buscador -->
    <form method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="buscar" class="form-control" placeholder="Buscar por paciente, doctor o fecha..." value="<?php echo htmlspecialchars($buscar); ?>">
            <button type="submit" class="btn btn-success"><i class="bi bi-search"></i> Buscar</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Paciente</th>
                    <th>Doctor</th>
                    <th>Fecha</th>
                    <th>Ver</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($res && mysqli_num_rows($res) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($res)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['paciente_nombre'] . ' ' . $row['paciente_apellidos']); ?></td>
                        <td><?php echo htmlspecialchars($row['doctor_nombre'] . ' ' . $row['doctor_apellidos']); ?></td>
                        <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($row['fecha']))); ?></td>
                        <td><a href="ver.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm"><i class="bi bi-eye"></i></a></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4" class="text-center">No se encontraron informes.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
