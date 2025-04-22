<?php
include '../../components/db.php';

// Inicializar variables
$paciente_id = '';
$paciente_nombre = '';
$paciente_apellidos = '';
$apartados = [];
$mostrar_apartados = false;

// Obtener todos los nombres y apellidos de pacientes para el autocompletado
$query = "SELECT nombre, apellidos FROM usuarios WHERE tipo = 'paciente'";
$result = mysqli_query($conn, $query);
$nombres_pacientes = [];
while ($row = mysqli_fetch_assoc($result)) {
    $nombres_pacientes[] = $row['nombre'] . ' ' . $row['apellidos'];
}

if (isset($_POST['verificar_paciente'])) {
    $paciente_nombre_completo = $_POST['paciente'];
    $query = "SELECT id, nombre, apellidos FROM usuarios WHERE CONCAT(nombre, ' ', apellidos) = '" . mysqli_real_escape_string($conn, $paciente_nombre_completo) . "' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        $paciente_id = $row['id'];
        $paciente_nombre = $row['nombre'];
        $paciente_apellidos = $row['apellidos'];
        $mostrar_apartados = true;

        $apartados = [
            "Sistema Electromagnético" => [
                "Información Desorganizada", "Inhibición Articular", "Inhibición Visual", "Química Sanguínea",
                "Ionización. Nueva conexión estructural.", "Técnica respiratoria", "Nivelación del Eje Central",
                "Hioides", "Reflejos Cloacales", "Forma de Andar", "Acupuntura", "Pulso",
                "Marcha Cruzada", "Plexos Nerviosos (Chakras)"
            ],
            "Cicatrices" => [], "Técnica de Memoria de Traumatismo (TMT)" => [], "Cráneo" => [], "ATM" => [],
            "Occipital" => [], "Subuxación y Fijación" => ["Fijación", "Subluxación"], "Hígado" => [],
            "Agotamiento" => [], "Entradas" => [], "Deshidratación" => [], "Falta de Proteínas" => [],
            "Flora Intestinal" => [], "Requerimiento de B-12" => [], "S.O.D. y Discos Vertebrales" => [],
            "Adicciones" => [], "Problema de Cándidas" => [], "Remedios Florales" => ["FLORES DE BACH"]
        ];
    } else {
        $error = "El nombre del paciente no es válido.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Apuntes RKI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('../../assets/img/fondo.jpg') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            color: #f1faee;
            min-height: 100vh;
        }
        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(0,0,0,0.75), rgba(0,0,0,0.9));
            z-index: -1;
        }
        h2 {
            text-align: center;
            margin: 30px 0;
            color: #53a252;
            font-size: 2rem;
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: rgba(35,39,43,0.95);
            box-shadow: 0 2px 10px rgba(0,0,0,0.6);
            border-radius: 8px;
        }
        .info-container {
            margin-bottom: 20px;
        }
        .info-container h3 {
            margin-bottom: 10px;
            font-size: 18px;
            color: #f1faee;
        }
        .info-container input {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
        }
        button[type="submit"] {
            background-color: #53a252;
            color: white;
            border: none;
            padding: 10px 15px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        button[type="submit"]:hover {
            background-color: #45a049;
        }
        .accordion {
            background-color: #2e3134;
            color: #f1faee;
            cursor: pointer;
            padding: 18px;
            width: 100%;
            border: none;
            text-align: left;
            font-size: 18px;
            transition: 0.4s;
            margin-top: 5px;
            border-radius: 4px;
        }
        .active, .accordion:hover {
            background-color: #53a252;
            color: white;
        }
        .panel {
            padding: 0 18px;
            display: none;
            background-color: #ffffff;
            overflow: hidden;
            margin-top: 5px;
            border-left: 3px solid #53a252;
            border-radius: 4px;
            color: #212529;
        }
        textarea {
            width: 100%;
            height: 80px;
            margin-top: 10px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .error {
            color: #ff6b6b;
            margin-top: 10px;
        }
        h3 {
            color: #f1faee;
        }
        @media (max-width: 600px) {
            .accordion {
                font-size: 16px;
                padding: 15px;
            }
            textarea {
                height: 60px;
            }
        }
    </style>
</head>
<body>

<h2><i class="bi bi-journal-text"></i> Apuntes RKI</h2>

<div class="container">
    <?php if (!$mostrar_apartados): ?>
        <form method="POST" action="">
            <div class="info-container">
                <h3>Paciente:</h3>
                <input type="text" name="paciente" placeholder="Nombre y Apellidos del Paciente" list="pacientes" value="<?= htmlspecialchars($_POST['paciente'] ?? '') ?>" required>
                <datalist id="pacientes">
                    <?php foreach ($nombres_pacientes as $nombre_completo): ?>
                        <option value="<?= htmlspecialchars($nombre_completo) ?>"></option>
                    <?php endforeach; ?>
                </datalist>
                <button type="submit" name="verificar_paciente">Verificar</button>
            </div>
            <?php if (isset($error)): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
        </form>
    <?php endif; ?>

    <?php if ($mostrar_apartados): ?>
        <form method="POST" action="informe.php" id="formNotas">
            <input type="hidden" name="paciente_id" value="<?= $paciente_id ?>">
            <input type="hidden" name="paciente_nombre" value="<?= htmlspecialchars($paciente_nombre) ?>">
            <input type="hidden" name="paciente_apellidos" value="<?= htmlspecialchars($paciente_apellidos) ?>">
            <h3>Paciente Seleccionado: <?= htmlspecialchars($paciente_nombre . ' ' . $paciente_apellidos) ?></h3>

            <?php foreach ($apartados as $titulo => $subtemas): ?>
                <button type="button" class="accordion"><?= htmlspecialchars($titulo) ?></button>
                <div class="panel">
                    <?php if (count($subtemas) > 0): ?>
                        <ul>
                            <?php foreach ($subtemas as $subtema): ?>
                                <li><?= htmlspecialchars($subtema) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted">Sin subtemas.</p>
                    <?php endif; ?>
                    <label for="nota_<?= md5($titulo) ?>">Tus notas:</label><br>
                    <textarea name="notas[<?= htmlspecialchars($titulo) ?>]" id="nota_<?= md5($titulo) ?>"></textarea>
                </div>
            <?php endforeach; ?>
            <button type="submit" class="btn btn-success mt-3">Guardar Notas</button>
        </form>
    <?php endif; ?>
</div>

<script>
    document.querySelectorAll(".accordion").forEach(btn => {
        btn.addEventListener("click", function () {
            this.classList.toggle("active");
            const panel = this.nextElementSibling;
            panel.style.display = (panel.style.display === "block") ? "none" : "block";
        });
    });

    document.getElementById("formNotas")?.addEventListener("submit", function (e) {
        const textareas = this.querySelectorAll("textarea");
        textareas.forEach(textarea => {
            if (textarea.value.trim() === "") textarea.remove();
        });
    });
</script>
</body>
</html>
