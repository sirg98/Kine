<?php if ($proxima_cita): ?>
    <?php
    require_once __DIR__ . '/../../../../components/qr.php';
    $url = "https://reflexiokine.es/cita.php?id={$proxima_cita['id']}";
    $qr_binary = generateQRBinary($url);
    $qr_code = 'data:image/png;base64,' . base64_encode($qr_binary);
    ?>
    <div class="bg-card text-secondary rounded-xl shadow-sm p-6 mb-6 border border-card">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="font-semibold text-2xl text-kinetic-900 mb-1">Tu Próxima Cita</div>
                <div class="text-base text-lg text-kinetic-900 mb-1 font-semibold">
                    <?= date('d \d\e F, Y', strtotime($proxima_cita['fecha'])) ?> - <?= date('H:i', strtotime($proxima_cita['fecha'])) ?>
                </div>
                <div class="text-sm text-kinetic-700">
                    Dr. <?= htmlspecialchars($proxima_cita['terapeuta_nombre']) ?> - <?= htmlspecialchars($proxima_cita['tratamiento_nombre']) ?>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button class="px-4 py-1 rounded border border-kinetic-500 text-kinetic-500 font-semibold hover:bg-kinetic-100 transition">
                    Reprogramar
                </button>
                <button onclick="verQRDesdeProxima(<?= $proxima_cita['id'] ?>)"
                    class="px-4 py-1 rounded border border-green-500 text-green-600 font-semibold hover:bg-green-100 transition">
                    Ver QR
                </button>
            </div>
        </div>
    </div>
<?php endif; ?>

<div id="successPanel" class="fixed inset-0 bg-gray-600 bg-opacity-50 dark:bg-gray-900 dark:bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800 scale-95 transition-transform duration-300">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mt-4">¡Tu Código QR!</h3>
            <div class="mt-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">Presenta este código QR en tu cita:</p>
                <div class="mt-2" id="qrPreview">
                    <img src="<?= $qr_code ?>" alt="QR Code" class="mx-auto" />
                </div>
            </div>
            <div class="mt-6 space-y-3">
                <button onclick="enviarQRPorCorreoDesdeProxima(<?= $proxima_cita['id'] ?>)"
                        class="w-full px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors">
                    Enviar por correo
                </button>
                <button onclick="closeSuccessPanel()"
                        class="w-full px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function verQRDesdeProxima(citaId) {
    citaIdActual = citaId;

    const panel = document.getElementById('successPanel');
    panel.classList.remove('hidden');
    panel.style.opacity = '1';
    panel.querySelector('.relative').style.transform = 'scale(1)';
    document.body.style.overflow = 'hidden';
}


function enviarQRPorCorreoDesdeProxima(citaId) {
    if (!citaId) return alert('ID no disponible');

    fetch('/src/pages/paciente/ajax/enviar_qr.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ cita_id: citaId })
})
.then(async res => {
    const text = await res.text();
    console.log('Respuesta bruta del servidor:', text);

    try {
        const json = JSON.parse(text);
        if (json.success) {
            alert('✅ QR enviado correctamente.');
        } else {
            alert('❌ Error: ' + json.message);
        }
    } catch (err) {
        console.error('⚠ HTML inesperado en respuesta:', text);
        alert('⚠ El servidor devolvió HTML en lugar de JSON. Mira la consola.');
    }
});


}


function closeSuccessPanel() {
    const panel = document.getElementById('successPanel');
    panel.style.opacity = '0';
    panel.querySelector('.relative').style.transform = 'scale(0.95)';
    setTimeout(() => {
        panel.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }, 300);
}
</script>
