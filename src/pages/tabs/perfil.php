<?php
$id = $_SESSION['id'] ?? null;
$nombre = $_SESSION['nombre'] ?? '';
$email = $_SESSION['email'] ?? '';
$tipo = $_SESSION['tipo'] ?? '';

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

// Consulta actual del usuario
$result = mysqli_query($conn, "SELECT * FROM usuarios WHERE id = $id");
$usuario = mysqli_fetch_assoc($result);
?>

<h2 class="text-2xl font-bold mb-4">Información personal</h2>
<p class="text-gray-600 mb-6">Actualiza tu información de perfil.</p>

<?php if ($success === 'perfil'): ?>
    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded text-sm">✅ Perfil actualizado correctamente.</div>
<?php elseif ($error === 'perfil'): ?>
    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded text-sm">❌ Error al actualizar el perfil.</div>
<?php endif; ?>

<form method="POST" action="/auth/actualizar-perfil.php" class="max-w-4xl grid grid-cols-1 md:grid-cols-2 gap-6">
    <input type="hidden" name="id" value="<?= $id ?>">

    <div>
        <label class="block text-sm font-medium mb-1">Nombre</label>
        <input type="text" name="nombre" required class="w-full border px-3 py-2 rounded" value="<?= htmlspecialchars($usuario['nombre']) ?>">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Apellidos</label>
        <input type="text" name="apellidos" class="w-full border px-3 py-2 rounded" value="<?= htmlspecialchars($usuario['apellidos']) ?>">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Email</label>
        <input type="email" name="email" required class="w-full border px-3 py-2 rounded" value="<?= htmlspecialchars($usuario['email']) ?>">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Fecha de nacimiento</label>
        <input type="date" name="fecha_nacimiento" class="w-full border px-3 py-2 rounded" value="<?= htmlspecialchars($usuario['fecha_nacimiento']) ?>">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Teléfono</label>
        <input type="text" name="telefono" class="w-full border px-3 py-2 rounded" value="<?= htmlspecialchars($usuario['telefono']) ?>">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Código postal</label>
        <input type="text" name="codigo_postal" class="w-full border px-3 py-2 rounded" value="<?= htmlspecialchars($usuario['codigo_postal']) ?>">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Ciudad</label>
        <input type="text" name="ciudad" class="w-full border px-3 py-2 rounded" value="<?= htmlspecialchars($usuario['ciudad']) ?>">
    </div>

    <!-- Botón ocupa toda la fila -->
    <div class="md:col-span-2">
        <button type="submit" class="w-full bg-kinetic-500 text-white px-4 py-2 rounded hover:bg-kinetic-600">Guardar cambios</button>
    </div>
</form>

