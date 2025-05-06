<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - KineticCare</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="tailwind-colors.css" rel="stylesheet">
    <style>
    @keyframes fade-in {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fade-in 0.7s cubic-bezier(.23,1,.32,1); }
    </style>
</head>
<body class="bg-blue text-gray-900 min-h-screen flex items-center justify-center">
    <main class="w-full max-w-md mx-auto p-8 bg-card rounded-xl shadow-lg mt-48 mb-64 animate-fade-in">
        <h1 class="text-3xl font-bold text-center text-kinetic-900 mb-6">Iniciar Sesión</h1>
        <?php if (isset($error) && $error): ?>
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded text-center text-sm"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form class="space-y-5" method="post" action="/login">
            <div>
                <label class="block text-sm font-medium mb-1 text-secondary">Correo electrónico</label>
                <input type="email" name="email" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="tu@ejemplo.com" required value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1 text-secondary">Contraseña</label>
                <input type="password" name="password" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="Tu contraseña" required>
            </div>
            <button type="submit" class="w-full bg-kinetic-500 text-white font-semibold rounded py-2 hover:bg-kinetic-600 shadow hover:scale-105 transition-transform">Acceder</button>
        </form>
        <div class="flex justify-between mt-4 text-sm">
            <a href="#" class="text-kinetic-500 hover:underline">¿Olvidaste tu contraseña?</a>
        </div>
    </main>
</body>
</html> 