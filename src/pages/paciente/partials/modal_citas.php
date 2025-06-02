<?php
// Verificar la conexión
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Procesar el formulario si se ha enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tratamiento_id = $_POST['tratamiento_id'];
    $terapeuta_id = $_POST['terapeuta_id'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $motivo = $_POST['motivo'] ?? '';
    $paciente_id = $_SESSION['id'];

    // Combinar fecha y hora
    $fecha_hora = $fecha . ' ' . $hora;

    // Insertar la cita
    $sql = "INSERT INTO citas (paciente_id, terapeuta_id, tratamiento_id, fecha, motivo, estado) 
            VALUES (?, ?, ?, ?, ?, 'pendiente')";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iiiss", $paciente_id, $terapeuta_id, $tratamiento_id, $fecha_hora, $motivo);
    
    if (mysqli_stmt_execute($stmt)) {
        $cita_id = mysqli_insert_id($conn);
        // Generar URL para el QR
        $url = "https://reflexiokine.es/cita.php?id={$cita_id}";
        require_once __DIR__ . '/../../../../components/qr.php';
        $qr_code = generateQR($url);
        
        // Mostrar panel de éxito con QR
        echo "<div id='successPanel' class='fixed inset-0 bg-blue bg-opacity-50 dark:bg-gray-900 dark:bg-opacity-50 overflow-y-auto h-full w-full opacity-0 transition-opacity duration-300'>
                <div class='relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800 transform scale-95 transition-transform duration-300'>
                    <div class='mt-3 text-center'>
                        <div class='mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100'>
                            <svg class='h-6 w-6 text-green-600' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7'></path>
                            </svg>
                        </div>
                        <h3 class='text-lg leading-6 font-medium text-gray-900 dark:text-white mt-4'>¡Cita Agendada con Éxito!</h3>
                        <div class='mt-4'>
                            <p class='text-sm text-gray-500 dark:text-gray-400'>Tu código QR para la cita:</p>
                            <div class='mt-2'>
                                <img src='{$qr_code}' alt='QR Code' class='mx-auto' />
                            </div>
                            <p class='mt-4 text-sm text-gray-500 dark:text-gray-400'>Guarda este código QR para tu cita</p>
                        </div>
                        <div class='mt-6 space-y-3'>
                            <button onclick='enviarQRPorCorreo({$cita_id})' 
                                    class='w-full px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors'>
                                <svg class='inline-block w-5 h-5 mr-2' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                    <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z' />
                                </svg>
                                Enviar por correo
                            </button>
                            <button onclick='closeSuccessPanel()' 
                                    class='w-full px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors'>
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>";
        exit;
    } else {
        $error = "Error al agendar la cita: " . mysqli_error($conn);
    }
}

// Obtener los tratamientos disponibles
$sql = "SELECT * FROM tratamientos ORDER BY nombre";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error en la consulta de tratamientos: " . mysqli_error($conn));
}

$tratamientos = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Obtener los terapeutas disponibles
$sql = "SELECT id, nombre, apellidos FROM usuarios WHERE tipo = 'terapeuta' ORDER BY apellidos, nombre";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error en la consulta de terapeutas: " . mysqli_error($conn));
}

$terapeutas = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<!-- Modal para agendar citas -->
<div id="modalCitas" class="fixed inset-0 bg-gray-600 bg-opacity-50 dark:bg-gray-900 dark:bg-opacity-50 hidden overflow-y-auto h-full w-full max-h-screen">
    <div class="relative top-20 mx-auto p-5 border border-card w-3/4 max-w-4xl shadow-lg rounded-md bg-blue dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-medium text-main">Agendar Nueva Cita</h3>
                <button onclick="closeModal('modalCitas')" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="citaForm" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="tratamiento" class="block text-sm font-medium text-secondary">Tratamiento</label>
                        <select id="tratamiento" name="tratamiento_id" required
                                class="mt-1 block w-full px-4 py-2 border border-card rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                            <option value="">Seleccione un tratamiento</option>
                            <?php foreach ($tratamientos as $t): ?>
                                <option value="<?= $t['id'] ?>" 
                                        data-duracion="<?= $t['duracion'] ?>"
                                        data-precio="<?= $t['precio'] ?>">
                                    <?= htmlspecialchars($t['nombre']) ?> - $<?= number_format($t['precio'], 2) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="terapeuta" class="block text-sm font-medium text-secondary">terapeuta</label>
                        <select id="terapeuta" name="terapeuta_id" required
                                class="mt-1 block w-full px-4 py-2 border border-card rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                            <option value="">Seleccione un terapeuta</option>
                            <?php foreach ($terapeutas as $d): ?>
                                <option value="<?= $d['id'] ?>">
                                    Dr. <?= htmlspecialchars($d['apellidos'] . ', ' . $d['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="fecha" class="block text-sm font-medium text-secondary">Fecha</label>
                        <input type="date" id="fecha" name="fecha" required
                               min="<?= date('Y-m-d') ?>"
                               class="mt-1 block w-full px-4 py-2 border border-card rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label for="hora" class="block text-sm font-medium text-secondary mb-1">Hora</label>
                        <select id="hora" name="hora" required class="border rounded px-3 py-2 bg-white text-black">
                            <option value="">Seleccione una hora</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="motivo" class="block text-sm font-medium text-secondary mb-1">Motivo de la cita</label>
                        <textarea id="motivo" name="motivo" rows="3" class="w-full px-3 py-2 border border-card rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Describe brevemente el motivo de tu cita..."></textarea>
                    </div>
                </div>

                <div class="bg-card dark:bg-gray-700 p-4 rounded-lg border border-card">
                    <h4 class="font-medium text-secondary mb-2">Resumen de la Cita</h4>
                    <div class="space-y-2 text-sm text-kinetic-900">
                        <p><span class="font-medium">Duración:</span> <span id="duracionCita">-</span> minutos</p>
                        <p><span class="font-medium">Precio:</span> $<span id="precioCita">0.00</span></p>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeModal('modalCitas')"
                            class="px-4 py-2 text-sm font-medium text-secondary bg-card border border-card rounded-lg hover:bg-blue-50 transition">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition">
                        Agendar Cita
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Horas disponibles (de 9 AM a 8 PM, cada hora)
const horasDisponibles = [
    '09:00', '10:00', '11:00', '12:00',
    '13:00', '14:00', '15:00', '16:00',
    '17:00', '18:00', '19:00'
];

// Al cambiar fecha o terapeuta, actualiza opciones de hora
function actualizarHoras() {
    const fecha = document.getElementById('fecha').value;
    const terapeutaId = document.querySelector('select[name="terapeuta_id"]').value;
    const horaSelect = document.getElementById('hora');

    horaSelect.innerHTML = '<option value="">Cargando horas...</option>';

    if (!fecha || !terapeutaId) return;

    fetch(`src/ajax/horas_disponibles.php?fecha=${fecha}&terapeuta_id=${terapeutaId}`)
        .then(res => res.json())
        .then(ocupadas => {
            horaSelect.innerHTML = '<option value="">Selecciona hora</option>';
            horasDisponibles.forEach(hora => {
                const option = document.createElement('option');
                option.value = hora;
                option.textContent = hora;
                if (ocupadas.includes(hora)) {
                    option.disabled = true;
                    option.textContent += ' (ocupada)';
                    option.style.color = '#888';
                }
                horaSelect.appendChild(option);
            });
        });
}

// Eventos
document.getElementById('fecha').addEventListener('change', actualizarHoras);
document.querySelector('select[name="terapeuta_id"]').addEventListener('change', actualizarHoras);

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Cerrar modal al hacer clic fuera
document.getElementById('modalCitas').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal('modalCitas');
    }
});

// Actualizar resumen cuando se selecciona un tratamiento
document.getElementById('tratamiento').addEventListener('change', function() {
    const option = this.options[this.selectedIndex];
    const duracion = option.dataset.duracion || '-';
    const precio = option.dataset.precio || '0.00';
    
    document.getElementById('duracionCita').textContent = duracion;
    document.getElementById('precioCita').textContent = precio;
});

// Función para cerrar el panel de éxito
function closeSuccessPanel() {
    const panel = document.getElementById('successPanel');
    panel.style.opacity = '0';
    panel.querySelector('.relative').style.transform = 'scale(0.95)';
    setTimeout(() => {
        panel.remove();
        window.location.reload();
    }, 300);
}

// Función para enviar QR por correo
function enviarQRPorCorreo(citaId) {
    fetch('ajax/enviar_qr.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ cita_id: citaId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('QR enviado correctamente a tu correo');
        } else {
            alert('Error al enviar el QR: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error al enviar el QR. Por favor, intente nuevamente.');
        console.error('Error:', error);
    });
}

// Modificar el manejo del envío del formulario
document.getElementById('citaForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Enviar los datos al servidor
    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(html => {
        // Insertar el panel de éxito en el DOM
        document.body.insertAdjacentHTML('beforeend', html);
        // Activar la animación
        const panel = document.getElementById('successPanel');
        setTimeout(() => {
            panel.style.opacity = '1';
            panel.querySelector('.relative').style.transform = 'scale(1)';
        }, 10);
    })
    .catch(error => {
        alert('Error al agendar la cita. Por favor, intente nuevamente.');
        console.error('Error:', error);
    });
});
</script> 