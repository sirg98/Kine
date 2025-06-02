<?php
$base_dir = 'src/pages/terapeuta/uploads/apuntes/';
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

// Crear nueva carpeta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nueva_carpeta'])) {
    $nombre = preg_replace('/[^a-zA-Z0-9_-]/', '_', $_POST['nueva_carpeta']);
    $ruta = __DIR__ . '/uploads/apuntes/' . $nombre;
    
    // Verificar si ya existe una carpeta con ese nombre
    if (is_dir($ruta)) {
        $msg = "Ya existe una carpeta con ese nombre.";
    } else {
        mkdir($ruta, 0777, true);
        $msg = "Carpeta '$nombre' creada.";
    }
}

// Renombrar carpeta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['renombrar_carpeta'])) {
    $actual = $_POST['carpeta_actual'];
    $nuevo = preg_replace('/[^a-zA-Z0-9_-]/', '_', $_POST['carpeta_nueva']);
    $ruta_actual = __DIR__ . '/uploads/apuntes/' . $actual;
    $ruta_nueva = __DIR__ . '/uploads/apuntes/' . $nuevo;

    if (is_dir($ruta_actual)) {
        if (is_dir($ruta_nueva)) {
            $msg = "Ya existe una carpeta con ese nombre.";
        } else {
            rename($ruta_actual, $ruta_nueva);
            $orden = getCarpetasOrdenadas($base_dir, $orden_path);
            $index = array_search($actual, $orden);
            if ($index !== false) {
                $orden[$index] = $nuevo;
                guardarOrden($orden, $orden_path);
            }
            $msg = "Carpeta renombrada correctamente.";
        }
    } else {
        $msg = "La carpeta original no existe.";
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
        $upload_path = __DIR__ . '/uploads/apuntes/' . rtrim($carpeta_destino, '/') . '/';

        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        $dest = $upload_path . $safe_name;
        
        // Verificar si ya existe un archivo con ese nombre
        if (file_exists($dest)) {
            $msg = 'Ya existe un archivo con ese nombre en esta carpeta.';
        } else {
            if (move_uploaded_file($file['tmp_name'], $dest)) {
                $msg = 'Archivo subido correctamente.';
            } else {
                $msg = 'Error al mover el archivo.';
            }
        }
    } else {
        $msg = 'Solo se permiten archivos PNG o PDF.';
    }
}

$orden_carpetas = getCarpetasOrdenadas($base_dir, $orden_path);

// Eliminar archivo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_archivo'])) {
    $archivo_relativo = $_POST['eliminar_archivo'];
    $archivo_a_eliminar = __DIR__ . '/uploads/apuntes/' . $archivo_relativo;
    
    if (file_exists($archivo_a_eliminar)) {
        unlink($archivo_a_eliminar);
        $msg = 'Archivo eliminado correctamente.';
    } else {
        $msg = 'Archivo no encontrado: ' . $archivo_a_eliminar;
    }
}

// Eliminar carpeta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_carpeta'])) {
    $carpeta = basename($_POST['eliminar_carpeta']);
    $ruta = __DIR__ . '/uploads/apuntes/' . $carpeta;

    if (is_dir($ruta)) {
        // Eliminar todos los archivos dentro de la carpeta
        $archivos = glob($ruta . '/*');
        foreach ($archivos as $archivo) {
            if (is_file($archivo)) {
                unlink($archivo);
            }
        }
        // Eliminar la carpeta
        rmdir($ruta);

        // Actualizar orden
        $orden = getCarpetasOrdenadas($base_dir, $orden_path);
        $orden = array_values(array_filter($orden, fn($c) => $c !== $carpeta));
        guardarOrden($orden, $orden_path);

        $msg = "Carpeta eliminada correctamente.";
    } else {
        $msg = "La carpeta no existe: " . $ruta;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apuntes - Portal del Médico</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="/kine/tailwind-colors.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .apunte-thumb {
            max-width: 100px;
            max-height: 100px;
            object-fit: contain;
            border-radius: 6px;
            box-shadow: 0 2px 8px #0002;
        }
        details > summary svg.flecha-carpeta {
            transition: transform 0.2s;
        }
        details[open] > summary svg.flecha-carpeta {
            transform: rotate(90deg);
        }
    </style>
</head>
<body class="bg-blue-light text-gray-900 min-h-screen">
    <main class="container mx-auto px-4 py-10">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-kinetic-900">
                <i class="bi bi-journal-richtext"></i> Apuntes
            </h1>
            <div class="flex gap-2">
                <button class="bg-kinetic-500 hover:bg-kinetic-600 text-white px-4 py-2 rounded-lg flex items-center gap-2" onclick="document.getElementById('modalSubirApunte').classList.remove('hidden')">
                    <i class="bi bi-upload"></i> Añadir apunte
                </button>
                <button class="bg-card text-secondary border border-card hover:bg-blue-50 px-4 py-2 rounded-lg flex items-center gap-2" onclick="document.getElementById('modalCrearCarpeta').classList.remove('hidden')">
                    <i class="bi bi-folder-plus"></i> Nueva carpeta
                </button>
            </div>
        </div>

        <?php foreach ($orden_carpetas as $nombre_carpeta): 
            $carpeta = $base_dir . $nombre_carpeta;
            $apuntes = array_filter(glob("$carpeta/*.{png,pdf}", GLOB_BRACE), 'is_file');
        ?>
            <details class="mb-6 group">
                <summary class="flex justify-between items-center bg-card text-secondary border border-card px-4 py-2 rounded-lg cursor-pointer select-none">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5 transition-transform duration-200 flecha-carpeta" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                        <span class="text-lg font-semibold text-kinetic-900"><i class="bi bi-folder-fill"></i> <?php echo htmlspecialchars($nombre_carpeta); ?></span>
                    </span>
                    <div class="flex gap-2">
                        <form method="POST" class="inline">
                            <input type="hidden" name="mover_carpeta" value="<?php echo $nombre_carpeta; ?>">
                            <input type="hidden" name="direccion" value="up">
                            <button class="bg-card text-secondary border border-card hover:bg-blue-50 px-2 py-1 rounded" title="Subir carpeta">↑</button>
                        </form>
                        <form method="POST" class="inline">
                            <input type="hidden" name="mover_carpeta" value="<?php echo $nombre_carpeta; ?>">
                            <input type="hidden" name="direccion" value="down">
                            <button class="bg-card text-secondary border border-card hover:bg-blue-50 px-2 py-1 rounded" title="Bajar carpeta">↓</button>
                        </form>
                        <button class="bg-healing-600 hover:bg-healing-700 text-white px-2 py-1 rounded" onclick="document.getElementById('modalRenombrar_<?php echo $nombre_carpeta; ?>').classList.remove('hidden')">Renombrar</button>
                        <form method="POST" class="inline" onsubmit="return eliminarCarpeta(this, event);">
                            <input type="hidden" name="eliminar_carpeta" value="<?php echo htmlspecialchars($nombre_carpeta); ?>">
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded" title="Eliminar carpeta">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </summary>
                <!-- Modal Renombrar Carpeta -->
                <div id="modalRenombrar_<?php echo $nombre_carpeta; ?>" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white rounded-lg p-6 w-96">
                        <form method="POST">
                            <input type="hidden" name="carpeta_actual" value="<?php echo $nombre_carpeta; ?>">
                            <h5 class="text-xl font-semibold text-kinetic-900 mb-4">Renombrar carpeta</h5>
                            <input type="text" name="carpeta_nueva" class="w-full px-3 py-2 border rounded-lg mb-4" placeholder="Nuevo nombre" required>
                            <div class="flex justify-end gap-2">
                                <button type="button" class="bg-card text-secondary border border-card hover:bg-blue-50 px-4 py-2 rounded" onclick="this.closest('.fixed').classList.add('hidden')">Cancelar</button>
                                <button type="submit" name="renombrar_carpeta" class="bg-healing-600 hover:bg-healing-700 text-white px-4 py-2 rounded">Renombrar</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3 mt-4">
                    <?php if (count($apuntes) > 0): ?>
                        <?php foreach ($apuntes as $apunte): 
                            $basename = basename($apunte);
                            $url = $carpeta . '/' . $basename;
                            $is_pdf = strtolower(pathinfo($basename, PATHINFO_EXTENSION)) === 'pdf';
                        ?>
                            <div class="bg-card text-secondary border border-card rounded-lg p-3 flex flex-col items-center">
                                <div class="flex flex-col items-center justify-center h-full relative w-full">
                                    <?php if ($is_pdf): ?>
                                        <button class="text-red-600 hover:text-red-700" onclick="openPreview('<?php echo $url; ?>', '<?php echo htmlspecialchars($basename); ?>', 'pdf')">
                                            <i class="bi bi-file-earmark-pdf text-4xl"></i>
                                        </button>
                                        <a href="<?php echo $url; ?>" target="_blank" class="mt-1 text-xs text-kinetic-700 hover:text-kinetic-900 break-words text-center">
                                            <?php echo htmlspecialchars($basename); ?>
                                        </a>
                                    <?php else: ?>
                                        <button class="text-kinetic-700 hover:text-kinetic-900" onclick="openPreview('<?php echo $url; ?>', '<?php echo htmlspecialchars($basename); ?>', 'image')">
                                            <img src="<?php echo $url; ?>" class="apunte-thumb max-h-20" alt="Apunte">
                                        </button>
                                        <span class="mt-1 text-xs text-kinetic-700 hover:text-kinetic-900 break-words text-center block">
                                            <?php echo htmlspecialchars($basename); ?>
                                        </span>
                                    <?php endif; ?>
                                    <form method="POST" onsubmit="return eliminarArchivo(this, event);" class="absolute -top-1 -right-1">
                                        <input type="hidden" name="eliminar_archivo" value="<?php echo str_replace($base_dir, '', $url); ?>">
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white p-1 rounded-full shadow-sm hover:shadow-md transition-all duration-200 text-xs">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-span-full text-center text-secondary">No hay archivos en esta carpeta.</div>
                    <?php endif; ?>
                </div>
            </details>
        <?php endforeach; ?>
    </main>

    <!-- Modal Subir Apunte -->
    <div id="modalSubirApunte" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96">
            <form method="POST" enctype="multipart/form-data">
                <h5 class="text-xl font-semibold text-kinetic-900 mb-4">Subir nuevo apunte</h5>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-kinetic-700 mb-1">Nombre del archivo (sin extensión)</label>
                    <input type="text" name="nombre_personalizado" class="w-full px-3 py-2 border rounded-lg" placeholder="Opcional">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-kinetic-700 mb-1">Selecciona una carpeta</label>
                    <select name="carpeta_destino" class="w-full px-3 py-2 border rounded-lg" required>
                        <option value="">Seleccionar carpeta</option>
                        <?php foreach ($orden_carpetas as $nombre_carpeta): ?>
                            <option value="<?php echo htmlspecialchars($nombre_carpeta); ?>">
                                <?php echo htmlspecialchars($nombre_carpeta); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-kinetic-700 mb-1">Archivo (PNG o PDF)</label>
                    <input type="file" name="apunte" class="w-full px-3 py-2 border rounded-lg" accept=".png,.pdf" required>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" class="bg-card text-secondary border border-card hover:bg-blue-50 px-4 py-2 rounded" onclick="this.closest('.fixed').classList.add('hidden')">Cancelar</button>
                    <button type="submit" class="bg-kinetic-500 hover:bg-kinetic-600 text-white px-4 py-2 rounded flex items-center gap-2">
                        <i class="bi bi-cloud-arrow-up"></i> Subir
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Crear Carpeta -->
    <div id="modalCrearCarpeta" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96">
            <form method="POST">
                <h5 class="text-xl font-semibold text-kinetic-900 mb-4">Crear nueva carpeta</h5>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-kinetic-700 mb-1">Nombre de la carpeta</label>
                    <input type="text" name="nueva_carpeta" class="w-full px-3 py-2 border rounded-lg" required>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" class="bg-card text-secondary border border-card hover:bg-blue-50 px-4 py-2 rounded" onclick="this.closest('.fixed').classList.add('hidden')">Cancelar</button>
                    <button type="submit" class="bg-card text-secondary border border-card hover:bg-blue-50 px-4 py-2 rounded flex items-center gap-2">
                        <i class="bi bi-folder-plus"></i> Crear
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Preview Modal -->
    <div id="previewModal" class="hidden fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50">
        <div class="bg-card text-secondary border border-card w-11/12 h-5/6 rounded-lg overflow-hidden">
            <div class="flex justify-between items-center p-4 border-b border-card">
                <div class="flex items-center gap-4">
                    <button onclick="navigateFile(-1)" class="bg-kinetic-500 hover:bg-kinetic-600 text-white px-3 py-1 rounded-lg flex items-center gap-1">
                        <i class="bi bi-chevron-left"></i> Anterior
                    </button>
                    <button onclick="navigateFile(1)" class="bg-kinetic-500 hover:bg-kinetic-600 text-white px-3 py-1 rounded-lg flex items-center gap-1">
                        Siguiente <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
                <h5 id="previewTitle" class="text-lg text-kinetic-900"></h5>
                <button class="text-secondary hover:text-kinetic-900" onclick="closePreview()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div id="previewContent" class="h-full flex items-center justify-center">
                <!-- Content will be dynamically inserted here -->
            </div>
        </div>
    </div>

    <script>
    let currentFiles = [];
    let currentIndex = 0;
    let currentType = '';

    function openPreview(url, filename, type, files = [], index = 0) {
        currentFiles = files;
        currentIndex = index;
        currentType = type;

        const modal = document.getElementById('previewModal');
        const content = document.getElementById('previewContent');
        const title = document.getElementById('previewTitle');
        
        title.textContent = filename;
        
        // Clear previous content
        content.innerHTML = '';
        
        // Add new content based on type
        if (type === 'pdf') {
            content.innerHTML = `<embed src="${url}" type="application/pdf" class="w-full h-full">`;
        } else {
            content.innerHTML = `<img src="${url}" class="max-w-full max-h-full object-contain" alt="${filename}">`;
        }
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closePreview() {
        const modal = document.getElementById('previewModal');
        const content = document.getElementById('previewContent');
        
        content.innerHTML = '';
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function navigateFile(offset) {
        if (currentFiles.length === 0) return;
        
        currentIndex += offset;
        if (currentIndex < 0) currentIndex = currentFiles.length - 1;
        if (currentIndex >= currentFiles.length) currentIndex = 0;

        const file = currentFiles[currentIndex];
        openPreview(file.url, file.nombre, currentType, currentFiles, currentIndex);
    }

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (!document.getElementById('previewModal').classList.contains('hidden')) {
            if (e.key === 'ArrowLeft') {
                navigateFile(-1);
            } else if (e.key === 'ArrowRight') {
                navigateFile(1);
            } else if (e.key === 'Escape') {
                closePreview();
            }
        }
    });

    // Close preview when clicking outside
    document.getElementById('previewModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePreview();
        }
    });

    // File input name auto-fill
    document.querySelector('input[name="apunte"]').addEventListener('change', function () {
        const archivo = this.files[0];
        if (!archivo) return;
        const inputNombre = document.querySelector('input[name="nombre_personalizado"]');
        const nombreSinExtension = archivo.name.replace(/\.[^/.]+$/, '');
        if (inputNombre && inputNombre.value.trim() === '') {
            inputNombre.value = nombreSinExtension;
        }
    });

    async function eliminarArchivo(form, event) {
        event.preventDefault();
        if (!confirm('¿Seguro que quieres eliminar este archivo?')) return false;

        const formData = new FormData(form);
        try {
            const response = await fetch(window.location.href, {
                method: 'POST',
                body: formData
            });
            const text = await response.text();
            
            // Buscar el mensaje en la respuesta
            const msgMatch = text.match(/<div class="alert[^>]*>([^<]+)<\/div>/);
            if (msgMatch) {
                alert(msgMatch[1]);
            }

            // Eliminar el elemento del DOM
            const card = form.closest('.bg-card');
            card.remove();
        } catch (error) {
            alert('Error al eliminar el archivo');
        }
        return false;
    }

    async function eliminarCarpeta(form, event) {
        event.preventDefault();
        if (!confirm('¿Eliminar esta carpeta?')) return false;

        const formData = new FormData(form);
        try {
            const response = await fetch(window.location.href, {
                method: 'POST',
                body: formData
            });
            const text = await response.text();
            
            // Buscar el mensaje en la respuesta
            const msgMatch = text.match(/<div class="alert[^>]*>([^<]+)<\/div>/);
            if (msgMatch) {
                alert(msgMatch[1]);
            }

            // Eliminar la sección de la carpeta del DOM
            const carpetaSection = form.closest('.mb-6');
            carpetaSection.remove();
        } catch (error) {
            alert('Error al eliminar la carpeta');
        }
        return false;
    }
    </script>
</body>
</html>
