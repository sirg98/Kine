<?php
$title = "Iniciar Sesión - ReflexioKineTP";
?>

<main class="w-full max-w-md mx-auto p-8 bg-card rounded-xl shadow-lg mt-16 mb-20 animate-fade-in">
    <h1 class="text-3xl font-bold text-center text-kinetic-900 mb-6">Iniciar Sesión</h1>

    <?php if (isset($_GET['error'])): ?>
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded text-center text-sm">
            <?php
            switch ($_GET['error']) {
                case 'unauthorized': echo "🚫 No tienes permisos para acceder a esa sección."; break;
                case 'usuario': echo "Usuario no encontrado."; break;
                case 'contraseña': echo "Contraseña incorrecta."; break;
                case 'tipo-desconocido': echo "Error: Tipo de usuario no reconocido."; break;
                default: echo "Ocurrió un error desconocido.";
            }
            ?>
        </div>
    <?php endif; ?>

    <form class="space-y-5" method="post" action="/login">
        <div>
            <label class="block text-sm font-medium mb-1 text-secondary">Correo electrónico</label>
            <input type="email" name="email" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="tu@ejemplo.com" required value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Contraseña</label>
            <div class="relative">
                <input type="password" id="password" name="password"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                    placeholder="Tu contraseña" required>
                <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                    <!-- Icono de ojo -->
                    <svg id="eye-icon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5
                                 c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7
                                 -4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>
        </div>
        <button type="submit" class="w-full bg-kinetic-500 text-white font-semibold rounded py-2 hover:bg-kinetic-600">Acceder</button>
    </form>

    <div class="mt-4 text-center text-sm">
        <a href="#" onclick="openResetModal()" class="text-blue-600 hover:underline">¿Olvidaste tu contraseña?</a>
    </div>
</main>
<div id="resetModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg max-w-sm w-full shadow-lg">
        <h2 class="text-xl font-bold mb-4 text-center">Restablecer contraseña</h2>
        <form id="resetForm" action="/restablecer" method="get">
            <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Correo electrónico</label>
            <input type="email" name="email" class="w-full border border-gray-300 rounded px-3 py-2 mb-4" required placeholder="tu@correo.com">
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeResetModal()" class="text-sm text-gray-500 hover:text-gray-800 dark:text-gray-300">Cancelar</button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">Enviar</button>
            </div>
        </form>
    </div>
</div>

<style>
@keyframes fade-in {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
  animation: fade-in 0.7s cubic-bezier(.23,1,.32,1);
}
</style>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    const icon = document.getElementById('eye-icon');
    input.type = input.type === 'password' ? 'text' : 'password';
}
function openResetModal() {
    document.getElementById('resetModal').classList.remove('hidden');
}
function closeResetModal() {
    document.getElementById('resetModal').classList.add('hidden');
}
document.getElementById('resetForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const email = this.email.value;
    const submitButton = this.querySelector('button[type="submit"]');
    submitButton.disabled = true;
    submitButton.textContent = "Enviando...";

    fetch('/auth/recuperar_contraseña.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: new URLSearchParams({ email })
    })
    .then(response => response.json())
    .then(data => {
        submitButton.disabled = false;
        submitButton.textContent = "Enviar";

        alert(data.message);

        if (data.success) {
            closeResetModal();
        }
    })
    .catch(() => {
        submitButton.disabled = false;
        submitButton.textContent = "Enviar";
        alert("❌ Error al enviar la solicitud. Intenta más tarde.");
    });
});

</script>
