<section class="py-16 bg-gray-50 dark:bg-gray-900" id="donacionWrapper">
<div class="max-w-md mx-auto bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 text-center border dark:border-gray-700">
    <h2 class="text-2xl font-bold mb-2 text-kinetic-900 dark:text-white">Apoya ReflexioKineTP</h2>
    <p class="text-sm text-kinetic-700 dark:text-gray-300 mb-6">Tu donación contribuye a mejorar la atención, investigación y el movimiento personalizado.</p>

    <div class="text-4xl font-bold text-blue-600 mb-4" id="montoVisual">0 €</div>

    <div class="flex flex-wrap justify-center gap-2 mb-4">
    <?php foreach ([1, 2, 5, 10] as $euro): ?>
        <button type="button" class="btn-donacion px-4 py-2 text-sm rounded bg-blue-600 text-white hover:bg-blue-700" data-amount="<?= $euro ?>"><?= $euro ?> €</button>
    <?php endforeach; ?>
    </div>

    <input type="number" id="donacionPersonalizada" min="0.5" step="0.5" placeholder="Otro importe (€)" class="mb-3 w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-white text-sm" />

    <input type="email" id="donacionEmail" placeholder="Tu email (opcional)" class="mb-4 w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-white text-sm" />

    <div id="paypal-button-container" class="mb-4"></div>

    <div id="mensajeDonacion" class="text-sm text-green-600 mt-2 hidden">Gracias por tu apoyo ❤️</div>
</div>

<script src="https://www.paypal.com/sdk/js?client-id=AVZkNQiuEADSIpFYUn0mdYFca2aKsBoOylv4EsuaK2IMXpe44nWuV-XCL9iusx5O6mgYqPIs514SVZUh&currency=EUR"></script>
<script>
    let monto = 0;
    const botones = document.querySelectorAll('.btn-donacion');
    const visual = document.getElementById('montoVisual');

    function actualizarVisual() {
    visual.textContent = monto.toFixed(2) + ' €';
    }

    botones.forEach(btn => {
    btn.addEventListener('click', () => {
        monto = parseFloat(btn.dataset.amount);
        document.getElementById('donacionPersonalizada').value = '';
        botones.forEach(b => b.classList.remove('bg-blue-800'));
        btn.classList.add('bg-blue-800');
        actualizarVisual();
    });
    });

    document.getElementById('donacionPersonalizada').addEventListener('input', e => {
    monto = parseFloat(e.target.value) || 0;
    botones.forEach(b => b.classList.remove('bg-blue-800'));
    actualizarVisual();
    });

    paypal.Buttons({
    createOrder: function(data, actions) {
        return actions.order.create({
        purchase_units: [{
            amount: { value: monto.toFixed(2) }
        }]
        });
    },
    onApprove: function(data, actions) {
    return actions.order.capture().then(function(details) {
        const email = document.getElementById('donacionEmail').value;
        document.getElementById('mensajeDonacion').classList.remove('hidden');

        if (email) {
        const formData = new FormData();
        formData.append('email', email);
        formData.append('cantidad', monto);

        fetch('/src/ajax/procesar_donacion.php', {
            method: 'POST',
            body: formData
        }).catch(err => console.error('Error AJAX:', err));
        }
    });
    }
    }).render('#paypal-button-container');

    // Inicializar visual
    actualizarVisual();
</script>
</section>
