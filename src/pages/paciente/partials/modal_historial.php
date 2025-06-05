<?php
// Verificar la conexión
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Obtener historial completo de sesiones
$sql = "SELECT c.*, t.nombre as tratamiento_nombre, 
        CONCAT(d.nombre, ' ', d.apellidos) as terapeuta_nombre
        FROM citas c
        JOIN tratamientos t ON c.tratamiento_id = t.id
        JOIN usuarios d ON c.terapeuta_id = d.id AND d.tipo = 'terapeuta'
        WHERE c.paciente_id = $paciente_id 
        ORDER BY c.fecha DESC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error en la consulta de historial: " . mysqli_error($conn));
}

$historial_completo = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!-- Modal para historial completo -->
<div id="modalHistorial" class="fixed inset-0 bg-gray-600 bg-opacity-50 dark:bg-gray-900 dark:bg-opacity-50 hidden overflow-y-auto h-full w-full max-h-screen">
    <div class="relative top-20 mx-auto p-5 border border-card w-3/4 max-w-4xl shadow-lg rounded-md bg-blue dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-medium text-main">Historial Completo de Sesiones</h3>
                <button onclick="closeModalHistorial()" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Buscador -->
            <div class="mb-6">
                <div class="relative">
                    <input type="text" 
                           id="searchHistorial" 
                           class="w-full px-4 py-2 border border-card rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-card text-kinetic-900 dark:text-gray-100 border-card"
                           placeholder="Buscar por tratamiento o terapeuta...">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Lista de Sesiones -->
            <div class="space-y-4 h-96 overflow-y-auto pr-2">
                <?php if (empty($historial_completo)): ?>
                    <p class='text-center text-secondary'>No hay sesiones registradas.</p>
                <?php else: ?>
                    <?php foreach ($historial_completo as $sesion): ?>
                        <div class="card-item bg-card dark:bg-gray-700 rounded-lg shadow p-4 hover:shadow-md transition-shadow duration-200 border border-card">
                            <div class="flex justify-between items-center">
                                <div>
                                    <div class="text-lg font-semibold text-secondary">
                                        <?= date('d \d\e F, Y', strtotime($sesion['fecha'])) ?> - <?= date('H:i', strtotime($sesion['fecha'])) ?>
                                    </div>
                                    <div class="mt-1 text-sm text-kinetic-900">
                                        <p>Tratamiento: <?= htmlspecialchars($sesion['tratamiento_nombre']) ?></p>
                                        <p>terapeuta: Dr. <?= htmlspecialchars($sesion['terapeuta_nombre']) ?></p>
                                        <p>Estado: 
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                                <?= $sesion['estado'] === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 
                                                ($sesion['estado'] === 'completada' ? 'bg-green-100 text-green-800' : 
                                                'bg-red-100 text-red-800') ?>">
                                                <?= ucfirst($sesion['estado']) ?>
                                            </span>
                                        </p>
                                        <?php if (!empty($sesion['motivo'])): ?>
                                            <p class="mt-2">Notas: <?= htmlspecialchars($sesion['motivo']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <?php if ($sesion['estado'] === 'pendiente'): ?>
                                    <div class="flex items-center ml-4">
                                        <button 
                                            class="cancelar-cita-btn bg-red-600 text-white hover:bg-red-700 font-medium text-sm px-4 py-2 rounded transition"
                                            data-id="<?= $sesion['id'] ?>">
                                            Cancelar
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function openModalHistorial() {
    document.getElementById('modalHistorial').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModalHistorial() {
    document.getElementById('modalHistorial').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Cerrar modal al hacer clic fuera
document.getElementById('modalHistorial').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModalHistorial();
    }
});

// Función de búsqueda
document.getElementById('searchHistorial').addEventListener('input', function(e) {
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

// Manejar cancelación con confirmación y AJAX
document.querySelectorAll('.cancelar-cita-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const citaId = btn.dataset.id;

        if (confirm("¿Estás seguro de que deseas cancelar esta cita?")) {
            fetch('/src/pages/paciente/ajax/cancelar_cita.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `cita_id=${encodeURIComponent(citaId)}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    btn.closest('.card-item').querySelector('span').classList.remove('bg-yellow-100', 'text-yellow-800');
                    btn.closest('.card-item').querySelector('span').classList.add('bg-red-100', 'text-red-800');
                    btn.closest('.card-item').querySelector('span').textContent = 'Cancelada';
                    btn.remove();
                } else {
                    alert(data.message || "Error al cancelar la cita.");
                }
            })
            .catch(() => alert("Error de conexión con el servidor."));
        }
    });
});

</script> 