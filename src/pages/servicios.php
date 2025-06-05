<?php
$query = "SELECT * FROM tratamientos ORDER BY id ASC";
$result = mysqli_query($conn, $query);

$tratamientos = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $row['beneficios'] = json_decode($row['beneficios'] ?? '[]', true);
        $tratamientos[] = $row;
    }
}
?>

<main>
    <section class="py-16">
        <div class="container mx-auto px-4">
            <h1 class="text-5xl md:text-6xl font-bold text-center mb-4 text-kinetic-900">Tratamientos de Kinesiología Personalizados</h1>
            <p class="text-center text-base text-kinetic-700 mb-8 max-w-2xl mx-auto">
                Evaluamos y aplicamos técnicas basadas en kinesiología para mejorar el movimiento, aliviar el dolor y prevenir lesiones.
            </p>
        </div>
    </section>

    <section class="py-8">
        <div class="container mx-auto px-4 space-y-16">

            <?php foreach ($tratamientos as $t): ?>
                <div class="flex flex-col md:flex-row items-center gap-10">
                    <div class="flex-1">
                        <div class="flex items-center mb-4">
                            <span class="bg-kinetic-100 text-kinetic-700 rounded-full w-10 h-10 flex items-center justify-center mr-3">
                                <?= $t["icon"] ?>
                            </span>
                            <span class="font-semibold text-3xl text-kinetic-900"><?= htmlspecialchars($t["nombre"]) ?></span>
                        </div>
                        <p class="text-kinetic-700 text-base md:text-lg mb-4">
                            <?= htmlspecialchars($t["descripcion"]) ?>
                        </p>
                        <?php if (!empty($t["beneficios"])): ?>
                            <div class="mb-4">
                                <span class="font-semibold text-lg text-kinetic-900">Beneficios:</span>
                                <ul class="text-md text-kinetic-700 mt-1 space-y-1">
                                    <?php foreach ($t["beneficios"] as $b): ?>
                                        <li>➜ <?= htmlspecialchars($b) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <a href="#" class="mt-2 inline-block px-4 py-1 bg-kinetic-500 text-white rounded hover:bg-kinetic-600 text-sm font-semibold" title="Leer más sobre <?= htmlspecialchars($t["nombre"]) ?>">
                            Más información
                        </a>
                    </div>
                    <div class="flex-1 flex justify-center">
                        <img src="<?= htmlspecialchars($t["imagen"]) ?>"
                             alt="<?= htmlspecialchars($t["nombre"]) ?>"
                             class="rounded-xl shadow-md w-full max-w-lg object-cover h-80 md:h-96">
                    </div>
                </div>
                <hr class="my-2 border-blue-100">
            <?php endforeach; ?>

        </div>
    </section>

    <?php include dirname(__DIR__, 2) . '/partials/cta.php'; ?>
</main>
