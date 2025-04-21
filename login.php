<?php
session_start();
include_once 'components/db.php';

$error_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass = trim($_POST['contraseña'] ?? '');
    $email = mysqli_real_escape_string($conn, $email);
    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($pass, $row['contraseña'])) {
            $_SESSION['nombre'] = $row['nombre'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['tipo'] = $row['tipo'];
            $_SESSION['id'] = $row['id'];
            switch ($row['tipo']) {
                case 'admin': header("Location: admin/index.php"); exit();
                case 'paciente': header("Location: paciente/index.php"); exit();
                case 'doctor': header("Location: doctor/index.php"); exit();
                default: die("Error: Tipo de usuario no reconocido.");
            }
        } else {
            $error_message = "Contraseña incorrecta.";
        }
    } else {
        $error_message = "Usuario no encontrado.";
    }
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/root.css">
    <style>
    body {
        background: url('assets/img/fondo.jpg') center/cover no-repeat fixed !important;
        color: #f7f6ef !important;
        min-height: 100vh;
        position: relative;
    }
    body::before {
        content: '';
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(24,26,27,0.5);
        z-index: 0;
    }
    .login-main { position: relative; z-index: 2; }
    .login-card { background: #23272b; border-radius: 1.5rem; box-shadow: 0 2px 16px 0 #0005; color: #f7f6ef; }
    .form-control, .form-label { color: #f7f6ef !important; background: #23272b !important; border-color: #444 !important; }
    .form-control:focus { background: #23272b !important; color: #fff !important; border-color: var(--color-green-light); }
    .btn-custom { background: var(--color-green)!important; color: #fff!important; border:none; }
    .btn-custom:hover { background: var(--color-green-light)!important; }
    footer {position: relative; z-index: 1; padding: 1rem;text-align: center;
    }
</style>
</head>
<body>
<?php include 'partials/navbar.php'; ?>
<main class="container d-flex flex-column justify-content-center align-items-center min-vh-100 login-main" style="max-width:480px;">
  <div class="login-card w-100 p-4 mt-5 mb-4">
    <h2 class="text-center mb-4">Iniciar Sesión</h2>
    <?php if ($error_message): ?>
      <div class="alert alert-danger text-center py-2 mb-3"> <?php echo $error_message; ?> </div>
    <?php endif; ?>
    <form method="POST" action="auth/login.php">
      <div class="mb-3">
        <label for="email" class="form-label">Correo electrónico</label>
        <input type="email" class="form-control" id="email" name="email" required autofocus>
      </div>
      <div class="mb-3">
        <label for="contraseña" class="form-label">Contraseña</label>
        <input type="password" class="form-control" id="contraseña" name="contraseña" required>
      </div>
      <button type="submit" class="btn btn-custom w-100 py-2">Entrar</button>
    </form>
  </div>
</main>
<?php include 'partials/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>