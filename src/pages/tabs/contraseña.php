<?php
$id = $_SESSION['id'] ?? null;
$nombre = $_SESSION['nombre'] ?? 'usuario';

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
?>

<h2 class="text-2xl font-bold mb-4">Hola, <?= htmlspecialchars($nombre) ?> 👋</h2>
<p class="text-gray-600 mb-6">Cambia tu contraseña de forma segura.</p>

<?php if ($success === 'contraseña'): ?>
    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded text-sm">✅ Contraseña actualizada correctamente.</div>
<?php elseif ($error === 'contraseña'): ?>
    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded text-sm">❌ Todos los campos son obligatorios y deben coincidir.</div>
<?php elseif ($error === 'incorrecta'): ?>
    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded text-sm">❌ La contraseña actual no es correcta.</div>
<?php endif; ?>

<form method="POST" action="/auth/cambiar-contraseña.php?redirect=ajustes&tab=contraseña" class="space-y-4 max-w-md">
    <div>
        <label class="block text-sm font-medium mb-1">Contraseña actual</label>
        <div class="relative">
            <input type="password" name="actual" id="actual" required class="w-full border px-3 py-2 rounded" />
            <button type="button" onclick="togglePassword('actual')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                <svg id="eye-icon-actual" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </button>
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Nueva contraseña</label>
        <div class="relative">
            <input type="password" name="nueva" id="nueva" required class="w-full border px-3 py-2 rounded" />
            <button type="button" onclick="togglePassword('nueva')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                <svg id="eye-icon-nueva" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </button>
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Confirmar nueva contraseña</label>
        <div class="relative">
            <input type="password" name="confirmar" id="confirmar" required class="w-full border px-3 py-2 rounded" />
            <button type="button" onclick="togglePassword('confirmar')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                <svg id="eye-icon-confirmar" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </button>
        </div>
    </div>
    <button type="submit" class="bg-kinetic-500 text-white px-4 py-2 rounded hover:bg-kinetic-600">
        Cambiar contraseña
    </button>
</form>

<script>
function togglePassword(fieldId) {
    const passwordInput = document.getElementById(fieldId);
    const eyeIcon = document.getElementById(`eye-icon-${fieldId}`);
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
        `;
    } else {
        passwordInput.type = 'password';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        `;
    }
}
</script>
