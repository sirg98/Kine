<?php
$tratamientos_cortos = [
  [
    "titulo" => "Evaluación funcional muscular",
    "descripcion" => "Detectamos desequilibrios musculares y mejoramos la coordinación neuromuscular para optimizar el movimiento.",
    "icono" => '<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-kinetic-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 12h-2.48a2 2 0 0 0-1.93 1.46l-2.35 8.36a.25.25 0 0 1-.48 0L9.24 2.18a.25.25 0 0 0-.48 0l-2.35 8.36A2 2 0 0 1 4.49 12H2"/></svg>'
  ],
  [
    "titulo" => "Corrección postural activa",
    "descripcion" => "Mejoramos la alineación y movilidad corporal para prevenir molestias y favorecer el equilibrio postural.",
    "icono" => '<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-kinetic-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="4" y="8" width="16" height="8" rx="2"/><path d="M8 8V6a4 4 0 0 1 8 0v2"/></svg>'
  ],
  [
    "titulo" => "Regulación del estrés emocional",
    "descripcion" => "Aplicamos técnicas físicas y respiratorias que ayudan a reducir el impacto del estrés en el sistema nervioso.",
    "icono" => '<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-kinetic-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 9V7a5 5 0 0 0-10 0v2M5 12h14M12 15v2"/></svg>'
  ],
  [
    "titulo" => "Asesoramiento nutricional",
    "descripcion" => "Guiamos en la mejora de hábitos alimentarios y detección de carencias que influyen en la energía y salud.",
    "icono" => '<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-kinetic-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="6" y="6" width="12" height="12" rx="2"/><path d="M9 9h6v6H9z"/></svg>'
  ],
  [
    "titulo" => "Soporte en el manejo del dolor",
    "descripcion" => "Abordamos el dolor persistente con técnicas que promueven movilidad, funcionalidad y alivio.",
    "icono" => '<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-kinetic-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3"/><circle cx="12" cy="12" r="10"/></svg>'
  ],
  [
    "titulo" => "Optimización del rendimiento físico",
    "descripcion" => "Aumentamos fuerza, resistencia y coordinación mediante rutinas adaptadas a cada persona.",
    "icono" => '<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-kinetic-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 8a6 6 0 1 1-8 0"/><path d="M12 2v6"/></svg>'
  ]
];
?>

<section class="py-16">
  <div class="container mx-auto px-4">
    <h2 class="text-3xl md:text-4xl font-bold text-center mb-2 text-kinetic-900">Nuestros Enfoques de Tratamiento</h2>
    <p class="text-center text-lg text-kinetic-700 mb-12 max-w-2xl mx-auto">
      Ofrecemos tratamientos personalizados para mejorar el movimiento, reducir el dolor y apoyar la salud general.
    </p>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <?php foreach ($tratamientos_cortos as $t): ?>
        <div class="bg-card rounded-xl shadow-sm border border-card p-4 flex flex-col items-start transition-transform hover:scale-105">
          <div class="bg-kinetic-100 rounded-full p-2 mb-3">
            <?= $t['icono'] ?>
          </div>
          <h3 class="font-semibold text-lg text-kinetic-900 mb-1"><?= htmlspecialchars($t['titulo']) ?></h3>
          <p class="text-kinetic-700 mb-3 text-sm"><?= htmlspecialchars($t['descripcion']) ?></p>
          <button class="w-full border border-card rounded py-2 font-semibold text-kinetic-900 hover:bg-healing-100 transition">Saber más</button>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
