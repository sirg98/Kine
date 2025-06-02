<?php
// Obtener datos del paciente
$sql = "SELECT nombre, apellidos, email, telefono, fecha_nacimiento FROM usuarios WHERE id = $paciente_id LIMIT 1";
$result = mysqli_query($conn, $sql);
$perfil = mysqli_fetch_assoc($result);
?>

<div id="modalPerfil" class="fixed inset-0 bg-gray-600 bg-opacity-50 dark:bg-gray-900 dark:bg-opacity-50 hidden overflow-y-auto h-full w-full max-h-screen">
    <div class="relative top-20 mx-auto p-5 border border-card w-full max-w-lg shadow-lg rounded-md bg-blue dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-medium text-main">Mi Perfil</h3>
                <button onclick="closeModal('modalPerfil')" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form class="space-y-4">
                <div>
                    <label class="block text-sm text-secondary mb-1">Nombre completo</label>
                    <input type="text" readonly class="w-full px-3 py-2 border border-card rounded-lg bg-gray-100 text-kinetic-900" value="<?= htmlspecialchars($perfil['nombre'] . ' ' . $perfil['apellidos']) ?>">
                </div>
                <div>
                    <label class="block text-sm text-secondary mb-1">Email</label>
                    <input type="email" readonly class="w-full px-3 py-2 border border-card rounded-lg bg-gray-100 text-kinetic-900" value="<?= htmlspecialchars($perfil['email']) ?>">
                </div>
                <div>
                    <label class="block text-sm text-secondary mb-1">Teléfono</label>
                    <input type="text" readonly class="w-full px-3 py-2 border border-card rounded-lg bg-gray-100 text-kinetic-900" value="<?= htmlspecialchars($perfil['telefono'] ?? 'No registrado') ?>">
                </div>
                <div>
                    <label class="block text-sm text-secondary mb-1">Fecha de nacimiento</label>
                    <input type="date" readonly class="w-full px-3 py-2 border border-card rounded-lg bg-gray-100 text-kinetic-900" value="<?= $perfil['fecha_nacimiento'] ? htmlspecialchars($perfil['fecha_nacimiento']) : '' ?>">
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" onclick="closeModal('modalPerfil')" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Cerrar modal al hacer clic fuera
if (document.getElementById('modalPerfil')) {
    document.getElementById('modalPerfil').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal('modalPerfil');
        }
    });
}
</script> 