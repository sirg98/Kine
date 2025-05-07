<?php
// crear_usuario_rapido.php

// Configuración de la base de datos
$host = "localhost";
$user = "root";
$pass = ""; // Cambia si tienes contraseña
$db = "kine";

// Funciones para datos aleatorios
function randomString($length = 8) {
    return substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}
function randomEmail() {
    return randomString(6) . '@' . randomString(4) . '.com';
}
function randomDate($start = '1950-01-01', $end = '2010-12-31') {
    $timestamp = mt_rand(strtotime($start), strtotime($end));
    return date('Y-m-d', $timestamp);
}
function randomPhone() {
    return mt_rand(600000000, 699999999);
}
function randomCP() {
    return mt_rand(10000, 52999);
}
function randomCiudad() {
    $ciudades = ['Madrid', 'Barcelona', 'Valencia', 'Sevilla', 'Bilbao'];
    return $ciudades[array_rand($ciudades)];
}
function randomTipo() {
    $tipos = ['admin', 'doctor', 'paciente'];
    return $tipos[array_rand($tipos)];
}

// Procesar el formulario
$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $contraseña = $_POST['contraseña'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $telefono = $_POST['telefono'];
    $codigo_postal = $_POST['codigo_postal'];
    $ciudad = $_POST['ciudad'];
    $tipo = $_POST['tipo'];
    $fecha_creacion = date('Y-m-d H:i:s');

    $hash = password_hash($contraseña, PASSWORD_DEFAULT);

    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        $msg = "Error de conexión: " . $conn->connect_error;
    } else {
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellidos, email, contraseña, fecha_nacimiento, telefono, codigo_postal, ciudad, tipo, fecha_creacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssisss", $nombre, $apellidos, $email, $hash, $fecha_nacimiento, $telefono, $codigo_postal, $ciudad, $tipo, $fecha_creacion);
        if ($stmt->execute()) {
            $msg = "Usuario creado correctamente.";
        } else {
            $msg = "Error al crear usuario: " . $stmt->error;
        }
        $stmt->close();
        $conn->close();
    }
}

// Si se pide datos aleatorios por AJAX
if (isset($_GET['aleatorio'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'nombre' => randomString(6),
        'apellidos' => randomString(8),
        'email' => randomEmail(),
        'fecha_nacimiento' => randomDate(),
        'telefono' => randomPhone(),
        'codigo_postal' => randomCP(),
        'ciudad' => randomCiudad(),
        'tipo' => randomTipo()
    ]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Usuario Rápido</title>
    <script>
    function rellenarAleatorio() {
        fetch('crear_usuario_rapido.php?aleatorio=1')
        .then(response => response.json())
        .then(data => {
            document.getElementById('nombre').value = data.nombre;
            document.getElementById('apellidos').value = data.apellidos;
            document.getElementById('email').value = data.email;
            document.getElementById('fecha_nacimiento').value = data.fecha_nacimiento;
            document.getElementById('telefono').value = data.telefono;
            document.getElementById('codigo_postal').value = data.codigo_postal;
            document.getElementById('ciudad').value = data.ciudad;
            document.getElementById('tipo').value = data.tipo;
        });
    }
    </script>
</head>
<body>
    <h2>Crear Usuario Rápido</h2>
    <?php if ($msg) echo "<p><strong>$msg</strong></p>"; ?>
    <form method="post">
        <label>Nombre: <input type="text" name="nombre" id="nombre" required></label><br>
        <label>Apellidos: <input type="text" name="apellidos" id="apellidos" required></label><br>
        <label>Email: <input type="email" name="email" id="email" required></label><br>
        <label>Contraseña: <input type="password" name="contraseña" id="contraseña" required></label><br>
        <label>Fecha nacimiento: <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" required></label><br>
        <label>Teléfono: <input type="text" name="telefono" id="telefono" required></label><br>
        <label>Código postal: <input type="text" name="codigo_postal" id="codigo_postal" required></label><br>
        <label>Ciudad: <input type="text" name="ciudad" id="ciudad" required></label><br>
        <label>Tipo: 
            <select name="tipo" id="tipo" required>
                <option value="admin">admin</option>
                <option value="doctor">doctor</option>
                <option value="paciente">paciente</option>
            </select>
        </label><br>
        <button type="button" onclick="rellenarAleatorio()">Rellenar aleatorio</button>
        <button type="submit">Crear usuario</button>
    </form>
</body>
</html>