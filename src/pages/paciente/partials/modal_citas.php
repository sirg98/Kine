<?php
// Verificar la conexión
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Procesar el formulario si se ha enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tratamiento_id = $_POST['tratamiento_id'];
    $doctor_id = $_POST['doctor_id'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $motivo = $_POST['motivo'] ?? '';
    $paciente_id = $_SESSION['id'];

    // Combinar fecha y hora
    $fecha_hora = $fecha . ' ' . $hora;

    // Insertar la cita
    $sql = "INSERT INTO citas (paciente_id, doctor_id, tratamiento_id, fecha, motivo, estado) 
            VALUES (?, ?, ?, ?, ?, 'pendiente')";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iiiss", $paciente_id, $doctor_id, $tratamiento_id, $fecha_hora, $motivo);
    
    if (mysqli_stmt_execute($stmt)) {
        header('Location: ' . $_SERVER['PHP_SELF']);
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

// Obtener los doctores disponibles
$sql = "SELECT id, nombre, apellidos FROM usuarios WHERE tipo = 'doctor' ORDER BY apellidos, nombre";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error en la consulta de doctores: " . mysqli_error($conn));
}

$doctores = mysqli_fetch_all($result, MYSQLI_ASSOC);

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
                                class="mt-1 block w-full px-4 py-2 border border-card rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Seleccione un tratamiento</option>
                            <?php foreach ($tratamientos as $t): ?>
                                <option value="<?= $t['id'] ?>" 
                                        data-duracion="<?= $t['duracion_minutos'] ?>"
                                        data-precio="<?= $t['precio'] ?>">
                                    <?= htmlspecialchars($t['nombre']) ?> - $<?= number_format($t['precio'], 2) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="doctor" class="block text-sm font-medium text-secondary">Doctor</label>
                        <select id="doctor" name="doctor_id" required
                                class="mt-1 block w-full px-4 py-2 border border-card rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Seleccione un doctor</option>
                            <?php foreach ($doctores as $d): ?>
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
                        <select id="hora" name="hora" required class="w-full px-3 py-2 border border-card rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Selecciona una hora</option>
                            <?php foreach ($horasDisponibles as $hora): ?>
                                <option value="<?= $hora ?>"><?= $hora ?></option>
                            <?php endforeach; ?>
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
    '09:00', '10:00', '11:00', '12:00', '13:00', '14:00',
    '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'
];

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

// Cargar horas disponibles cuando se selecciona una fecha
document.getElementById('fecha').addEventListener('change', function() {
    const horaSelect = document.getElementById('hora');
    horaSelect.innerHTML = '<option value="">Seleccione una hora</option>';
    
    horasDisponibles.forEach(hora => {
        const option = document.createElement('option');
        option.value = hora;
        option.textContent = hora;
        horaSelect.appendChild(option);
    });
});

// Manejar el envío del formulario
document.getElementById('citaForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Enviar los datos al servidor
    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            // Recargar la página para mostrar la nueva cita
            window.location.reload();
        } else {
            throw new Error('Error al agendar la cita');
        }
    })
    .catch(error => {
        alert('Error al agendar la cita. Por favor, intente nuevamente.');
        console.error('Error:', error);
    });
});
</script> 