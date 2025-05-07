<?php
// Obtener todos los informes con información de paciente y doctor
$sql = "SELECT 
            i.id,
            i.fecha,
            i.contenido,
            p.nombre as paciente_nombre,
            p.apellidos as paciente_apellidos,
            d.nombre as doctor_nombre,
            d.apellidos as doctor_apellidos,
            t.nombre as tratamiento_nombre
        FROM informes i
        JOIN usuarios p ON i.paciente_id = p.id
        JOIN usuarios d ON i.doctor_id = d.id
        LEFT JOIN tratamientos t ON i.tratamiento_id = t.id
        ORDER BY i.fecha DESC";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Error en la consulta SQL: " . mysqli_error($conn));
}
?>

<div class="mb-4">
    <div class="relative">
        <input type="text" 
               id="searchInformesInput" 
               class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-white"
               placeholder="Buscar informes...">
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
                    <th class="px-4 py-2 text-left">Doctor</th>
                    <th class="px-4 py-2 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): 
                    $fecha = date("d/m/Y", strtotime($row['fecha']));
                ?>
                    <tr class="border-b border-card informe-row">
                        <td class="px-4 py-2"><?= $fecha ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($row['paciente_nombre'] . ' ' . $row['paciente_apellidos']) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($row['doctor_nombre'] . ' ' . $row['doctor_apellidos']) ?></td>
                        <td class="px-4 py-2">
                            <button onclick="showInformePasswordModal(<?= $row['id'] ?>)"
                                    class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 mr-2">
                                Ver informe
                            </button>
                            <button onclick="deleteInforme(<?= $row['id'] ?>)" 
                                    class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p class="text-gray-500 dark:text-gray-400">No se encontraron informes.</p>
<?php endif; ?>

<!-- Modal para pedir contraseña y mostrar informe -->
<div id="verInformeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border border-card w-full max-w-lg shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-medium text-main">Ver Informe</h3>
                <button onclick="closeVerInformeModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="verInformePasswordForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contraseña</label>
                    <input type="password" id="verInformePassword" name="password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                </div>
                <input type="hidden" id="verInformeId" name="id">
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeVerInformeModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-700">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700">
                        Ver
                    </button>
                </div>
            </form>
            <div id="verInformeDetalles" class="mt-6 hidden">
                <h4 class="font-semibold mb-2">Diagnóstico</h4>
                <p id="verDiagnostico" class="mb-4"></p>
                <h4 class="font-semibold mb-2">Tratamiento</h4>
                <p id="verTratamiento" class="mb-4"></p>
                <h4 class="font-semibold mb-2">Observaciones</h4>
                <p id="verObservaciones"></p>
            </div>
            <div id="verInformeError" class="text-red-500 mt-2 hidden"></div>
        </div>
    </div>
</div>

<script>
// Función de búsqueda dinámica
document.getElementById('searchInformesInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase().trim();
    const rows = document.querySelectorAll('.informe-row');
    
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

function deleteInforme(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este informe?')) {
        // Aquí puedes agregar la lógica para eliminar el informe mediante AJAX
        console.log('Eliminar informe con ID:', id);
    }
}

function showInformePasswordModal(id) {
    document.getElementById('verInformeId').value = id;
    document.getElementById('verInformePassword').value = '';
    document.getElementById('verInformeDetalles').classList.add('hidden');
    document.getElementById('verInformeError').classList.add('hidden');
    document.getElementById('verInformeModal').classList.remove('hidden');
}

function closeVerInformeModal() {
    document.getElementById('verInformeModal').classList.add('hidden');
}

document.getElementById('verInformePasswordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('verInformeId').value;
    const password = document.getElementById('verInformePassword').value;
    // AJAX para validar contraseña y obtener detalles del informe
    fetch('ajax/ver_informe.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${encodeURIComponent(id)}&password=${encodeURIComponent(password)}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('verDiagnostico').textContent = data.diagnostico;
            document.getElementById('verTratamiento').textContent = data.tratamiento;
            document.getElementById('verObservaciones').textContent = data.observaciones;
            document.getElementById('verInformeDetalles').classList.remove('hidden');
            document.getElementById('verInformeError').classList.add('hidden');
        } else {
            document.getElementById('verInformeError').textContent = data.message || 'Contraseña incorrecta o error.';
            document.getElementById('verInformeError').classList.remove('hidden');
            document.getElementById('verInformeDetalles').classList.add('hidden');
        }
    })
    .catch(() => {
        document.getElementById('verInformeError').textContent = 'Error de red o del servidor.';
        document.getElementById('verInformeError').classList.remove('hidden');
        document.getElementById('verInformeDetalles').classList.add('hidden');
    });
});
</script> 