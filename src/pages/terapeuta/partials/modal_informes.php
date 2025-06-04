<?php
// Obtener todos los informes
$sql = "SELECT 
            i.id as informe_id,
            i.fecha,
            p.id as paciente_id,
            p.nombre as paciente_nombre,
            p.apellidos as paciente_apellidos,
            d.nombre as terapeuta_nombre,
            d.apellidos as terapeuta_apellidos,
            t.nombre as tratamiento_nombre
        FROM informes i
        JOIN usuarios p ON i.paciente_id = p.id
        JOIN usuarios d ON i.terapeuta_id = d.id
        LEFT JOIN tratamientos t ON i.tratamiento_id = t.id
        ORDER BY i.fecha DESC";

$res = mysqli_query($conn, $sql) or die("Error en la consulta: " . mysqli_error($conn));
?>

<!-- Modal -->
<div id="modalInformes" class="fixed inset-0 bg-gray-600 bg-opacity-50 dark:bg-gray-900 dark:bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border border-card w-3/4 max-w-4xl shadow-lg rounded-md bg-blue dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-medium text-main">Informes de Pacientes</h3>
                <button onclick="closeModal('modalInformes')" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Buscador -->
            <div class="mb-6">
                <div class="relative">
                    <input type="text" 
                           id="searchInput" 
                           class="w-full px-4 py-2 border border-card rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Buscar por paciente o tratamiento...">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Lista de Informes -->
            <div class="space-y-4 h-96 overflow-y-auto pr-2">
                <?php if (mysqli_num_rows($res) === 0): ?>
                    <p class='text-center text-secondary'>No hay informes registrados.</p>
                <?php else: ?>
                    <?php while ($row = mysqli_fetch_assoc($res)): 
                        $fecha = date("d/m/Y", strtotime($row['fecha']));
                    ?>
                        <div class="card-item bg-card dark:bg-gray-700 rounded-lg shadow p-4 hover:shadow-md transition-shadow duration-200 border border-card">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="text-lg font-semibold text-secondary">
                                        <?= htmlspecialchars($row['paciente_nombre'] . ' ' . $row['paciente_apellidos']) ?>
                                    </h4>
                                    <div class="mt-1 text-sm text-kinetic-900">
                                        <p>Tratamiento: <?= htmlspecialchars($row['tratamiento_nombre'] ?? 'Sin tratamiento') ?></p>
                                        <p>Dr. <?= htmlspecialchars($row['terapeuta_apellidos'] . ', ' . $row['terapeuta_nombre']) ?></p>
                                        <p>Fecha: <?= $fecha ?></p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="informe?id=<?= $row['informe_id'] ?>" 
                                       class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 transition-colors duration-200">
                                        Ver Informe
                                    </a>
                                    <a href="nuevoinforme?paciente_id=<?= $row['paciente_id'] ?>" 
                                       class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 transition-colors duration-200">
                                        Nuevo Informe
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Cerrar modal al hacer clic fuera
document.getElementById('modalInformes').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal('modalInformes');
    }
});

// Función de búsqueda
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase().trim();
    const cards = document.querySelectorAll('.card-item');
    
    cards.forEach(card => {
        const cardText = card.textContent.toLowerCase();
        if (cardText.includes(searchTerm)) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
});
</script> 