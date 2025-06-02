<?php
$sql = "SELECT 
            i.id,
            i.fecha,
            p.nombre as paciente_nombre,
            p.apellidos as paciente_apellidos,
            d.nombre as terapeuta_nombre,
            d.apellidos as terapeuta_apellidos
        FROM informes i
        JOIN usuarios p ON i.paciente_id = p.id
        JOIN usuarios d ON i.terapeuta_id = d.id
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
                    <th class="px-4 py-2 text-left">Terapeuta</th>
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
                        <td class="px-4 py-2"><?= htmlspecialchars($row['terapeuta_nombre'] . ' ' . $row['terapeuta_apellidos']) ?></td>
                        <td class="px-4 py-2">
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

<!-- Modal de confirmación con contraseña -->
<div id="confirmDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Eliminar Informe</h2>
        <p class="mb-2 text-sm text-gray-600 dark:text-gray-300">Introduce la contraseña del terapeuta para confirmar.</p>
        <input type="password" id="deletePasswordInput" placeholder="Contraseña del terapeuta"
               class="w-full px-4 py-2 mb-4 border rounded dark:bg-gray-700 dark:text-white">
        <input type="hidden" id="deleteInformeId">
        <div class="flex justify-end space-x-2">
            <button onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-700">
                Cancelar
            </button>
            <button onclick="submitDeleteInforme()" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                Eliminar
            </button>
        </div>
        <div id="deleteError" class="text-red-500 mt-2 hidden text-sm"></div>
    </div>
</div>

<script>
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
    document.getElementById('deleteInformeId').value = id;
    document.getElementById('deletePasswordInput').value = '';
    document.getElementById('deleteError').classList.add('hidden');
    document.getElementById('confirmDeleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('confirmDeleteModal').classList.add('hidden');
}

function submitDeleteInforme() {
    const id = document.getElementById('deleteInformeId').value;
    const password = document.getElementById('deletePasswordInput').value;

    fetch('src/pages/admin/ajax/eliminar-informe.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            id: id,
            password: password
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            document.getElementById('deleteError').textContent = data.error || 'Contraseña incorrecta';
            document.getElementById('deleteError').classList.remove('hidden');
        }
    })
    .catch(() => {
        document.getElementById('deleteError').textContent = 'Error de red o del servidor.';
        document.getElementById('deleteError').classList.remove('hidden');
    });
}

</script>
