<?php
$paciente_id = $_SESSION['id'] ?? null;

if (!$paciente_id) {
    header('Location: /login.php');
    exit;
}

// Obtener la próxima cita
$sql = "SELECT c.*, t.nombre as tratamiento_nombre, 
        CONCAT(d.nombre, ' ', d.apellidos) as terapeuta_nombre
        FROM citas c
        JOIN tratamientos t ON c.tratamiento_id = t.id
        JOIN usuarios d ON c.terapeuta_id = d.id AND d.tipo = 'terapeuta'
        WHERE c.paciente_id = $paciente_id 
        AND c.fecha >= CURDATE()
        AND c.estado = 'pendiente'
        ORDER BY c.fecha ASC
        LIMIT 1";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error en la consulta de próxima cita: " . mysqli_error($conn));
}

$proxima_cita = mysqli_fetch_assoc($result);

// Obtener historial de sesiones
$sql = "SELECT c.*, t.nombre as tratamiento_nombre, 
        CONCAT(d.nombre, ' ', d.apellidos) as terapeuta_nombre
        FROM citas c
        JOIN tratamientos t ON c.tratamiento_id = t.id
        JOIN usuarios d ON c.terapeuta_id = d.id AND d.tipo = 'terapeuta'
        WHERE c.paciente_id = $paciente_id 
        AND c.estado = 'completada'
        ORDER BY c.fecha DESC
        LIMIT 3";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error en la consulta de historial: " . mysqli_error($conn));
}

$historial = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Para debug
if (empty($historial)) {
    error_log("No se encontraron sesiones para el paciente ID: " . $paciente_id);
    error_log("SQL Query: " . $sql);
}
?><!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal del Paciente - KineticCare</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="/kine/tailwind-colors.css" rel="stylesheet">
</head>
<body class="bg-blue-light text-gray-900 min-h-screen">
    <main class="container mx-auto px-4 py-10">
            <h1 class="text-2xl md:text-3xl font-bold text-kinetic-900 mb-6">Portal del Paciente</h1>
            <!-- Próxima cita -->
            <?php include 'partials/proxima_cita.php'?>
            <!-- Acciones rápidas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <button onclick="openModal('modalCitas')" class="bg-card text-secondary border border-card rounded-xl p-6 flex flex-col items-center hover:bg-blue-50 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar h-8 w-8 text-kinetic-600"><path d="M8 2v4"></path><path d="M16 2v4"></path><rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M3 10h18"></path></svg>
                    <span class="text-sm">Agendar Cita</span>
                </button>
                <button onclick="openModalChat()" class="bg-card text-secondary border border-card rounded-xl p-6 flex flex-col items-center hover:bg-blue-50 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle h-8 w-8 text-kinetic-600"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path></svg>
                    <span class="text-sm">Contactar Médico</span>
                </button>
                <button onclick="openModal('modalInformes')" class="bg-card text-secondary border border-card rounded-xl p-6 flex flex-col items-center hover:bg-blue-50 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text h-8 w-8 text-kinetic-600"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path><path d="M14 2v4a2 2 0 0 0 2 2h4"></path><path d="M10 9H8"></path><path d="M16 13H8"></path><path d="M16 17H8"></path></svg>
                    <span class="text-sm">Mis Informes</span>
                </button>
                <button onclick="openModal('modalPerfil')" class="bg-card text-secondary border border-card rounded-xl p-6 flex flex-col items-center hover:bg-blue-50 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user h-8 w-8 text-kinetic-600"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    <span class="text-sm">Mi Perfil</span>
                </button>
            </div>
            <!-- Historial de sesiones -->
            <div>
                <div class="font-semibold text-lg text-kinetic-900 mb-4">Historial de Sesiones</div>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-card text-secondary border-gray-100 rounded-xl">
                        <thead>
                            <tr class="bg-gray-100 text-kinetic-900">
                                <th class="py-2 px-4 text-left text-sm font-semibold">Fecha</th>
                                <th class="py-2 px-4 text-left text-sm font-semibold">Tipo</th>
                                <th class="py-2 px-4 text-left text-sm font-semibold">Notas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($historial)): ?>
                            <tr>
                                    <td colspan="3" class="py-2 px-4 text-sm text-center">No hay sesiones anteriores</td>
                            </tr>
                            <?php else: ?>
                                <?php foreach ($historial as $sesion): ?>
                                    <tr>
                                        <td class="py-2 px-4 text-sm"><?= date('d \d\e F, Y', strtotime($sesion['fecha'])) ?> - <?= date('H:i', strtotime($sesion['fecha'])) ?></td>
                                        <td class="py-2 px-4 text-sm"><?= htmlspecialchars($sesion['tratamiento_nombre']) ?></td>
                                        <td class="py-2 px-4 text-sm"><?= htmlspecialchars($sesion['motivo'] ?? 'Sin notas') ?></td>
                            </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-center mt-4">
                    <button onclick="openModalHistorial()" class="px-6 py-2 btn-blue text-white rounded font-semibold hover:bg-kinetic-600 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-kinetic-500 focus:ring-opacity-50">Ver Historial Completo</button>
                </div>
            </div>
        </main>

    <!-- Incluir el modal de citas -->
    <?php include 'partials/modal_citas.php'; ?>
    
    <!-- Incluir el modal de historial -->
    <?php include 'partials/modal_historial.php'; ?>

    <!-- Incluir el modal de informes -->
    <?php include 'partials/modal_informes.php'; ?>

    <!-- Incluir el modal de perfil -->
    <?php include 'partials/modal_perfil.php'; ?>

    <!-- Incluir el modal de chat -->
    <?php include 'partials/modal_chat.php'; ?>

    <script>
    // Función para abrir el modal
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    // Función para cerrar el modal
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    }

    // Cerrar modal al hacer clic fuera
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('modalCitas');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal('modalCitas');
                }
            });
        }
    });
    </script>
    </body>
</html>