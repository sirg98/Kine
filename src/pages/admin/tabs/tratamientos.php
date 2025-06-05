<?php
// Obtener todos los tratamientos
$sql = "SELECT id, nombre, descripcion, duracion, precio, icon, beneficios, imagen FROM tratamientos ORDER BY nombre ASC";
$result = mysqli_query($conn, $sql);
?>

<div class="mb-4">
    <div class="relative">
        <input type="text" 
               id="searchTratamientosInput" 
               class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-white"
               placeholder="Buscar tratamientos...">
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
                    <th class="px-4 py-2 text-left">Nombre</th>
                    <th class="px-4 py-2 text-left">Descripción</th>
                    <th class="px-4 py-2 text-left">Duración</th>
                    <th class="px-4 py-2 text-left">Precio</th>
                    <th class="px-4 py-2 text-left">Icono</th>
                    <th class="px-4 py-2 text-left">Beneficios</th>
                    <th class="px-4 py-2 text-left">Imagen</th>
                    <th class="px-4 py-2 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr class="border-b border-card tratamiento-row">
                        <td class="px-4 py-2"><?= htmlspecialchars($row['nombre']) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($row['descripcion']) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($row['duracion']) ?> min</td>
                        <td class="px-4 py-2"><?= number_format($row['precio'], 2) ?> €</td>

                        <td class="px-4 py-2"><?= $row['icon'] ?></td>

                        <td class="px-4 py-2 text-sm">
                            <?php
                                $beneficios = json_decode($row['beneficios'], true);
                                if (is_array($beneficios)) {
                                    echo '<ul class="list-disc pl-4">';
                                    foreach ($beneficios as $b) {
                                        echo '<li>' . htmlspecialchars($b) . '</li>';
                                    }
                                    echo '</ul>';
                                } else {
                                    echo htmlspecialchars($row['beneficios']);
                                }
                            ?>
                        </td>

                        <td class="px-4 py-2">
                            <img src="<?= htmlspecialchars($row['imagen']) ?>" alt="imagen" class="w-10 h-10 object-cover rounded shadow">
                        </td>

                        <td class="px-4 py-2">
                            <div class="flex space-x-2">
                                <button onclick='editTratamiento(<?= json_encode($row, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)' 
                                        class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700">
                                    Modificar
                                </button>
                                <button onclick="deleteTratamiento(<?= $row['id'] ?>)" 
                                        class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700">
                                    Eliminar
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p class="text-gray-500 dark:text-gray-400">No se encontraron tratamientos.</p>
<?php endif; ?>

<!-- Modal de Edición -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border border-card w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-medium text-main">Modificar Tratamiento</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="editForm" class="space-y-4">
                <input type="hidden" id="editId" name="id">

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                    <input type="text" id="editNombre" name="nombre" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción</label>
                    <textarea id="editDescripcion" name="descripcion" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duración (minutos)</label>
                    <input type="number" id="editDuracion" name="duracion" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Precio (€)</label>
                    <input type="number" step="0.01" id="editPrecio" name="precio" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Icono (SVG o clase)</label>
                    <input type="text" id="editIcon" name="icon" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Beneficios (JSON o lista)</label>
                    <textarea id="editBeneficios" name="beneficios" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ruta de la imagen</label>
                    <input type="text" id="editImagen" name="imagen" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
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
document.getElementById('searchTratamientosInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase().trim();
    const rows = document.querySelectorAll('.tratamiento-row');
    
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
function editTratamiento(tratamiento) {
    document.getElementById('editId').value = tratamiento.id;
    document.getElementById('editNombre').value = tratamiento.nombre;
    document.getElementById('editDescripcion').value = tratamiento.descripcion;
    document.getElementById('editDuracion').value = tratamiento.duracion;
    document.getElementById('editPrecio').value = tratamiento.precio;
    document.getElementById('editIcon').value = tratamiento.icon || '';
    document.getElementById('editBeneficios').value = tratamiento.beneficios || '';
    document.getElementById('editImagen').value = tratamiento.imagen || '';
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

// Manejar el envío del formulario de edición
document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());

    fetch('src/pages/admin/ajax/modificar_tratamiento.php', {
        method: 'POST',
        body: new URLSearchParams(data)
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) {
            alert('Tratamiento actualizado correctamente.');
            location.reload(); // O actualizar dinámicamente la fila
        } else {
            alert('Error al actualizar: ' + (res.error || 'desconocido'));
        }
    })
    .catch(err => {
        console.error('Error AJAX:', err);
        alert('Error de conexión al actualizar tratamiento.');
    });

    closeEditModal();
});


function deleteTratamiento(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este tratamiento?')) {
        // Aquí puedes agregar la lógica para eliminar el tratamiento mediante AJAX
        console.log('Eliminar tratamiento con ID:', id);
    }
}

// Cerrar modal al hacer clic fuera
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});

</script> 