<?php
// Obtener todos los usuarios
$sql = "SELECT id, nombre, apellidos, email, tipo FROM usuarios ORDER BY apellidos, nombre";
$result = mysqli_query($conn, $sql);
?>

<div class="mb-4">
    <div class="relative">
        <input type="text" 
               id="searchUsuariosInput" 
               class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-white"
               placeholder="Buscar usuarios...">
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
                    <th class="px-4 py-2 text-left">Apellidos</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2 text-left">Tipo</th>
                    <th class="px-4 py-2 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr class="border-b border-card user-row">
                        <td class="px-4 py-2"><?= htmlspecialchars($row['nombre']) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($row['apellidos']) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($row['email']) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($row['tipo']) ?></td>
                        <td class="px-4 py-2">
                            <button onclick="deleteUser(<?= $row['id'] ?>)" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700">Eliminar</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p class="text-gray-500 dark:text-gray-400">No se encontraron usuarios.</p>
<?php endif; ?>

<script>
// Función de búsqueda dinámica
document.getElementById('searchUsuariosInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase().trim();
    const rows = document.querySelectorAll('.user-row');
    
    if (searchTerm === '') {
        // Si el buscador está vacío, mostrar todos
        rows.forEach(row => row.style.display = '');
        return;
    }

    // Dividir el término de búsqueda en palabras
    const searchWords = searchTerm.split(/\s+/);
    
    rows.forEach(row => {
        const rowText = row.textContent.toLowerCase();
        // Verificar si todas las palabras del término de búsqueda están en el texto
        const matches = searchWords.every(word => rowText.includes(word));
        row.style.display = matches ? '' : 'none';
    });
});

function deleteUser(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
        // Aquí puedes agregar la lógica para eliminar el usuario mediante AJAX
        console.log('Eliminar usuario con ID:', id);
    }
}
</script> 