<h2 class="text-2xl font-bold mb-4">Opciones avanzadas</h2>
<p class="text-gray-600 mb-6">Activa funciones adicionales o gestiona acciones críticas de tu cuenta.</p>

<div class="space-y-6">
    <!-- Conectar Telegram -->
    <div class="p-4 bg-gray-100 rounded-lg flex items-center justify-between">
        <div>
            <h3 class="font-semibold text-lg mb-1">Conectar con Telegram</h3>
            <p class="text-sm text-gray-600">Recibe notificaciones directamente en Telegram.</p>
        </div>
        <a href="/integraciones/telegram.php" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition shadow">
            Conectar
        </a>
    </div>

    <!-- Habilitar 2FA -->
    <div class="p-4 bg-gray-100 rounded-lg flex items-center justify-between">
        <div>
            <h3 class="font-semibold text-lg mb-1">Verificación en dos pasos (2FA)</h3>
            <p class="text-sm text-gray-600">Protege tu cuenta con Google Authenticator o Authy.</p>
        </div>
        <a href="/auth/2fa-setup.php" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition shadow">
            Activar 2FA
        </a>
    </div>

    <!-- Eliminar cuenta -->
    <div class="p-4 bg-red-100 border border-red-300 rounded-lg">
        <h3 class="text-red-700 font-semibold text-lg mb-1">Eliminar cuenta</h3>
        <p class="text-sm text-red-600 mb-4">
            Tu cuenta será marcada para eliminación. Todos tus datos se conservarán durante 30 días por motivos legales y administrativos. Podrás recuperarla si cambias de opinión durante ese plazo.
        </p>
        <form action="/auth/eliminar-cuenta.php" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar tu cuenta? Esta acción no se puede deshacer.')">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition shadow">
                Eliminar cuenta permanentemente
            </button>
        </form>
    </div>
</div>
