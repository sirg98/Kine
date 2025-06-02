<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Restablecer Contraseña</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-blue text-gray-900 min-h-screen flex items-center justify-center">
  <main class="w-full max-w-md mx-auto p-8 bg-card rounded-xl shadow-lg mt-48 mb-64 animate-fade-in">
    <h1 class="text-3xl font-bold text-center text-kinetic-900 mb-6">Restablecer Contraseña</h1>

    <?php
    require_once 'components/db.php';
    $token = $_GET['token'] ?? '';

    if (!$token) {
        echo '<div class="text-red-600">Token no válido.</div>';
        exit;
    }

    $sql = "SELECT id FROM usuarios WHERE token_recuperacion = '$token' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (!$result || mysqli_num_rows($result) === 0) {
        echo '<div class="text-red-600">Este enlace ha expirado o es inválido.</div>';
        exit;
    }

    $user = mysqli_fetch_assoc($result);
    $user_id = $user['id'];

    $mensaje = '';
    $exito = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nueva = $_POST['nueva'] ?? '';
        $repetir = $_POST['repetir'] ?? '';

        if (!$nueva || !$repetir) {
            $mensaje = '⚠️ Debes completar ambos campos.';
        } elseif ($nueva !== $repetir) {
            $mensaje = '❌ Las contraseñas no coinciden.';
        } else {
            $hash = password_hash($nueva, PASSWORD_DEFAULT);
            $update_sql = "UPDATE usuarios SET contraseña = '$hash', token_recuperacion = NULL WHERE id = $user_id";
            if (mysqli_query($conn, $update_sql)) {
                $exito = '✅ Tu contraseña ha sido restablecida correctamente.';
            } else {
                $mensaje = '❌ Error al actualizar la contraseña.';
            }
        }
    }
    ?>

    <?php if ($mensaje): ?>
      <div class="mb-4 p-3 bg-red-100 text-red-700 rounded text-sm text-center">
        <?= htmlspecialchars($mensaje) ?>
      </div>
    <?php endif; ?>

    <?php if ($exito): ?>
      <div class="mb-4 p-3 bg-green-100 text-green-700 rounded text-sm text-center">
        <?= htmlspecialchars($exito) ?>
      </div>
    <?php else: ?>
      <form method="POST" class="space-y-5">
        <div>
          <label class="block text-sm font-medium text-secondary mb-1">Nueva Contraseña</label>
          <input type="password" name="nueva" class="w-full border border-gray-300 rounded px-3 py-2" required>
        </div>
        <div>
          <label class="block text-sm font-medium text-secondary mb-1">Repetir Contraseña</label>
          <input type="password" name="repetir" class="w-full border border-gray-300 rounded px-3 py-2" required>
        </div>
        <button type="submit" class="w-full bg-kinetic-500 text-white font-semibold rounded py-2 hover:bg-kinetic-600 shadow hover:scale-105 transition-transform">
          Guardar nueva contraseña
        </button>
      </form>
    <?php endif; ?>
  </main>
</body>
</html>
