<?php
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'terapeuta') {
    header('Location: /login.php');
    exit;
}
    include 'partials/modal_informes.php';
    include 'partials/modal_pacientes.php';
    include 'partials/modal_gestionar_citas.php';
    $terapeuta_id = $_SESSION['id'] ?? null;
    // Obtener próximas citas reales del terapeuta
    $sql = "SELECT c.*, 
                   p.nombre AS paciente_nombre, 
                   p.apellidos AS paciente_apellidos, 
                   t.nombre AS tratamiento_nombre
            FROM citas c
            JOIN usuarios p ON c.paciente_id = p.id
            JOIN tratamientos t ON c.tratamiento_id = t.id
            WHERE c.terapeuta_id = $terapeuta_id
              AND c.fecha >=  NOW()
              AND c.estado = 'pendiente'
            ORDER BY c.fecha ASC
            LIMIT 3";
    $result = mysqli_query($conn, $sql);
    $proximas_citas = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal del Médico - KineticCare</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="/kine/tailwind-colors.css" rel="stylesheet">
</head>
<body class="bg-blue-light text-gray-900 min-h-screen">
    <main class="container mx-auto px-4 py-10">
        <h1 class="text-2xl md:text-3xl font-bold text-kinetic-900 mb-6">Portal del Médico</h1>
        <!-- Acciones rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <button onclick="openModalGestionarCitas()" class="bg-card text-secondary border border-card rounded-xl p-6 flex flex-col items-center hover:bg-blue-50 transition">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="#21637f" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z"/>
                </svg>
                <span class="text-sm">Gestionar Citas</span>
            </button>
            <button onclick="openModalPacientes()" class="bg-card text-secondary border border-card rounded-xl p-6 flex flex-col items-center hover:bg-blue-50 transition">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="#21637f" stroke-linecap="round" stroke-width="2" d="M16 19h4a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-2m-2.236-4a3 3 0 1 0 0-4M3 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                </svg>
                <span class="text-sm">Ver Pacientes</span>
            </button>
            <button onclick="openModal('modalInformes')" class="bg-card text-secondary border border-card rounded-xl p-6 flex flex-col items-center hover:bg-blue-50 transition">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="#21637f" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 3v4a1 1 0 0 1-1 1H5m4 8h6m-6-4h6m4-8v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1Z"/>
                </svg>
                <span class="text-sm">Historial Clínico</span>
            </button>
            <a href="apuntes" class="bg-card text-secondary border border-card rounded-xl p-6 flex flex-col items-center hover:bg-blue-50 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="#21637f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                <span class="text-sm">Apuntes</span>
            </a>
        </div>
        <!-- Próximas citas -->
        <div class="mb-10">
            <div class="flex items-center justify-between mb-4">
                <div class="font-semibold text-lg text-kinetic-900">Próximas Citas</div>
                <button onclick="openModalGestionarCitas()" class="px-4 py-2 bg-kinetic-500 text-white rounded font-semibold hover:bg-kinetic-600 transition">Todas las Citas</button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <?php if (empty($proximas_citas)): ?>
                    <div class="col-span-3 text-center text-secondary">No hay próximas citas.</div>
                <?php else: ?>
                    <?php foreach ($proximas_citas as $cita): ?>
                        <div class="bg-card text-secondary border border-card rounded-xl p-6 flex flex-col">
                            <div class="font-semibold text-kinetic-900 mb-1">
                                <?= htmlspecialchars($cita['paciente_apellidos'] . ', ' . $cita['paciente_nombre']) ?>
                            </div>
                            <span class="inline-block bg-healing-600 text-white text-xs px-2 py-1 rounded mb-2">
                                <?= htmlspecialchars($cita['tratamiento_nombre']) ?>
                            </span>
                            <div class="text-xs text-kinetic-700 mb-1">
                                Fecha<br>
                                <span class="font-semibold text-kinetic-900">
                                    <?= date('d/m/Y H:i', strtotime($cita['fecha'])) ?>
                                </span>
                            </div>
                            <form action="nuevoinforme" method="post" style="display:inline;">
                                <input type="hidden" name="paciente_id" value="<?= $cita['paciente_id'] ?? $cita['paciente'] ?? '' ?>">
                                <input type="hidden" name="tratamiento_id" value="<?= $cita['tratamiento_id'] ?? '' ?>">
                                <input type="hidden" name="cita_id" value="<?= $cita['id'] ?>">
                                <button type="submit" class="mt-3 px-4 py-2 btn-blue text-white rounded-lg hover:bg-blue-600 transition-colors text-center font-semibold">
                                    Nuevo Informe
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <!-- Estadísticas -->
        <div>
            <div class="font-semibold text-lg text-kinetic-900 mb-4">Estadísticas</div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-card text-secondary border border-card rounded-xl p-6 flex flex-col">
                    <div class="font-semibold text-kinetic-900 mb-1">Pacientes Activos</div>
                    <div class="text-3xl font-bold text-kinetic-900 mb-1">24</div>
                    <div class="text-xs text-kinetic-700">+3 desde el mes pasado</div>
                </div>
                <div class="bg-card text-secondary border border-card rounded-xl p-6 flex flex-col">
                    <div class="font-semibold text-kinetic-900 mb-1">Sesiones Este Mes</div>
                    <div class="text-3xl font-bold text-kinetic-900 mb-1">42</div>
                    <div class="text-xs text-kinetic-700">+8 desde el mes pasado</div>
                </div>
                <div class="bg-card text-secondary border border-card rounded-xl p-6 flex flex-col">
                    <div class="font-semibold text-kinetic-900 mb-1">Tasa de Recuperación</div>
                    <div class="text-3xl font-bold text-kinetic-900 mb-1">87%</div>
                    <div class="text-xs text-kinetic-700">+2% desde el mes pasado</div>
                </div>
            </div>
        </div>
    </main>
    <!-- Botón flotante de chat -->
    <button onclick="openModalChatTerapeuta()" id="chatTerapeutaBtn" class="fixed bottom-6 right-6 z-50 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg w-16 h-16 flex items-center justify-center text-3xl focus:outline-none focus:ring-2 focus:ring-blue-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z" />
        </svg>
    </button>
    <!-- Incluir el modal de chat del terapeuta -->
    <?php include 'partials/modal_chat.php'; ?>
    <script>
    function openModalChatTerapeuta() {
        document.getElementById('modalChat').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeModalChatTerapeuta() {
        document.getElementById('modalChat').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    // Cerrar modal al hacer clic fuera
    if (document.getElementById('modalChat')) {
        document.getElementById('modalChat').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModalChatTerapeuta();
            }
        });
    }
    </script>
</body>
</html> 