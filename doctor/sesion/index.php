<?php
include '../../components/db.php';

// Inicializar variables
$paciente_id = '';
$paciente_nombre = '';
$paciente_apellidos = '';
$apartados = [];
$mostrar_apartados = false;

// Obtener todos los nombres y apellidos de pacientes para el autocompletado
$query = "SELECT nombre, apellidos FROM usuarios";
$result = mysqli_query($conn, $query);
$nombres_pacientes = [];
while ($row = mysqli_fetch_assoc($result)) {
    $nombres_pacientes[] = $row['nombre'] . ' ' . $row['apellidos'];
}

// Si se envía el formulario para verificar el paciente
if (isset($_POST['verificar_paciente'])) {
    $paciente_nombre_completo = $_POST['paciente'];
    $query = "SELECT id, nombre, apellidos FROM usuarios WHERE CONCAT(nombre, ' ', apellidos) = '" . mysqli_real_escape_string($conn, $paciente_nombre_completo) . "' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        $paciente_id = $row['id'];
        $paciente_nombre = $row['nombre'];
        $paciente_apellidos = $row['apellidos'];
        $mostrar_apartados = true;

        // Definir los apartados
        $apartados = [
            "Sistema Electromagnético" => [
                "Información Desorganizada",
                "Inhibición Articular",
                "Inhibición Visual",
                "Química Sanguínea",
                "Ionización. Nueva conexión estructural.",
                "Técnica respiratoria",
                "Nivelación del Eje Central",
                "Hioides",
                "Reflejos Cloacales",
                "Forma de Andar",
                "Acupuntura",
                "Pulso",
                "Marcha Cruzada",
                "Plexos Nerviosos (Chakras)"
            ],
            "Cicatrices" => [],
            "Técnica de Memoria de Traumatismo (TMT)" => [],
            "Cráneo" => [],
            "ATM" => [],
            "Occipital" => [],
            "Subuxación y Fijación" => [
                "Fijación",
                "Subluxación"
            ],
            "Hígado" => [],
            "Agotamiento" => [],
            "Entradas" => [],
            "Deshidratación" => [],
            "Falta de Proteínas" => [],
            "Flora Intestinal" => [],
            "Requerimiento de B-12" => [],
            "S.O.D. y Discos Vertebrales" => [],
            "Adicciones" => [],
            "Problema de Cándidas" => [],
            "Remedios Florales" => ["FLORES DE BACH"]
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apuntes RKI</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }
        h2 {
            text-align: center;
            margin: 20px 0;
            color: #4CAF50;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .info-container {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
        }
        .info-container h3 {
            margin-bottom: 10px;
            font-size: 18px;
        }
        .info-container input {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
        }
        button[type="submit"] {
            background-color: #4CAF50;
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
            background-color: #f1f1f1;
            color: #444;
            cursor: pointer;
            padding: 18px;
            width: 100%;
            border: none;
            text-align: left;
            outline: none;
            font-size: 18px;
            transition: 0.4s;
            margin-top: 5px;
            border-radius: 4px;
        }
        .active, .accordion:hover {
            background-color: #ccc;
        }
        .panel {
            padding: 0 18px;
            display: none;
            background-color: white;
            overflow: hidden;
            margin-top: 5px;
            border-left: 2px solid #4CAF50;
            border-radius: 4px;
        }
        textarea {
            width: 99%;
            height: 80px;
            margin-top: 10px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .error {
            color: red;
            margin-top: 10px;
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

<h2>Apuntes RKI - Tomar Notas</h2>
<div class="container">
    <!-- Formulario para verificar el paciente -->
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

    <!-- Mostrar apartados si el paciente es válido -->
    <?php if ($mostrar_apartados): ?>
        <form method="POST" action="notas.php" id="formNotas">
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
                        <p>Sin subtemas.</p>
                    <?php endif; ?>
                    <label for="nota_<?= md5($titulo) ?>">Tus notas sobre este apartado:</label><br>
                    <textarea name="notas[<?= htmlspecialchars($titulo) ?>]" id="nota_<?= md5($titulo) ?>"></textarea>
                </div>
            <?php endforeach; ?>
            <button type="submit">Guardar Notas</button>
        </form>
    <?php endif; ?>
</div>

<script>
    // Desplegar/cerrar apartados
    document.querySelectorAll(".accordion").forEach(btn => {
        btn.addEventListener("click", function () {
            this.classList.toggle("active");
            const panel = this.nextElementSibling;
            panel.style.display = (panel.style.display === "block") ? "none" : "block";
        });
    });

    // Eliminar campos vacíos antes de enviar el formulario
    document.getElementById("formNotas")?.addEventListener("submit", function (e) {
        const textareas = this.querySelectorAll("textarea");
        textareas.forEach(textarea => {
            if (textarea.value.trim() === "") {
                textarea.remove();
            }
        });
    });
</script>

</body>
</html>