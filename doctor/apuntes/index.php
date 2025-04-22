<?php
session_start();

$base_dir = 'uploads/apuntes/';
$orden_path = $base_dir . 'orden.json';

if (!is_dir($base_dir)) {
    mkdir($base_dir, 0777, true);
}

function getCarpetasOrdenadas($base_dir, $orden_path) {
    $carpetas = array_filter(glob($base_dir . '*'), 'is_dir');
    $nombres = array_map('basename', $carpetas);
    if (!file_exists($orden_path)) {
        file_put_contents($orden_path, json_encode($nombres));
    }
    $orden = json_decode(file_get_contents($orden_path), true);
    $orden = array_values(array_filter($orden, fn($c) => in_array($c, $nombres)));
    foreach ($nombres as $c) {
        if (!in_array($c, $orden)) $orden[] = $c;
    }
    return $orden;
}

function guardarOrden($orden, $orden_path) {
    file_put_contents($orden_path, json_encode(array_values($orden), JSON_PRETTY_PRINT));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nueva_carpeta'])) {
    $nombre = preg_replace('/[^a-zA-Z0-9_-]/', '_', $_POST['nueva_carpeta']);
    $ruta = $base_dir . $nombre;
    if (!is_dir($ruta)) {
        mkdir($ruta, 0777, true);
        $msg = "Carpeta '$nombre' creada.";
    } else {
        $msg = "Ya existe esa carpeta.";
    }
}



// Renombrar carpeta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['renombrar_carpeta'])) {
    $actual = $_POST['carpeta_actual'];
    $nuevo = preg_replace('/[^a-zA-Z0-9_-]/', '_', $_POST['carpeta_nueva']);
    $ruta_actual = $base_dir . $actual;
    $ruta_nueva = $base_dir . $nuevo;

    if (is_dir($ruta_actual) && !is_dir($ruta_nueva)) {
        rename($ruta_actual, $ruta_nueva);
        $orden = getCarpetasOrdenadas($base_dir, $orden_path);
        $index = array_search($actual, $orden);
        if ($index !== false) {
            $orden[$index] = $nuevo;
            guardarOrden($orden, $orden_path);
        }
        $msg = "Carpeta renombrada correctamente.";
    } else {
        $msg = "Error: ya existe '$nuevo'.";
    }
}

// Mover carpeta ↑↓
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mover_carpeta'])) {
    $orden = getCarpetasOrdenadas($base_dir, $orden_path);
    $carpeta = $_POST['mover_carpeta'];
    $direccion = $_POST['direccion'];
    $index = array_search($carpeta, $orden);
    if ($index !== false) {
        $nuevoIndex = $direccion === 'up' ? $index - 1 : $index + 1;
        if (isset($orden[$nuevoIndex])) {
            $tmp = $orden[$index];
            $orden[$index] = $orden[$nuevoIndex];
            $orden[$nuevoIndex] = $tmp;
            guardarOrden($orden, $orden_path);
        }
    }
}

// Subida de archivo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['apunte'])) {
    $file = $_FILES['apunte'];
    $allowed = ['image/png', 'application/pdf'];
    $nombre_personalizado = trim($_POST['nombre_personalizado']);
    $carpeta_destino = $_POST['carpeta_destino'];

    if ($file['error'] === 0 && in_array($file['type'], $allowed)) {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $safe_name = $nombre_personalizado !== ''
            ? preg_replace('/[^a-zA-Z0-9_-]/', '_', $nombre_personalizado) . '.' . $ext
            : uniqid('apunte_', true) . '.' . $ext;
        $upload_path = rtrim($base_dir . $carpeta_destino, '/') . '/';

        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        $dest = $upload_path . $safe_name;
        if (move_uploaded_file($file['tmp_name'], $dest)) {
            $msg = 'Archivo subido correctamente.';
        } else {
            $msg = 'Error al mover el archivo.';
        }
    } else {
        $msg = 'Solo se permiten archivos PNG o PDF.';
    }
}

$orden_carpetas = getCarpetasOrdenadas($base_dir, $orden_path);
// Eliminar archivo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_archivo'])) {
    $archivo_relativo = $_POST['eliminar_archivo'];
    $archivo_a_eliminar = __DIR__ . '/' . $archivo_relativo;
    
    if (file_exists($archivo_a_eliminar)) {
        unlink($archivo_a_eliminar);
        $msg = 'Archivo eliminado correctamente.';
    } else {
        $msg = 'Archivo no encontrado.';
    }
}
// Eliminar carpeta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_carpeta'])) {
    $carpeta = basename($_POST['eliminar_carpeta']);
    $ruta = $base_dir . $carpeta;

    if (is_dir($ruta)) {
        array_map('unlink', glob("$ruta/*")); // Elimina archivos
        rmdir($ruta); // Elimina la carpeta

        // Actualizar orden
        $orden = getCarpetasOrdenadas($base_dir, $orden_path);
        $orden = array_values(array_filter($orden, fn($c) => $c !== $carpeta));
        guardarOrden($orden, $orden_path);

        $msg = "Carpeta eliminada correctamente.";
    } else {
        $msg = "La carpeta no existe.";
    }
}

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Apuntes - Panel Doctor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../img/favicon.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/clinica/styles/root.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
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
        .apunte-thumb {
            max-width: 100px;
            max-height: 100px;
            object-fit: contain;
            border-radius: 6px;
            box-shadow: 0 2px 8px #0002;
        }
        .card-body {
            padding: 1rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .card .text-break a {
            word-break: break-word;
            white-space: normal;
            display: block;
            max-width: 100%;
        }

        .card {
            min-width: 180px;
            max-width: 100%;
        }

        .card .card-body {
            word-wrap: break-word;
            text-align: center;
        }

        .card {
            position: relative;
        }

        .btn-delete {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            z-index: 1;
        }

    </style>
</head>
<body>
    <?php include '../../partials/navbar_doctor.php'; ?>
    <div class="main-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="greeting"><i class="bi bi-journal-richtext"></i> Apuntes</div>
            <div class="d-flex gap-2">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalSubirApunte">
                    <i class="bi bi-upload"></i> Añadir apunte
                </button>
                <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalCrearCarpeta">
                    <i class="bi bi-folder-plus"></i> Nueva carpeta
                </button>
            </div>
        </div>

        <?php foreach ($orden_carpetas as $nombre_carpeta): 
        $carpeta = $base_dir . $nombre_carpeta;
        $apuntes = array_filter(glob("$carpeta/*.{png,pdf}", GLOB_BRACE), 'is_file');
    ?>
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center bg-dark text-light px-3 py-2 rounded">
                <h5 class="mb-0"><i class="bi bi-folder-fill"></i> <?php echo htmlspecialchars($nombre_carpeta); ?></h5>
                <div>
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="mover_carpeta" value="<?php echo $nombre_carpeta; ?>">
                        <input type="hidden" name="direccion" value="up">
                        <button class="btn btn-sm btn-light" title="Subir carpeta">↑</button>
                    </form>
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="mover_carpeta" value="<?php echo $nombre_carpeta; ?>">
                        <input type="hidden" name="direccion" value="down">
                        <button class="btn btn-sm btn-light" title="Bajar carpeta">↓</button>
                    </form>
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalRenombrar_<?php echo $nombre_carpeta; ?>">Renombrar</button>
                    <form method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta carpeta?');">
                        <input type="hidden" name="eliminar_carpeta" value="<?php echo htmlspecialchars($nombre_carpeta); ?>">
                        <button type="submit" class="btn btn-sm btn-danger" title="Eliminar carpeta" onclick="setTimeout(() => location.reload(), 100);">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- RENOMBRAR CARPETA MODAL -->
            <div class="modal fade" id="modalRenombrar_<?php echo $nombre_carpeta; ?>" tabindex="-1">
            <div class="modal-dialog">
                <form method="POST" class="modal-content">
                <input type="hidden" name="carpeta_actual" value="<?php echo $nombre_carpeta; ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Renombrar carpeta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="carpeta_nueva" class="form-control" placeholder="Nuevo nombre" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="renombrar_carpeta" class="btn btn-warning">Renombrar</button>
                </div>
                </form>
            </div>
            </div>

            <div class="row g-3 mt-2 justify-content-start">
        <?php if (count($apuntes) > 0): ?>
            <?php foreach ($apuntes as $apunte): 
                $basename = basename($apunte);
                $url = $carpeta . '/' . $basename;
                $is_pdf = strtolower(pathinfo($basename, PATHINFO_EXTENSION)) === 'pdf';
            ?>
            <div class="col-6 col-md-4 col-lg-3">

                <div class="card text-center" style="width: 220px; height: 220px; position: relative;">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <?php if ($is_pdf): ?>
                            <!-- Icono PDF -->
                            <button class="btn btn-link text-danger p-0 border-0" data-bs-toggle="modal" data-bs-target="#modalPdf_<?php echo md5($url); ?>">
                                <i class="bi bi-file-earmark-pdf" style="font-size:3rem;"></i>
                            </button>
                            <!-- Modal para previsualizar PDF -->
                            <div class="modal fade" id="modalPdf_<?php echo md5($url); ?>" tabindex="-1">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content bg-dark text-light">
                                <div class="modal-header">
                                    <h5 class="modal-title"><?php echo htmlspecialchars($basename); ?></h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body p-0">
                                    <embed src="<?php echo $url; ?>" type="application/pdf" width="100%" style="min-height:80vh;">
                                </div>
                                </div>
                            </div>
                            </div>
                            <!-- Enlace al archivo PDF -->
                            <div class="small text-break">
                                <a href="<?php echo $url; ?>" target="_blank"><?php echo htmlspecialchars($basename); ?></a>
                            </div>
                            <!-- Botón eliminar -->
                            <form method="POST" onsubmit="return confirm('¿Seguro que quieres eliminar este archivo?');" class="btn-delete">
                                <input type="hidden" name="eliminar_archivo" value="<?php echo $url; ?>">
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        <?php else: ?>
                            <!-- Imagen -->
                            <a href="<?php echo $url; ?>" target="_blank">
                                <img src="<?php echo $url; ?>" class="apunte-thumb" alt="Apunte">
                            </a>
                            <div class="small text-break">
                                <a href="<?php echo $url; ?>" target="_blank"><?php echo htmlspecialchars($basename); ?></a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-muted text-center">No hay archivos en esta carpeta.</div>
        <?php endif; ?>
    </div>

            </div>
        <?php endforeach; ?>
    </div>

    <!-- Modal subir archivo -->
    <div class="modal fade" id="modalSubirApunte" tabindex="-1" aria-labelledby="modalSubirApunteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" enctype="multipart/form-data" class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalSubirApunteLabel">Subir nuevo apunte</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label for="nombre_personalizado" class="form-label">Nombre del archivo (sin extensión)</label>
                <input type="text" name="nombre_personalizado" class="form-control" placeholder="Opcional">
            </div>
            <div class="mb-3">
                <label for="carpeta_destino" class="form-label">Selecciona una carpeta</label>
                <select name="carpeta_destino" class="form-select" required>
                    <option value="">Seleccionar carpeta</option>
                    <?php foreach ($orden_carpetas as $nombre_carpeta): ?>
                        <option value="<?php echo htmlspecialchars($nombre_carpeta); ?>">
                            <?php echo htmlspecialchars($nombre_carpeta); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="apunte" class="form-label">Archivo (PNG o PDF)</label>
                <input type="file" name="apunte" class="form-control" accept=".png,.pdf" required>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success"><i class="bi bi-cloud-arrow-up"></i> Subir</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
        </form>
    </div>
    </div>

    <!-- Modal crear carpeta -->
    <div class="modal fade" id="modalCrearCarpeta" tabindex="-1" aria-labelledby="modalCrearCarpetaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalCrearCarpetaLabel">Crear nueva carpeta</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label for="nueva_carpeta" class="form-label">Nombre de la carpeta</label>
                <input type="text" name="nueva_carpeta" class="form-control" required>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="bi bi-folder-plus"></i> Crear</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
        </form>
    </div>
    </div> 
    
<script>
document.querySelector('input[name="apunte"]').addEventListener('change', function () {
    const archivo = this.files[0];
    if (!archivo) return;
    const inputNombre = document.querySelector('input[name="nombre_personalizado"]');
    const nombreSinExtension = archivo.name.replace(/\.[^/.]+$/, '');
    if (inputNombre && inputNombre.value.trim() === '') {
        inputNombre.value = nombreSinExtension;
    }
});
</script>

<script>
function cambiarCarpeta(nombreCarpeta) {
    fetch('get_apuntes.php?carpeta=' + encodeURIComponent(nombreCarpeta))
        .then(res => res.json())
        .then(lista => {
            if (!lista.length) return;

            const nuevoArchivo = lista[0]; // Primer archivo de la nueva carpeta
            const nuevoUrl = `uploads/apuntes/${nombreCarpeta}/${nuevoArchivo}`;
            const visor = document.querySelector('.modal.show embed');
            const titulo = document.querySelector('.modal.show .modal-title');

            if (visor) visor.setAttribute('src', nuevoUrl);
            if (titulo) titulo.textContent = nuevoArchivo;

            // Actualiza botones de navegación
            visor.setAttribute('data-carpeta', nombreCarpeta);
            visor.setAttribute('data-archivo', nuevoArchivo);
        });
}

function navegarPdf(carpeta, actualArchivo, direccion) {
    fetch('get_apuntes.php?carpeta=' + encodeURIComponent(carpeta))
        .then(res => res.json())
        .then(lista => {
            const index = lista.indexOf(actualArchivo);
            if (index === -1) return;

            const nuevoIndex = index + direccion;
            if (nuevoIndex >= 0 && nuevoIndex < lista.length) {
                const nuevoArchivo = lista[nuevoIndex];
                const nuevoUrl = `uploads/apuntes/${carpeta}/${nuevoArchivo}`;
                const visor = document.querySelector('.modal.show embed');
                const titulo = document.querySelector('.modal.show .modal-title');

                if (visor) {
                    visor.setAttribute('src', nuevoUrl);
                    visor.setAttribute('data-archivo', nuevoArchivo);
                }
                if (titulo) titulo.textContent = nuevoArchivo;
            }
        });
}

// Simula md5 para el ID del visor (no es necesario si tienes IDs estáticos)
function md5(str) {
    return str.split('').reduce((a,b)=>((a<<5)-a)+b.charCodeAt(0),0).toString(16);
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
