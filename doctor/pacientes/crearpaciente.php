<?php
session_start();
include '../../components/db.php';

$nombre = trim($_POST['nombre'] ?? '');
$apellidos = trim($_POST['apellidos'] ?? '');
$email = trim($_POST['email'] ?? '');
$nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$codigo_postal = trim($_POST['codigo_postal'] ?? '');
$ciudad = trim($_POST['ciudad'] ?? '');
$contraseña = $_POST['contraseña'] ?? '';
$hash = password_hash($contraseña, PASSWORD_DEFAULT);

$datos_completos = $nombre && $apellidos && $email && $nacimiento && $telefono && $codigo_postal && $ciudad && $contraseña;

$exito = false;
$error = '';

if ($datos_completos) {
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellidos, email, fecha_nacimiento, telefono, codigo_postal, ciudad, contraseña, tipo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'paciente')");
    if ($stmt) {
        $stmt->bind_param("ssssssss", $nombre, $apellidos, $email, $nacimiento, $telefono, $codigo_postal, $ciudad, $hash);
        $exito = $stmt->execute();
        if (!$exito) {
            $error = "No se pudo crear el usuario. ¿El correo ya existe?";
        }
        $stmt->close();
    } else {
        $error = "Error al preparar la consulta.";
    }
} else {
    $error = "Faltan campos obligatorios.";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Creación de Paciente</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        body {
            background: url('../../assets/img/fondo.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #f1faee;
            min-height: 100vh;
            margin: 0;
        }
        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(0,0,0,0.7), rgba(0,0,0,0.8));
            z-index: -1;
        }
        .main-wrapper {
            max-width: 600px;
            margin: 100px auto 0;
            padding: 32px;
            background-color: rgba(35, 39, 43, 0.95);
            border-radius: 1rem;
            box-shadow: 0 0 20px #000;
            text-align: center;
        }
        .success {
            color: #4caf50;
        }
        .error {
            color: #f44336;
        }
        .back-button {
            margin-top: 24px;
            padding: 10px 20px;
            font-size: 1rem;
            background-color: #2a5329;
            border: none;
            color: white;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .back-button:hover {
            background-color: #53a252;
        }
    </style>
</head>
<body>
<?php include '../../partials/navbar_doctor.php'; ?>
<div class="main-wrapper">
    <?php if ($exito): ?>
        <h2 class="success"><i class="bi bi-check-circle-fill"></i> Usuario creado correctamente</h2>
        <p>El paciente ha sido registrado en el sistema.</p>
    <?php else: ?>
        <h2 class="error"><i class="bi bi-x-circle-fill"></i> Error al crear usuario</h2>
        <p><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <a href="index.php"><button class="back-button"><i class="bi bi-arrow-left-circle"></i> Volver al panel</button></a>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
