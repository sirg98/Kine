<?php
$informe_id = intval($_GET['id'] ?? 0);

$sql = "SELECT i.id, i.contenido, i.fecha, 
               d.nombre AS terapeuta_nombre,
               p.nombre AS paciente_nombre
        FROM informes i
        JOIN usuarios d ON i.terapeuta_id = d.id
        JOIN usuarios p ON i.paciente_id = p.id
        WHERE i.id = $informe_id";

$res = mysqli_query($conn, $sql) or die("Error en la consulta: " . mysqli_error($conn));
$informe = mysqli_fetch_assoc($res);

if (!$informe) {
    die("Informe no encontrado");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Informe - KineticCare</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="/kine/tailwind-colors.css" rel="stylesheet">
    <style>
        @media print {
            @page {
                margin: 0;
                padding: 0;
            }
            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
                color: black !important;
            }
            header, footer, nav, .no-print {
                display: none !important;
            }
            .print-content {
                padding: 20px !important;
                margin: 0 !important;
                box-shadow: none !important;
                border: none !important;
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                width: 100% !important;
                background: white !important;
            }
            .print-content * {
                color: black !important;
                background: white !important;
            }
            .print-header {
                border-bottom: 1px solid #000 !important;
                margin-bottom: 20px !important;
                padding-bottom: 10px !important;
            }
            .print-title {
                font-size: 18pt !important;
                margin-bottom: 20px !important;
                color: black !important;
            }
            .print-section {
                margin-bottom: 15px !important;
            }
            .print-section-title {
                font-size: 14pt !important;
                margin-bottom: 10px !important;
                color: black !important;
            }
            .print-text {
                font-size: 12pt !important;
                line-height: 1.5 !important;
                color: black !important;
            }
            .bg-white, .bg-card, .bg-blue-light {
                background: white !important;
            }
            .text-kinetic-900, .text-kinetic-700, .text-secondary {
                color: black !important;
            }
            .border-card {
                border-color: #ddd !important;
            }
        }
    </style>
</head>
<body class="bg-blue-light text-gray-900 min-h-screen">
    <main class="container mx-auto px-4 py-10">
        <div class="bg-card text-secondary border border-card rounded-xl p-6 print-content">
            <!-- Botones de navegación (no se imprimen) -->
            <div class="flex justify-end space-x-3 mb-6 no-print">
                <button onclick="window.print()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 transition-colors duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Imprimir
                </button>
                <a href="terapeuta" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition-colors duration-200">
                    Volver
                </a>
            </div>

            <!-- Contenido del informe -->
            <div class="print-header">
                <h1 class="text-2xl font-bold text-kinetic-900 print-title">Informe Clínico</h1>
                
                <div class="grid grid-cols-2 gap-4 mb-4 print-section">
                    <div>
                        <p class="text-sm text-kinetic-700">Paciente</p>
                        <p class="font-semibold text-kinetic-900 print-text"><?= htmlspecialchars($informe['paciente_nombre']) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-kinetic-700">Terapeuta</p>
                        <p class="font-semibold text-kinetic-900 print-text">Dr. <?= htmlspecialchars($informe['terapeuta_nombre']) ?></p>
                    </div>
                </div>
                <div class="print-section">
                    <p class="text-sm text-kinetic-700">Fecha</p>
                    <p class="font-semibold text-kinetic-900 print-text"><?= date("d/m/Y", strtotime($informe['fecha'])) ?></p>
                </div>
            </div>

            <div class="prose max-w-none">
                <h2 class="text-xl font-semibold text-kinetic-900 mb-4 print-section-title">Contenido del Informe</h2>
                <div class="bg-card p-4 rounded-lg border border-card print-text">
                    <?= nl2br(htmlspecialchars(str_replace(';', "\n", $informe['contenido']))) ?>
                </div>
            </div>
        </div>
    </main>
</body>
</html> 