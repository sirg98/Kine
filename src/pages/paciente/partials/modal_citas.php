<?php
    $terapeutas = mysqli_query($conn, "SELECT id, nombre, apellidos FROM usuarios WHERE tipo = 'terapeuta' ORDER BY apellidos, nombre");
    $tratamientos = mysqli_query($conn, "SELECT * FROM tratamientos ORDER BY nombre");
?>
<!-- MODAL CITAS -->
<div id="modalCitas" class="fixed inset-0 bg-gray-600 bg-opacity-50 dark:bg-gray-900 dark:bg-opacity-50 hidden overflow-y-auto h-full w-full max-h-screen">
    <div class="relative top-20 mx-auto p-5 border border-card w-3/4 max-w-4xl shadow-lg rounded-md bg-blue dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-medium text-main">Agendar nueva cita 🩺</h3>
                <button onclick="closeModal('modalCitas')" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="formNuevaCita" class="space-y-6">
                <!-- DATOS DE LA CITA -->
                <div class="space-y-4">
                    <select name="tratamiento_id" required class="w-full border rounded px-3 py-2 bg-card text-kinetic-900 dark:text-gray-100 border-card" id="tratamiento">
                        <option value="">Selecciona tratamiento</option>
                        <?php foreach ($tratamientos as $t): ?>
                            <option value="<?= $t['id'] ?>" data-duracion="<?= $t['duracion'] ?>" data-precio="<?= $t['precio'] ?>">
                                <?= htmlspecialchars($t['nombre']) ?> - €<?= number_format($t['precio'], 2) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select name="terapeuta_id" required class="w-full border rounded px-3 py-2 bg-card text-kinetic-900 dark:text-gray-100 border-card">
                        <option value="">Selecciona terapeuta</option>
                        <?php foreach ($terapeutas as $d): ?>
                            <option value="<?= $d['id'] ?>">Dr. <?= htmlspecialchars($d['apellidos'] . ', ' . $d['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <input id="fecha" type="date" name="fecha" min="<?= date('Y-m-d') ?>" required class="w-full border rounded px-3 py-2 bg-card text-kinetic-900 dark:text-gray-100 border-card">

                    <select id="hora" name="hora" required class="w-full border rounded px-3 py-2 bg-card text-kinetic-900 dark:text-gray-100 border-card">
                        <option value="">Seleccione una hora</option>
                    </select>

                    <textarea name="motivo" rows="3" class="w-full border rounded px-3 py-2 bg-card text-kinetic-900 dark:text-gray-100 border-card" placeholder="Motivo de la cita (opcional)"></textarea>
                </div>

                <div class="bg-card border-card p-4 rounded mt-4 border-card text-kinetic-900 dark:text-gray-100">
                    <p><strong>Duración:</strong> <span id="duracionCita">-</span> minutos</p>
                    <p><strong>Precio:</strong> €<span id="precioCita">0.00</span></p>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        Registrar cita
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="successPanel" class="fixed inset-0 bg-gray-600 bg-opacity-50 dark:bg-gray-900 dark:bg-opacity-50 hidden overflow-y-auto h-full w-full z-50 flex items-center justify-center">
    <!-- Contenido dinámico será insertado aquí -->
</div>
<script>
const modal = document.getElementById('modalCitas');

// ABRIR MODAL (puedes llamarlo desde un botón con onclick)
function abrirModalCitas() {
    modal.classList.remove('hidden');
}

// CERRAR MODAL si clicas fuera del contenido
modal.addEventListener('click', (e) => {
    if (e.target === modal) {
        modal.classList.add('hidden');
    }
});

document.getElementById('tratamiento').addEventListener('change', function () {
    const opt = this.selectedOptions[0];
    document.getElementById('duracionCita').textContent = opt.dataset.duracion || '-';
    document.getElementById('precioCita').textContent = opt.dataset.precio || '0.00';
});

// HORAS DISPONIBLES
const horasDisponibles = [
    '09:00', '10:00', '11:00', '12:00',
    '13:00', '14:00', '15:00', '16:00',
    '17:00', '18:00', '19:00'
];

function actualizarHoras() {
    const fecha = document.getElementById('fecha').value;
    const terapeutaId = document.querySelector('select[name="terapeuta_id"]').value;
    const horaSelect = document.getElementById('hora');

    if (!fecha || !terapeutaId) {
        horaSelect.innerHTML = '<option value="">Seleccione una hora</option>';
        return;
    }

    horaSelect.innerHTML = '<option value="">Cargando horas...</option>';

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

document.getElementById('fecha').addEventListener('change', actualizarHoras);
document.querySelector('select[name="terapeuta_id"]').addEventListener('change', actualizarHoras);

document.getElementById('formNuevaCita').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);

    fetch('src/pages/paciente/ajax/insertar_cita.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            closeModal('modalCitas');
            const panel = document.getElementById('successPanel');
            panel.innerHTML = data.html;
            panel.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        } else {
            alert(data.message || 'Error al registrar la cita.');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Error de red al enviar cita.');
    });
});

function closeSuccessPanel() {
    const panel = document.getElementById('successPanel');
    panel.classList.add('hidden');
    panel.innerHTML = '';
    document.body.style.overflow = 'auto';
}

function enviarQRPorCorreoDesdeProxima(citaId) {
    fetch('/src/pages/paciente/ajax/enviar_qr.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ cita_id: citaId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('✅ QR enviado correctamente.');
        } else {
            alert('❌ ' + data.message);
        }
    })
    .catch(err => {
        console.error(err);
        alert('❌ Error al enviar el correo.');
    });
}
</script>
