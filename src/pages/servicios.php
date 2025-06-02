<?php
// Página de Servicios detallada

$tratamientos = [
    [
        "icon" => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-activity h-6 w-6"><path d="M22 12h-2.48a2 2 0 0 0-1.93 1.46l-2.35 8.36a.25.25 0 0 1-.48 0L9.24 2.18a.25.25 0 0 0-.48 0l-2.35 8.36A2 2 0 0 1 4.49 12H2"></path></svg>',
        "titulo" => "Valoración funcional muscular",
        "descripcion" => "Evaluamos el estado funcional del sistema muscular para detectar desequilibrios que puedan influir en el bienestar físico. Aplicamos técnicas complementarias que respetan la salud integral.",
        "beneficios" => [
            "Detecta desequilibrios musculares",
            "Favorece el funcionamiento neuromuscular",
            "Contribuye a la mejora del rendimiento físico"
        ],
        "imagen" => "https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=600&q=80"
    ],
    [
        "icon" => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bed-double h-6 w-6"><path d="M2 20v-8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v8"></path><path d="M4 10V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v4"></path><path d="M12 4v6"></path><path d="M2 18h20"></path></svg>',
        "titulo" => "Reeducación postural",
        "descripcion" => "Mejoramos la alineación corporal y la función muscular para prevenir molestias y favorecer una correcta biomecánica en el día a día.",
        "beneficios" => [
            "Mejora la postura",
            "Disminuye dolores persistentes",
            "Favorece la movilidad y flexibilidad"
        ],
        "imagen" => "https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=600&q=80"
    ],
    [
        "icon" => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-hand-heart h-6 w-6"><path d="M11 14h2a2 2 0 1 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 16"></path><path d="m7 20 1.6-1.4c.3-.4.8-.6 1.4-.6h4c1.1 0 2.1-.4 2.8-1.2l4.6-4.4a2 2 0 0 0-2.75-2.91l-4.2 3.9"></path><path d="m2 15 6 6"></path><path d="M19.5 8.5c.7-.7 1.5-1.6 1.5-2.7A2.73 2.73 0 0 0 16 4a2.78 2.78 0 0 0-5 1.8c0 1.2.8 2 1.5 2.8L16 12Z"></path></svg>',
        "titulo" => "Gestión del estrés emocional",
        "descripcion" => "Abordamos patrones de tensión emocional a través de técnicas de relajación y conciencia corporal, ayudando a mejorar el bienestar general.",
        "beneficios" => [
            "Reduce el estrés y la ansiedad",
            "Favorece un mejor descanso",
            "Promueve el equilibrio emocional"
        ],
        "imagen" => "https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=600&q=80"
    ],
    [
        "icon" => '<svg class="w-6 h-6 text-gray-800 dark:text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="#21637f" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12.4427 9.88469 9.93591 7.50961c.81449-.81448.80559-2.06903-.02046-2.89511-.82608-.82607-2.08062-.83494-2.89511-.02046-.4791.47911-.71525 1.20118-.56606 1.82948-.6283-.14919-1.35037.08695-1.82948.56606-.81448.81448-.80561 2.06903.02047 2.89511.82607.82611 2.08062.83491 2.8951.02046l2.50233 2.29305m.8063-1.38c1.83-1.8299 5.1241-1.22213 7.1925.8462 2.0684 2.0684 2.3191 4.6228.2978 6.6441-1.0322 1.0321-2.1287 1.6094-3.2302 1.6518.5878-1.3405.2254-2.5874-.8127-3.2811-.918-.6135-2.1806-.7802-3.5479.179-1.10401-2.0578-1.30393-4.6355.1005-6.04Z"/></svg>',
        "titulo" => "Asesoramiento nutricional",
        "descripcion" => "Ofrecemos orientación para mejorar hábitos alimentarios y detectar posibles carencias que influyan en el estado físico general.",
        "beneficios" => [
            "Ayuda a identificar carencias",
            "Apoya una alimentación personalizada",
            "Contribuye al aumento de energía"
        ],
        "imagen" => "https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=600&q=80"
    ],
    [
        "icon" => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-thermometer h-6 w-6"><path d="M14 4v10.54a4 4 0 1 1-4 0V4a2 2 0 0 1 4 0Z"></path></svg>',
        "titulo" => "Apoyo en el manejo del dolor",
        "descripcion" => "Aplicamos estrategias complementarias enfocadas a aliviar el malestar físico, favoreciendo la recuperación del movimiento y la calidad de vida.",
        "beneficios" => [
            "Alivia molestias crónicas",
            "Mejora la movilidad",
            "Apoya la recuperación funcional"
        ],
        "imagen" => "https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=600&q=80"
    ],
    [
        "icon" => '<svg class="w-6 h-6 text-gray-800 dark:text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="#21637f" stroke-linejoin="round" stroke-width="2" d="M2.98755 7.97095c0-.55229.44771-1 1-1H16.9253c.5523 0 1 .44771 1 1v7.95855c0 .5522-.4477 1-1 1H3.98755c-.55229 0-1-.4478-1-1V7.97095ZM20.9129 12.9419v-1.9834c0-.5523-.4478-1-1-1h-.9876c-.5523 0-1 .4477-1 1v1.9834c0 .5523.4477 1 1 1h.9876c.5522 0 1-.4477 1-1Z"/><path stroke="#21637f" stroke-linejoin="round" stroke-width="2" d="M5.9751 9.9585h8.9627v3.9834H5.9751V9.9585Z"/></svg>',
        "titulo" => "Mejora del rendimiento físico",
        "descripcion" => "Acompañamos procesos de mejora física y funcional, tanto en actividad deportiva como en tareas cotidianas, respetando las capacidades individuales.",
        "beneficios" => [
            "Favorece el rendimiento deportivo",
            "Incrementa la energía y resistencia",
            "Contribuye a la prevención de lesiones"
        ],
        "imagen" => "https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=600&q=80"
    ]
];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios - ReflexioKineTP</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="tailwind-colors.css" rel="stylesheet">
</head>
<body class="text-gray-900">
<main>
    <section class="py-16">
        <div class="container mx-auto px-4">
            <h1 class="text-5xl md:text-6xl font-bold text-center mb-4 text-kinetic-900">Nuestros Tratamientos Especializados</h1>
            <p class="text-center text-base text-kinetic-700 mb-8 max-w-2xl mx-auto">
                Ofrecemos una variedad de técnicas complementarias dirigidas a mejorar el bienestar físico, emocional y funcional, adaptadas a tus necesidades individuales.
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
                                <svg class="w-6 h-6 text-kinetic-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <?= $t["icon"] ?>
                                </svg>
                            </span>
                            <span class="font-semibold text-3xl text-kinetic-900"><?= htmlspecialchars($t["titulo"]) ?></span>
                        </div>
                        <p class="text-kinetic-700 text-base md:text-lg mb-4">
                            <?= htmlspecialchars($t["descripcion"]) ?>
                        </p>
                        <div class="mb-4">
                            <span class="font-semibold text-lg text-kinetic-900">Beneficios:</span>
                            <ul class="text-md text-kinetic-700 mt-1 space-y-1">
                                <?php foreach ($t["beneficios"] as $b): ?>
                                    <li>➜ <?= htmlspecialchars($b) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <a href="#" class="mt-2 inline-block px-4 py-1 bg-kinetic-500 text-white rounded hover:bg-kinetic-600 text-sm font-semibold">
                            Más información
                        </a>
                    </div>
                    <div class="flex-1 flex justify-center">
                        <img src="<?= htmlspecialchars($t["imagen"]) ?>"
                             alt="<?= htmlspecialchars($t["titulo"]) ?>"
                             class="rounded-xl shadow-md w-full max-w-lg object-cover h-80 md:h-96">
                    </div>
                </div>
                <hr class="my-2 border-blue-100">
            <?php endforeach; ?>

        </div>
    </section>

    <?php include dirname(__DIR__, 2) . '/partials/cta.php'; ?>
</main>
</body>
</html>
