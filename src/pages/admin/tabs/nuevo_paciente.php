<?php

$terapeutas      = mysqli_query($conn, "SELECT id, nombre, apellidos FROM usuarios WHERE tipo = 'terapeuta' ORDER BY apellidos, nombre");
$tratamientos  = mysqli_query($conn, "SELECT * FROM tratamientos ORDER BY nombre");
?>

<h2 class="text-2xl font-bold mb-4">Registrar nueva cita para paciente 🩺</h2>
<p class="text-gray-600 mb-6">Introduce los datos del nuevo paciente y su cita.</p>

<form method="POST" action="src/pages/admin/ajax/guardar_paciente.php" class="space-y-6 max-w-3xl">
    <!-- DATOS DEL PACIENTE -->
    <div class="border rounded p-4 bg-white shadow">
        <h3 class="font-semibold mb-3">🧍 Datos del paciente</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="text" name="nombre" placeholder="Nombre" required class="border rounded px-3 py-2">
            <input type="text" name="apellidos" placeholder="Apellidos" required class="border rounded px-3 py-2">
            <input type="email" name="email" placeholder="Email" required class="border rounded px-3 py-2">
            <input type="tel" name="telefono" placeholder="Teléfono" required class="border rounded px-3 py-2">
            <input type="date" name="fecha_nacimiento" required class="border rounded px-3 py-2">
            <input type="text" name="ciudad" placeholder="Ciudad" required class="border rounded px-3 py-2">
            <input type="text" name="codigo_postal" placeholder="Código postal" required class="border rounded px-3 py-2">
        </div>
        <p class="text-sm text-gray-500 mt-2">Se enviará un email al paciente para establecer su contraseña.</p>
    </div>

    <!-- DATOS DE LA CITA -->
    <div class="border rounded p-4 bg-white shadow">
        <h3 class="font-semibold mb-3">📅 Datos de la cita</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <select name="tratamiento_id" required class="border rounded px-3 py-2" id="tratamiento">
                <option value="">Selecciona tratamiento</option>
                <?php foreach ($tratamientos as $t): ?>
                    <option value="<?= $t['id'] ?>" data-duracion="<?= $t['duracion'] ?>" data-precio="<?= $t['precio'] ?>">
                        <?= htmlspecialchars($t['nombre']) ?> - €<?= number_format($t['precio'], 2) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="terapeuta_id" required class="border rounded px-3 py-2">
                <option value="">Selecciona terapeuta</option>
                <?php foreach ($terapeutas as $d): ?>
                    <option value="<?= $d['id'] ?>">Dr. <?= htmlspecialchars($d['apellidos'] . ', ' . $d['nombre']) ?></option>
                <?php endforeach; ?>
            </select>

            <input id="fecha" type="date" name="fecha" min="<?= date('Y-m-d') ?>" required class="border rounded px-3 py-2">
            <select id="hora" name="hora" required class="border rounded px-3 py-2">
                <option value="">Seleccione una hora</option>
            </select>


            <textarea name="motivo" rows="3" class="md:col-span-2 border rounded px-3 py-2" placeholder="Motivo de la cita (opcional)"></textarea>
        </div>

        <div class="bg-gray-50 p-4 rounded mt-4 border">
            <p><strong>Duración:</strong> <span id="duracionCita">-</span> minutos</p>
            <p><strong>Precio:</strong> €<span id="precioCita">0.00</span></p>
        </div>
    </div>

    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
        Registrar cita
    </button>
</form>

<script>
// Mostrar duración y precio
document.getElementById('tratamiento').addEventListener('change', function () {
    const opt = this.selectedOptions[0];
    document.getElementById('duracionCita').textContent = opt.dataset.duracion || '-';
    document.getElementById('precioCita').textContent = opt.dataset.precio || '0.00';
});

const horasDisponibles = [
    '09:00', '10:00', '11:00', '12:00',
    '13:00', '14:00', '15:00', '16:00',
    '17:00', '18:00', '19:00'
];

// Al cambiar fecha o terapeuta, actualiza opciones de hora
function actualizarHoras() {
    const fecha = document.getElementById('fecha').value;
    const terapeutaId = document.querySelector('select[name="terapeuta_id"]').value;
    const horaSelect = document.getElementById('hora');

    horaSelect.innerHTML = '<option value="">Cargando horas...</option>';

    if (!fecha || !terapeutaId) return;

    fetch(`src/ajax/horas_disponibles.php?fecha=${fecha}&terapeuta_id=${terapeutaId}`)
        .then(res => res.json())
        .then(ocupadas => {
            horaSelect.innerHTML = '<option value="">Selecciona hora</option>';
            horasDisponibles.forEach(hora => {
                const option = document.createElement('option');
                option.value = hora;
                option.textContent = hora;
                if (ocupadas.includes(hora)) {
                    option.disabled = true;
                    option.textContent += ' (ocupada)';
                    option.style.color = '#888';
                }
                horaSelect.appendChild(option);
            });
        });
}

// Eventos
document.getElementById('fecha').addEventListener('change', actualizarHoras);
document.querySelector('select[name="terapeuta_id"]').addEventListener('change', actualizarHoras);

// Actualizar resumen
document.getElementById('tratamiento').addEventListener('change', function () {
    const opt = this.selectedOptions[0];
    document.getElementById('duracionCita').textContent = opt.dataset.duracion || '-';
    document.getElementById('precioCita').textContent = opt.dataset.precio || '0.00';
});

// Llenar horas solo cuando se seleccione la fecha
document.getElementById('fecha').addEventListener('change', function () {
    const horaSelect = document.getElementById('hora');
    horaSelect.innerHTML = '<option value="">Seleccione una hora</option>';

    horasDisponibles.forEach(hora => {
        const option = document.createElement('option');
        option.value = hora;
        option.textContent = hora;
        horaSelect.appendChild(option);
    });
});

document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);

    fetch('src/pages/admin/ajax/guardar_paciente.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        const output = document.createElement('div');
        output.className = 'p-4 rounded mb-4 text-sm';
        output.innerText = data.message;

        if (data.success) {
            output.classList.add('bg-green-100', 'text-green-700', 'border', 'border-green-300');
            form.reset();
        } else {
            output.classList.add('bg-red-100', 'text-red-700', 'border', 'border-red-300');
        }

        // Limpiar mensajes anteriores
        document.querySelectorAll('.feedback-message').forEach(el => el.remove());

        output.classList.add('feedback-message');
        form.prepend(output);
    })
    .catch(error => {
        console.error('Error al enviar:', error);
        alert('Ocurrió un error al procesar el formulario.');
    });
});
</script>
