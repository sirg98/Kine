<?php
// Obtener todos los pacientes
$sql = "SELECT 
            p.id as paciente_id,
            p.nombre as paciente_nombre,
            p.email,
            p.telefono,
            p.fecha_nacimiento,
            COUNT(i.id) as total_informes,
            MAX(i.fecha) as ultima_visita
        FROM usuarios p
        LEFT JOIN informes i ON p.id = i.paciente_id
        WHERE p.tipo = 'paciente'
        GROUP BY p.id
        ORDER BY p.nombre ASC";

$res = mysqli_query($conn, $sql) or die("Error en la consulta: " . mysqli_error($conn));
?>

<!-- Modal -->
<div id="modalPacientes" class="fixed inset-0 bg-gray-600 bg-opacity-50 dark:bg-gray-900 dark:bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border border-card w-3/4 max-w-4xl shadow-lg rounded-md bg-blue dark:bg-gray-800">
        <!-- Vista de Lista -->
        <div id="listaView">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-medium text-main">Lista de Pacientes</h3>
                    <button onclick="closeModalPacientes()" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Buscador -->
                <div class="mb-6">
                    <div class="relative">
                        <input type="text" 
                               id="searchPacientesInput" 
                               class="bg-card text-kinetic-900 dark:text-gray-100 w-full px-4 py-2 border border-card rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Buscar paciente...">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Lista de Pacientes -->
                <div class="space-y-4 h-96 overflow-y-auto pr-2">
                    <?php if (mysqli_num_rows($res) === 0): ?>
                        <p class='text-center text-secondary'>No hay pacientes registrados.</p>
                    <?php else: ?>
                        <?php while ($row = mysqli_fetch_assoc($res)): 
                            $ultima_visita = $row['ultima_visita'] ? date("d/m/Y", strtotime($row['ultima_visita'])) : 'Sin visitas';
                        ?>
                            <div class="card-item bg-card dark:bg-gray-700 rounded-lg shadow p-4 hover:shadow-md transition-shadow duration-200 border border-card">
                                <div class="flex justify-between items-center">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                <span class="bg-blue-100 text-kinetic-900 font-bold rounded-full w-10 h-10 flex items-center justify-center">
                                                    <?= strtoupper(substr($row['paciente_nombre'], 0, 2)) ?>
                                                </span>
                                            </div>
                                            <div>
                                                <h4 class="text-lg font-semibold text-secondary">
                                                    <?= htmlspecialchars($row['paciente_nombre']) ?>
                                                </h4>
                                                <div class="mt-1 text-sm text-kinetic-900">
                                                    <p><?= htmlspecialchars($row['email']) ?></p>
                                                    <p>Tel: <?= htmlspecialchars($row['telefono']) ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <div class="text-right">
                                            <p class="text-sm text-kinetic-900">Total informes: <?= $row['total_informes'] ?></p>
                                            <p class="text-sm text-kinetic-900">Última visita: <?= $ultima_visita ?></p>
                                        </div>
                                        <div class="flex space-x-2">
                                            <button onclick="showPatientDetails(<?= htmlspecialchars(json_encode($row)) ?>)" 
                                                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 transition-colors duration-200">
                                                Ver Detalles
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Vista de Detalles -->
        <div id="detallesView" class="hidden">
            <div class="mt-3">
                <div class="flex items-center mb-6">
                    <button onclick="backToList()" class="mr-4 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </button>
                    <h3 class="text-xl font-medium text-main">Detalles del Paciente</h3>
                </div>

                <div class="bg-card dark:bg-gray-700 rounded-lg shadow p-6 border border-card">
                    <div class="flex items-center space-x-4 mb-6">
                        <span id="patientAvatar" class="bg-blue-100 text-kinetic-900 font-bold rounded-full w-16 h-16 flex items-center justify-center text-xl"></span>
                        <div>
                            <h3 id="patientName" class="text-xl font-semibold text-secondary"></h3>
                            <p id="patientEmail" class="text-sm text-kinetic-900"></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-secondary mb-4">Información de Contacto</h4>
                            <div class="space-y-3">
                                <p class="text-sm text-kinetic-900">
                                    <span class="font-medium">Teléfono:</span><br>
                                    <span id="patientPhone"></span>
                                </p>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-secondary mb-4">Información Personal</h4>
                            <div class="space-y-3">
                                <p class="text-sm text-kinetic-900">
                                    <span class="font-medium">Fecha de Nacimiento:</span><br>
                                    <span id="patientBirthDate"></span>
                                </p>
                                <p class="text-sm text-kinetic-900">
                                    <span class="font-medium">Total Informes:</span><br>
                                    <span id="patientTotalReports"></span>
                                </p>
                                <p class="text-sm text-kinetic-900">
                                    <span class="font-medium">Última Visita:</span><br>
                                    <span id="patientLastVisit"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <a id="nuevoInformeBtn" href="#" 
                           class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 transition-colors duration-200">
                            Nuevo Informe
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openModalPacientes() {
    document.getElementById('modalPacientes').classList.remove('hidden');
}

function closeModalPacientes() {
    document.getElementById('modalPacientes').classList.add('hidden');
}

// Cerrar modal al hacer clic fuera
document.getElementById('modalPacientes').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModalPacientes();
    }
});

// Función de búsqueda mejorada
document.getElementById('searchPacientesInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase().trim();
    const cards = document.querySelectorAll('#modalPacientes .card-item');
    
    if (searchTerm === '') {
        // Si el buscador está vacío, mostrar todos
        cards.forEach(card => card.style.display = '');
        return;
    }

    // Dividir el término de búsqueda en palabras
    const searchWords = searchTerm.split(/\s+/);
    
    cards.forEach(card => {
        const cardText = card.textContent.toLowerCase();
        
        // Verificar si todas las palabras del término de búsqueda están en el texto
        const matches = searchWords.every(word => cardText.includes(word));
        
        if (matches) {
            card.style.display = '';
            // Resaltar el texto que coincide
            highlightText(card, searchWords);
        } else {
            card.style.display = 'none';
        }
    });
});

// Función para resaltar el texto que coincide
function highlightText(card, searchWords) {
    const elements = card.querySelectorAll('h4, p');
    elements.forEach(element => {
        let text = element.textContent;
        searchWords.forEach(word => {
            const regex = new RegExp(`(${word})`, 'gi');
            text = text.replace(regex, '<span class="bg-yellow-200 dark:bg-yellow-800">$1</span>');
        });
        element.innerHTML = text;
    });
}

// Función para mostrar detalles del paciente
function showPatientDetails(patient) {
    // Ocultar lista y mostrar detalles
    document.getElementById('listaView').classList.add('hidden');
    document.getElementById('detallesView').classList.remove('hidden');

    // Actualizar información del paciente
    document.getElementById('patientAvatar').textContent = patient.paciente_nombre.substring(0, 2).toUpperCase();
    document.getElementById('patientName').textContent = patient.paciente_nombre;
    document.getElementById('patientEmail').textContent = patient.email;
    document.getElementById('patientPhone').textContent = patient.telefono;
    document.getElementById('patientBirthDate').textContent = patient.fecha_nacimiento ? new Date(patient.fecha_nacimiento).toLocaleDateString() : 'No especificada';
    document.getElementById('patientTotalReports').textContent = patient.total_informes;
    document.getElementById('patientLastVisit').textContent = patient.ultima_visita ? new Date(patient.ultima_visita).toLocaleDateString() : 'Sin visitas';
    
    // Actualizar enlace de nuevo informe
    document.getElementById('nuevoInformeBtn').href = `nuevo_informe.php?paciente_id=${patient.paciente_id}`;
}

// Función para volver a la lista
function backToList() {
    document.getElementById('detallesView').classList.add('hidden');
    document.getElementById('listaView').classList.remove('hidden');
    // Limpiar el resaltado al volver
    const cards = document.querySelectorAll('#modalPacientes .card-item');
    cards.forEach(card => {
        const elements = card.querySelectorAll('h4, p');
        elements.forEach(element => {
            element.innerHTML = element.textContent;
        });
    });
}
</script> 