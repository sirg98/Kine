<?php
// Simulación de guardado de preferencias
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mensaje = '<div class="mb-4 p-3 bg-green-100 text-green-700 rounded text-center text-sm">Preferencias guardadas correctamente.</div>';
}
?>
<?= $mensaje ?>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Información General -->
    <div class="bg-blue-50 rounded-xl p-6 border">
        <h2 class="font-bold text-lg mb-2">Información General</h2>
        <form method="post">
            <div class="mb-2">
                <label class="block text-sm font-medium mb-1">Nombre de la Clínica:</label>
                <input type="text" name="nombre" class="w-full border rounded px-3 py-2" value="KineticCare">
            </div>
            <div class="mb-2">
                <label class="block text-sm font-medium mb-1">Dirección:</label>
                <input type="text" name="direccion" class="w-full border rounded px-3 py-2" value="123 Wellness Avenue">
            </div>
            <div class="mb-2">
                <label class="block text-sm font-medium mb-1">Teléfono:</label>
                <input type="text" name="telefono" class="w-full border rounded px-3 py-2" value="+34 600 123 456">
            </div>
            <div class="mb-2">
                <label class="block text-sm font-medium mb-1">Email:</label>
                <input type="email" name="email" class="w-full border rounded px-3 py-2" value="info@kineticcare.com">
            </div>
            <button type="submit" class="mt-2 w-full bg-blue-500 text-white rounded py-2 hover:bg-blue-600 transition">Editar información</button>
        </form>
    </div>
    <!-- Apariencia -->
    <div class="bg-blue-50 rounded-xl p-6 border">
        <h2 class="font-bold text-lg mb-2">Apariencia</h2>
        <form method="post">
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Tema:</label>
                <div class="flex gap-2">
                    <label><input type="radio" name="tema" value="claro" checked> Claro</label>
                    <label><input type="radio" name="tema" value="oscuro"> Oscuro</label>
                </div>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Color principal:</label>
                <div class="flex gap-2">
                    <input type="radio" name="color" value="#0ea5e9" checked class="accent-blue-500"><span class="w-5 h-5 rounded-full bg-blue-400 inline-block"></span>
                    <input type="radio" name="color" value="#22d3ee" class="accent-cyan-400"><span class="w-5 h-5 rounded-full bg-cyan-400 inline-block"></span>
                    <input type="radio" name="color" value="#34d399" class="accent-green-400"><span class="w-5 h-5 rounded-full bg-green-400 inline-block"></span>
                    <input type="radio" name="color" value="#a78bfa" class="accent-purple-400"><span class="w-5 h-5 rounded-full bg-purple-400 inline-block"></span>
                </div>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white rounded py-2 hover:bg-blue-600 transition">Aplicar cambios</button>
        </form>
    </div>
    <!-- Notificaciones -->
    <div class="bg-blue-50 rounded-xl p-6 border">
        <h2 class="font-bold text-lg mb-2">Notificaciones</h2>
        <form method="post">
            <div class="flex flex-col gap-2 mb-3">
                <label><input type="checkbox" checked> Nuevas citas</label>
                <label><input type="checkbox" checked> Recordatorios de citas</label>
                <label><input type="checkbox" checked> Informes de pacientes</label>
                <label><input type="checkbox" checked> Alertas del sistema</label>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white rounded py-2 hover:bg-blue-600 transition">Guardar preferencias</button>
        </form>
    </div>
</div>
<!-- Configuración avanzada -->
<div class="bg-blue-50 rounded-xl p-6 border mt-4">
    <h2 class="font-bold text-lg mb-2">Configuración avanzada</h2>
    <div class="grid md:grid-cols-2 gap-6">
        <div>
            <div class="font-semibold mb-1">Seguridad</div>
            <ul class="list-disc ml-5 text-gray-700 text-sm">
                <li>Autenticación de dos factores</li>
                <li>Bloqueo por intentos fallidos</li>
                <li>Registro de actividad</li>
            </ul>
        </div>
        <div>
            <div class="font-semibold mb-1">Copias de seguridad</div>
            <form method="post">
                <label class="block mb-1"><input type="checkbox"> Copias automáticas</label>
                <label class="block mb-1"><input type="checkbox" checked> Frecuencia</label>
                <select class="w-full border rounded px-2 py-1 mb-2">
                    <option>Diaria</option>
                    <option>Semanal</option>
                    <option>Mensual</option>
                </select>
                <button type="submit" class="w-full bg-blue-500 text-white rounded py-2 hover:bg-blue-600 transition">Crear copia ahora</button>
            </form>
        </div>
    </div>
</div> 