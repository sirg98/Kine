<?php
$doctor_id = $_SESSION['id'] ?? null;
if (!$doctor_id) {
    echo '<div class="p-4 text-red-600">No autenticado.</div>';
    exit;
}
// Fecha seleccionada (por defecto hoy)
$fecha = $_GET['fecha'] ?? date('Y-m-d');
// Obtener citas del doctor para la fecha seleccionada
$sql = "SELECT c.*, p.nombre AS paciente_nombre, p.apellidos AS paciente_apellidos, t.nombre AS tratamiento_nombre
        FROM citas c
        JOIN usuarios p ON c.paciente_id = p.id
        JOIN tratamientos t ON c.tratamiento_id = t.id
        WHERE c.doctor_id = ? AND DATE(c.fecha) = ?
        ORDER BY c.fecha ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('is', $doctor_id, $fecha);
$stmt->execute();
$result = $stmt->get_result();
$citas = $result->fetch_all(MYSQLI_ASSOC);
?>
<div id="modalGestionarCitas" class="fixed inset-0 bg-gray-600 bg-opacity-50 dark:bg-gray-900 dark:bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-0 border border-card w-full max-w-3xl shadow-lg rounded-md bg-blue dark:bg-gray-800 flex flex-col min-h-[500px]">
        <div class="px-8 pt-8 pb-4 border-b border-card flex items-center justify-between">
            <h2 class="text-2xl font-bold text-main">Gestionar Citas</h2>
            <button type="button" onclick="closeModalGestionarCitas()" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="px-8 pt-4 pb-4">
            <div class="flex items-center mb-6 gap-4">
                <label for="fechaCitas" class="font-medium text-main">Día:</label>
                <input type="date" id="fechaCitas" value="<?= htmlspecialchars($fecha) ?>" class="border rounded px-2 py-1" onchange="cargarCitasPorDia(this.value)">
            </div>
            <div id="listaCitasDia">
                <?php if (empty($citas)): ?>
                    <div class="text-center text-secondary">No hay citas para este día.</div>
                <?php else: ?>
                    <?php foreach ($citas as $cita): ?>
                        <details class="mb-4 border rounded-lg bg-white dark:bg-gray-900">
                            <summary class="cursor-pointer px-4 py-2 flex justify-between items-center">
                                <span>
                                    <span class="font-semibold text-main mr-2">
                                        <?= htmlspecialchars($cita['paciente_apellidos'] . ', ' . $cita['paciente_nombre']) ?>
                                    </span>
                                    <span class="text-xs text-secondary">(<?= date('H:i', strtotime($cita['fecha'])) ?>)</span>
                                </span>
                                <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">
                                    <?= htmlspecialchars($cita['tratamiento_nombre']) ?>
                                </span>
                            </summary>
                            <div class="px-4 py-2">
                                <div class="mb-2 text-sm text-secondary">
                                    <strong>Motivo:</strong> <?= htmlspecialchars($cita['motivo'] ?? 'Sin motivo') ?><br>
                                    <strong>Estado:</strong> <?= htmlspecialchars($cita['estado']) ?>
                                </div>
                                <form method="post" onsubmit="return cancelarCita(event, <?= $cita['id'] ?>)">
                                    <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition">Cancelar cita</button>
                                </form>
                            </div>
                        </details>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script>
function openModalGestionarCitas() {
    document.getElementById('modalGestionarCitas').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeModalGestionarCitas() {
    document.getElementById('modalGestionarCitas').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
function cargarCitasPorDia(fecha) {
    fetch('partials/modal_gestionar_citas.php?fecha=' + fecha)
        .then(r => r.text())
        .then(html => {
            document.getElementById('modalGestionarCitas').outerHTML = html;
            openModalGestionarCitas();
        });
}
function cancelarCita(event, citaId) {
    event.preventDefault();
    if (!confirm('¿Seguro que deseas cancelar esta cita?')) return false;
    fetch('ajax/cancelar_cita.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'id=' + citaId
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert('Cita cancelada');
            cargarCitasPorDia(document.getElementById('fechaCitas').value);
        } else {
            alert('Error al cancelar la cita');
        }
    });
    return false;
}
</script> 