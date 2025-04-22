<?php
session_start();

// Simulación de usuario logueado para pruebas
if (!isset($_SESSION['nombre'])) {
    $_SESSION['nombre'] = 'Doctor Pérez';
    $_SESSION['id'] = 1;
    $_SESSION['email'] = 'doctor@clinica.test';
    $_SESSION['tipo'] = 'doctor';
}

// Variables de sesión
$nombre = $_SESSION['nombre'];
$citas_hoy = 0;

// Saludo según hora
$hora = intval(date('H'));
if ($hora < 12) {
    $saludo = 'Buenos días';
} elseif ($hora < 20) {
    $saludo = 'Buenas tardes';
} else {
    $saludo = 'Buenas noches';
}

// Activar modo debug
$DEBUG = true;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Panel Doctor</title>
  <link rel="icon" type="image/jpeg" href="img/favicon.jpg" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="styles/root.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    :root {
      --primary-color: #2a5329;
      --secondary-color: #53a252;
      --bg-dark: #2a5329;
      --text-light: #f1faee;
    }
    * { box-sizing: border-box; }
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: url('/assets/img/fondo.jpg') no-repeat center center fixed;
      background-size: cover;
      color: var(--text-light);
      min-height: 100vh;
    }
    body::before {
      content: "";
      position: fixed;
      top: 0; left: 0; width: 100%; height: 100%;
      background: linear-gradient(135deg, rgba(0,0,0,0.7), rgba(0,0,0,0.8));
      z-index: -1;
    }
    .main-wrapper {
      padding: 0 20px;
    }
    .greeting {
      margin-top: 40px;
      font-size: 2rem;
      font-weight: 400;
      color: var(--secondary-color);
      text-shadow: 1px 1px 6px #0005;
    }
    .count-citas {
      margin-top: 10px;
      font-size: 1.2rem;
      color: var(--text-light);
      text-shadow: 1px 1px 4px #0007;
    }
    .debug-box {
      background-color: rgba(255,255,255,0.1);
      color: #f1faee;
      padding: 10px;
      margin-top: 40px;
      font-size: 0.9rem;
      border-left: 4px solid #53a252;
      border-radius: 6px;
    }
    pre {
      white-space: pre-wrap;
      word-wrap: break-word;
    }
  </style>
</head>
<body>
<?php include '../partials/navbar_doctor.php'; ?>
<div class="main-wrapper">
  <div class="greeting">
    <?php echo "$saludo, <strong>" . htmlspecialchars($nombre) . "</strong>"; ?>
  </div>
  <div class="count-citas">
    <?php echo "Tienes <strong>$citas_hoy</strong> cita(s) para hoy."; ?>
  </div>

  <?php if ($DEBUG): ?>
    <div class="debug-box mt-4">
      <strong>Debug - Datos de sesión:</strong>
      <pre><?php print_r($_SESSION); ?></pre>
    </div>
  <?php endif; ?>
</div>
</body>
</html>
