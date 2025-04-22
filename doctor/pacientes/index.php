<?php
session_start();
include_once '../../components/db.php';

// Contador de pacientes
$sqlCount = "SELECT COUNT(*) as total FROM usuarios WHERE tipo = 'paciente'";
$resCount = mysqli_query($conn, $sqlCount);
$rowCount = mysqli_fetch_assoc($resCount);
$total_pacientes = $rowCount['total'] ?? 0;

// Búsqueda
$filtro = trim($_GET['buscar'] ?? '');
$sqlList = "SELECT id, nombre, apellidos, email, telefono FROM usuarios WHERE tipo = 'paciente'";
if ($filtro !== '') {
    $filtro_esc = mysqli_real_escape_string($conn, $filtro);
    $sqlList .= " AND (nombre LIKE '%$filtro_esc%' OR apellidos LIKE '%$filtro_esc%' OR email LIKE '%$filtro_esc%' OR telefono LIKE '%$filtro_esc%')";
}
$sqlList .= " ORDER BY id, nombre, apellidos";

$resList = mysqli_query($conn, $sqlList);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pacientes - Panel Doctor</title>
    <link rel="icon" href="../../img/favicon.jpg" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/clinica/styles/root.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
    <style>
        body {
            background: url('../../assets/img/fondo.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #f1faee;
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
        .count-pacientes {
            font-size: 1.2rem;
            color: #f1faee;
            margin-bottom: 24px;
        }
        .search-bar {
            margin-bottom: 28px;
        }

        /* Estilos para tabla clara */
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

        /* Estilos para modal claro */
        .modal-content,
        .modal-body,
        .modal-header,
        .modal-footer,
        .modal-title,
        .form-label {
            background-color: #fff;
            color: #212529 !important;
        }
    </style>
</head>
<body>
<?php include '../../partials/navbar_doctor.php'; ?>
<div class="main-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="greeting">
            <i class="bi bi-person-lines-fill"></i> Pacientes
        </div>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCrearPaciente">
            <i class="bi bi-person-plus"></i> Crear paciente
        </button>
    </div>
    <div class="count-pacientes">
        Total de pacientes registrados: <strong><?php echo $total_pacientes; ?></strong>
    </div>
    <form class="search-bar mb-4" method="get" action="">
        <div class="input-group">
            <input type="text" class="form-control" name="buscar" placeholder="Buscar paciente por nombre, apellido, email o teléfono" value="<?php echo htmlspecialchars($filtro); ?>" />
            <button class="btn btn-success" type="submit"><i class="bi bi-search"></i> Buscar</button>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Informes</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($resList && mysqli_num_rows($resList) > 0) {
                    $i = 1;
                    while ($row = mysqli_fetch_assoc($resList)) { ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($row['apellidos']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                            <td><button class="btn btn-primary btn-ver-informes" 
                                    data-id="<?php echo $row['id']; ?>" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalInformes">
                                    <i class="bi bi-file-text"></i>
                                </button>
                            </td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr>
                        <td colspan="6" class="text-center">No se encontraron pacientes.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Crear Paciente -->
<div class="modal fade" id="modalCrearPaciente" tabindex="-1" aria-labelledby="modalCrearPacienteLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" action="crearpaciente.php">
        <div class="modal-header">
          <h5 class="modal-title" id="modalCrearPacienteLabel">Nuevo Paciente</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required />
          </div>
          <div class="mb-3">
            <label for="apellidos" class="form-label">Apellidos</label>
            <input type="text" class="form-control" id="apellidos" name="apellidos" required />
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required />
          </div>
          <div class="mb-3">
            <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento</label>
            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required />
          </div>
          <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="telefono" name="telefono" required />
          </div>
          <div class="mb-3">
            <label for="codigo_postal" class="form-label">Código postal</label>
            <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" required />
          </div>
          <div class="mb-3">
            <label for="ciudad" class="form-label">Ciudad</label>
            <input type="text" class="form-control" id="ciudad" name="ciudad" required />
          </div>
          <div class="mb-3">
            <label for="contraseña" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="contraseña" name="contraseña" required />
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Modal Informes -->
<div class="modal fade" id="modalInformes" tabindex="-1" aria-labelledby="modalInformesLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalInformesLabel">Informes del Paciente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" id="contenedorInformes">
        <p class="text-muted">Cargando informes...</p>
      </div>
    </div>
  </div>
</div>
<!-- Bootstrap Bundle con Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.btn-ver-informes').forEach(btn => {
    btn.addEventListener('click', () => {
        const pacienteId = btn.getAttribute('data-id');
        const contenedor = document.getElementById('contenedorInformes');
        contenedor.innerHTML = '<p class="text-muted">Cargando informes...</p>';
        fetch('../informes/get_informes.php?id=' + pacienteId)
            .then(res => res.text())
            .then(html => {
                contenedor.innerHTML = html;
            })
            .catch(() => {
                contenedor.innerHTML = '<p class="text-danger">Error al cargar los informes.</p>';
            });
    });
});
</script>
</body>
</html>
