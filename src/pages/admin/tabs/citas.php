<?php
// Obtener todas las citas con información de paciente y terapeuta
$sql = "SELECT 
            c.id,
            c.fecha,
            c.estado,
            p.nombre as paciente_nombre,
            p.apellidos as paciente_apellidos,
            d.nombre as terapeuta_nombre,
            d.apellidos as terapeuta_apellidos,
            t.nombre as tratamiento_nombre
        FROM citas c
        JOIN usuarios p ON c.paciente_id = p.id
        JOIN usuarios d ON c.terapeuta_id = d.id
        LEFT JOIN tratamientos t ON c.tratamiento_id = t.id
        ORDER BY c.fecha DESC";
$result = mysqli_query($conn, $sql);
   if (!$result) {
       die("Error en la consulta SQL: " . mysqli_error($conn));
   }

?>

<div class="mb-4">
    <div class="relative">
        <input type="text" 
               id="searchCitasInput" 
               class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-white"
               placeholder="Buscar citas...">
        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
    </div>
</div>

<?php if (mysqli_num_rows($result) > 0): ?>
    <div class="h-96 overflow-y-auto">
        <table class="w-full border-collapse dark:kinetic-900 text-secondary">
            <thead>
                <tr class="bg-gray-100 text-gray-900">
                    <th class="px-4 py-2 text-left">Fecha</th>
                    <th class="px-4 py-2 text-left">Paciente</th>
                    <th class="px-4 py-2 text-left">Terapeuta</th>
                    <th class="px-4 py-2 text-left">Tratamiento</th>
                    <th class="px-4 py-2 text-left">Estado</th>
                    <th class="px-4 py-2 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): 
                    $fecha = date("d/m/Y", strtotime($row['fecha']));
                    $estadoClass = [
                        'pendiente' => 'bg-yellow-500',
                        'completada' => 'bg-green-500',
                        'cancelada' => 'bg-red-500'
                    ][$row['estado']] ?? 'bg-gray-500';
                ?>
                    <tr class="border-b border-card cita-row">
                        <td class="px-4 py-2"><?= $fecha ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($row['paciente_nombre'] . ' ' . $row['paciente_apellidos']) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($row['terapeuta_nombre'] . ' ' . $row['terapeuta_apellidos']) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($row['tratamiento_nombre'] ?? 'Sin tratamiento') ?></td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 text-white rounded-full text-sm <?= $estadoClass ?>">
                                <?= ucfirst($row['estado']) ?>
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            <div class="flex space-x-2">
                                <button onclick="editCita(<?= htmlspecialchars(json_encode($row)) ?>)" 
                                        class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700">
                                    Modificar
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p class="text-gray-500 dark:text-gray-400">No se encontraron citas.</p>
<?php endif; ?>

<!-- Modal de Edición -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border border-card w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-medium text-main">Modificar Cita</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="editForm" class="space-y-4">
                <input type="hidden" id="editId" name="id">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha</label>
                    <input type="date" id="editFecha" name="fecha" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado</label>
                    <select id="editEstado" name="estado" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="pendiente">Pendiente</option>
                        <option value="completada">Completada</option>
                        <option value="cancelada">Cancelada</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-700">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Función de búsqueda dinámica
document.getElementById('searchCitasInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase().trim();
    const rows = document.querySelectorAll('.cita-row');
    
    if (searchTerm === '') {
        rows.forEach(row => row.style.display = '');
        return;
    }

    const searchWords = searchTerm.split(/\s+/);
    
    rows.forEach(row => {
        const rowText = row.textContent.toLowerCase();
        const matches = searchWords.every(word => rowText.includes(word));
        row.style.display = matches ? '' : 'none';
    });
});

// Funciones para el modal de edición
function editCita(cita) {
    document.getElementById('editId').value = cita.id;
    document.getElementById('editFecha').value = cita.fecha;
    document.getElementById('editEstado').value = cita.estado;
    
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

// Manejar el envío del formulario de edición
document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const id = document.getElementById('editId').value;
    const fecha = document.getElementById('editFecha').value;
    const estado = document.getElementById('editEstado').value;

    fetch('src/pages/admin/ajax/modificar-cita.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({ id, fecha, estado })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload(); // ✅ recarga la lista actualizada
        } else {
            alert('❌ Error: ' + (data.error || 'desconocido'));
        }
    });

    closeEditModal();
});

// Cerrar modal al hacer clic fuera
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});
</script> 