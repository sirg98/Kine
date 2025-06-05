<?php
require_once __DIR__ . '/../../../components/mail.php';

if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'terapeuta') {
    header('Location: /login.php');
    exit;
}

$apartados = [
    'Exploración física' => ['Comentario'],
    'Pruebas complementarias' => ['Comentario'],
    'Diagnóstico' => ['Comentario'],
    'Tratamiento propuesto' => ['Comentario'],
];

// Obtener paciente, tratamiento y cita (por GET o POST)
$paciente_id = $_GET['paciente_id'] ?? $_POST['paciente_id'] ?? '';
$tratamiento_id = $_GET['tratamiento_id'] ?? $_POST['tratamiento_id'] ?? '';
$cita_id = $_GET['cita_id'] ?? $_POST['cita_id'] ?? '';

if (!empty($cita_id)) {
    $stmt = $conn->prepare("SELECT estado FROM citas WHERE id = ?");
    $stmt->bind_param('i', $cita_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cita = $result->fetch_assoc();
    function mostrarError($mensaje) {
        echo "
        <div class='min-h-screen flex flex-col items-center justify-center bg-blue px-4 text-center'>
            <div class='bg-red-100 text-red-800 p-6 rounded shadow max-w-md w-full'>
                <p class='text-lg font-semibold mb-4'>$mensaje</p>
                <a href='javascript:history.back()' class='inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold transition'>
                    Volver
                </a>
            </div>
        </div>
        ";
        exit;
    }
    
    if (!$cita) {
        mostrarError("❌ La cita no existe.");
    }
    
    if ($cita['estado'] === 'completada') {
        mostrarError("⚠️ Esta cita ya ha sido completada. No se puede registrar otro informe.");
    }
}    

$terapeuta_id = $_SESSION['id'];
$msg = '';

// Solo guardar si el usuario ha enviado el formulario con el botón de guardar
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['guardar_informe'])
) {
    // Construir el contenido
    $contenido = '';
    $contenido .= 'Observaciones iniciales: ' . ($_POST['observaciones_iniciales'] ?? '') . ' - ' . trim($_POST['comentario_inicial'] ?? '') . '; ';
    foreach ($apartados as $apartado => $subs) {
        $key = 'comentario_' . strtolower(str_replace(' ', '_', $apartado));
        $contenido .= $apartado . ': ' . trim($_POST[$key] ?? '') . '; ';
    }
    $contenido .= 'Observaciones finales: ' . ($_POST['observaciones_finales'] ?? '') . ' - ' . trim($_POST['comentario_final'] ?? '') . ';';
    $fecha = date('Y-m-d H:i:s');

    // Insertar en la base de datos
    $sql = "INSERT INTO informes (paciente_id, terapeuta_id, tratamiento_id, contenido, fecha) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iiiss', $paciente_id, $terapeuta_id, $tratamiento_id, $contenido, $fecha);
    if ($stmt->execute()) {
        $informe_id = $stmt->insert_id;
        // Si hay cita_id, marcar la cita como completada
        if (!empty($cita_id)) {
            $update = $conn->prepare("UPDATE citas SET estado='completada' WHERE id=?");
            $update->bind_param('i', $cita_id);
            $update->execute();
        }
    
        // Enviar correo al paciente notificando informe
        $paciente_stmt = $conn->prepare("SELECT nombre, email FROM usuarios WHERE id = ?");
        $paciente_stmt->bind_param('i', $paciente_id);
        $paciente_stmt->execute();
        $paciente_result = $paciente_stmt->get_result();
        $paciente = $paciente_result->fetch_assoc();
    
        if ($paciente && !empty($paciente['email'])) {
            $subject = 'Tu informe estará disponible pronto en ReflexioKine';
            $subject = '📝 Tu informe estará disponible en ReflexioKineTP';
            $body = "
                <h2 style='color: #0c4a6e;'>🩺 ¡Nuevo informe registrado!</h2>
                <p>Hola <strong>{$paciente['nombre']}</strong>,</p>

                <p>Tu terapeuta ha registrado un nuevo informe tras tu cita reciente.</p>

                <p style='margin: 15px 0;'>
                    📄 <strong>¿Dónde verlo?</strong><br>
                    Puedes acceder al contenido del informe desde tu panel de paciente en los próximos minutos.
                </p>
                <p style='margin: 15px 0;'>
                    💬 <strong>¿Tienes dudas?</strong><br>
                    Puedes escribir a tu terapeuta usando el <strong>chat privado</strong> desde el panel, o bien agendar una nueva cita cuando lo necesites:
                </p>
                <p>
                    👉 <a href='https://reflexiokine.es/paciente' target='_blank' style='color: #1d4ed8; text-decoration: underline;'>Agendar nueva cita</a>
                </p>
                <p style='margin-top: 30px;'>Gracias por confiar en nosotros.<br><strong>El equipo de ReflexioKineTP</strong></p>
            ";

    
            try {
                enviarEmail($paciente['email'], $subject, $body);
            } catch (Exception $e) {
                error_log("❌ Error al enviar el correo de informe: " . $e->getMessage());
            }
        }
    
        // Mostrar mensaje al terapeuta
        echo "<script>window.location.href = '/informe?id=$informe_id';</script>";
        exit;
    } else {
        $msg = '<div class="bg-red-100 text-red-800 p-2 rounded mb-4">Error al guardar el informe: ' . htmlspecialchars($stmt->error) . '</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Informe</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        details > summary svg.flecha {
            transition: transform 0.2s;
        }
        details[open] > summary svg.flecha {
            transform: rotate(90deg);
        }
    </style>
</head>
<body class="bg-blue-light min-h-screen flex flex-col">
    <main class="container mx-auto px-4 py-10 max-w-2xl flex-1 flex flex-col">
        <?php if ($msg) echo $msg; ?>
        <?php if ($cita_id): ?>
    <div id="qr-auth-container" class="flex flex-col items-center justify-center p-6 bg-white rounded-lg shadow border border-blue-300 mb-6">
        <h2 class="text-lg font-semibold mb-4 text-center text-gray-800">Verifica la cita escaneando el QR del paciente</h2>
        <div id="qr-reader" class="w-full"></div>
        <p id="qr-message" class="mt-4 text-sm text-gray-600">Esperando escaneo...</p>
        <button id="cambiar-camara" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
            Cambiar cámara
        </button>
        <button id="desbloqueo-manual" class="mt-2 px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition">
            Desbloqueo manual
        </button>
    </div>
    <div id="modal-manual" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
        <h2 class="text-lg font-semibold mb-4 text-center">Introduce la clave de desbloqueo</h2>
        <input type="password" id="clave-manual" placeholder="Clave de la cita" class="w-full border rounded px-3 py-2 mb-4">
        <div class="flex justify-end gap-2">
            <button id="cancelar-manual" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</button>
            <button id="confirmar-manual" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Desbloquear</button>
        </div>
        <p id="manual-error" class="text-red-600 mt-2 text-sm hidden">Clave incorrecta</p>
    </div>
</div>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const citaEsperada = "<?= $cita_id ?>";
    const qrContainer = document.getElementById('qr-auth-container');
    const form = document.querySelector("form[method='POST']");
    const message = document.getElementById("qr-message");
    const switchButton = document.getElementById("cambiar-camara");
    const btnDesbloqueo = document.getElementById('desbloqueo-manual');
    const modalManual = document.getElementById('modal-manual');
    const claveInput = document.getElementById('clave-manual');
    const btnCancelar = document.getElementById('cancelar-manual');
    const btnConfirmar = document.getElementById('confirmar-manual');
    const errorManual = document.getElementById('manual-error');

btnDesbloqueo.addEventListener('click', () => {
    modalManual.classList.remove('hidden');
    claveInput.value = '';
    errorManual.classList.add('hidden');
});

btnCancelar.addEventListener('click', () => {
    modalManual.classList.add('hidden');
});

btnConfirmar.addEventListener('click', () => {
    const clave = claveInput.value.trim();
    if (!clave) return;

    fetch('/src/pages/terapeuta/ajax/verificar_manual.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ cita_id: citaEsperada, clave })
    })
    .then(r => r.json())
    .then(data => {
        if (data.valido) {
            modalManual.classList.add('hidden');
            message.textContent = "✅ Cita verificada manualmente.";
            qrContainer.remove();
            form.style.display = "block";
            qrScanner.stop().then(() => qrScanner.clear());
        } else {
            errorManual.classList.remove('hidden');
        }
    })
    .catch(() => {
        errorManual.textContent = "Error de conexión.";
        errorManual.classList.remove('hidden');
    });
});

    form.style.display = "none";

    const qrScanner = new Html5Qrcode("qr-reader");
    let cameraDevices = [];
    let currentCameraIndex = 0;

    function iniciarEscaner(cameraId) {
        qrScanner.start(
            cameraId,
            { fps: 30, qrbox: 400 },
            qrCodeMessage => {
                const url = new URL(qrCodeMessage, window.location.origin);
                const idParam = url.searchParams.get("id");

                if (idParam === citaEsperada) {
                    message.textContent = "✅ Cita verificada correctamente. Puedes rellenar el informe.";
                    qrContainer.remove();
                    form.style.display = "block";
                    qrScanner.stop().then(() => qrScanner.clear());
                } else {
                    message.textContent = "❌ QR incorrecto. Asegúrate de escanear el QR correcto.";
                }
            },
            error => {
                message.textContent = "⛔ No se reconoce ningún QR válido, intenta enfocar mejor.";
            }
        );
    }

    Html5Qrcode.getCameras().then(devices => {
        if (devices.length === 0) {
            message.textContent = "No se encontró ninguna cámara.";
            return;
        }

        cameraDevices = devices;

        // Buscar cámara trasera primero
        const indexTrasera = devices.findIndex(d =>
            d.label.toLowerCase().includes("back") || d.label.toLowerCase().includes("rear")
        );
        currentCameraIndex = indexTrasera >= 0 ? indexTrasera : 0;

        iniciarEscaner(cameraDevices[currentCameraIndex].id);

        switchButton.addEventListener("click", () => {
            qrScanner.stop().then(() => {
                qrScanner.clear();
                currentCameraIndex = (currentCameraIndex + 1) % cameraDevices.length;
                iniciarEscaner(cameraDevices[currentCameraIndex].id);
            });
        });

        if (cameraDevices.length < 2) {
            switchButton.style.display = "none";
        }
    }).catch(err => {
        message.textContent = "Error al iniciar el lector QR: " + err;
    });
});
</script>

    <?php endif; ?>

        <form method="POST" class="flex flex-col gap-8 bg-white border border-card rounded-lg shadow-lg p-8">
            <input type="hidden" name="paciente_id" value="<?= htmlspecialchars($paciente_id) ?>">
            <input type="hidden" name="tratamiento_id" value="<?= htmlspecialchars($tratamiento_id) ?>">
            <input type="hidden" name="cita_id" value="<?= htmlspecialchars($cita_id) ?>">
            <input type="hidden" name="guardar_informe" value="1">
            <!-- Encabezado -->
            <div class="border border-gray-300 rounded-lg p-4 mb-4">
                <h1 class="text-2xl font-bold text-center">Nuevo Informe Médico</h1>
            </div>

            <!-- Observaciones iniciales -->
            <div class="border border-gray-300 rounded-lg p-4">
                <label for="observaciones_iniciales" class="block font-semibold mb-2">Observaciones iniciales</label>
                <select id="observaciones_iniciales" name="observaciones_iniciales" class="w-full border rounded px-3 py-2 mb-2">
                    <option value="">Selecciona una opción</option>
                    <option value="bien">Bien</option>
                    <option value="molestias">Molestias</option>
                    <option value="dolor">Dolor</option>
                    <option value="fatiga">Fatiga</option>
                    <option value="otro">Otro</option>
                </select>
                <textarea name="comentario_inicial" placeholder="Comenta observaciones iniciales..." class="w-full border rounded px-3 py-2"></textarea>
            </div>

            <!-- Apartados principales -->
            <?php foreach ($apartados as $apartado => $subs): ?>
            <details class="border border-gray-300 rounded-lg">
                <summary class="flex items-center gap-2 px-4 py-3 cursor-pointer select-none">
                    <svg class="w-5 h-5 flecha text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="font-semibold text-lg text-gray-800 flex items-center gap-2">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        <?= htmlspecialchars($apartado) ?>
                    </span>
                </summary>
                <div class="px-4 pb-4 pt-2 flex flex-col gap-2">
                <?php foreach ($subs as $sub): ?>
                    <textarea name="comentario_<?= strtolower(str_replace(' ', '_', $apartado)) ?>" placeholder="<?= $sub ?>..." class="w-full border rounded px-3 py-2 mt-2"></textarea>
                <?php endforeach; ?>
                </div>
            </details>
            <?php endforeach; ?>

            <!-- Observaciones finales -->
            <div class="border border-gray-300 rounded-lg p-4">
                <label for="observaciones_finales" class="block font-semibold mb-2">Observaciones finales</label>
                <select id="observaciones_finales" name="observaciones_finales" class="w-full border rounded px-3 py-2 mb-2">
                    <option value="">Selecciona una opción</option>
                    <option value="mejor">Mejor</option>
                    <option value="igual">Igual</option>
                    <option value="peor">Peor</option>
                    <option value="otro">Otro</option>
                </select>
                <textarea name="comentario_final" placeholder="Comenta observaciones finales..." class="w-full border rounded px-3 py-2"></textarea>
            </div>

            <div class="flex justify-center mt-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">Guardar Informe</button>
            </div>
        </form>
    </main>
</body>
</html> 