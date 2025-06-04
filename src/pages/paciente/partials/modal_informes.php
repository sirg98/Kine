<?php
// Obtener los informes del paciente actual
$sql = "SELECT 
            i.id as informe_id,
            i.fecha,
            t.nombre as tratamiento,
            d.nombre as terapeuta_nombre,
            d.apellidos as terapeuta_apellidos
        FROM informes i
        JOIN usuarios d ON i.terapeuta_id = d.id
        LEFT JOIN tratamientos t ON i.tratamiento_id = t.id
        WHERE i.paciente_id = $paciente_id
        ORDER BY i.fecha DESC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error en la consulta de informes: " . mysqli_error($conn));
}

$informes = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!-- Modal -->
<div id="modalInformes" class="fixed inset-0 bg-gray-600 bg-opacity-50 dark:bg-gray-900 dark:bg-opacity-50 hidden overflow-y-auto h-full w-full max-h-screen">
    <div class="relative top-20 mx-auto p-5 border border-card w-3/4 max-w-4xl shadow-lg rounded-md bg-blue dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-medium text-main">Mis Informes Médicos</h3>
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
                           class="bg-card text-kinetic-900 dark:text-gray-100 w-full px-4 py-2 border border-card rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Buscar por tratamiento...">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Lista de Informes -->
            <div class="space-y-4 h-96 overflow-y-auto pr-2">
                <?php if (empty($informes)): ?>
                    <p class='text-center text-secondary'>No hay informes registrados.</p>
                <?php else: ?>
                    <?php foreach ($informes as $informe): 
                        $fecha = date("d/m/Y", strtotime($informe['fecha']));
                    ?>
                        <div class="card-item bg-card dark:bg-gray-700 rounded-lg shadow p-4 hover:shadow-md transition-shadow duration-200 border border-card">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="text-lg font-semibold text-secondary">
                                        <?= htmlspecialchars($informe['tratamiento'] ?? 'Sin tratamiento') ?>
                                    </h4>
                                    <div class="mt-1 text-sm text-kinetic-900">
                                        <p>Dr. <?= htmlspecialchars($informe['terapeuta_apellidos'] . ', ' . $informe['terapeuta_nombre']) ?></p>
                                        <p>Fecha: <?= $fecha ?></p>
                                    </div>
                                </div>
                                <a href="informe?id=<?= $informe['informe_id'] ?>" 
                                   class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 transition-colors duration-200">
                                    Ver Informe
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
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