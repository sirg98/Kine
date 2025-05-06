<?php
?><!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal del Médico - KineticCare</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="/kine/tailwind-colors.css" rel="stylesheet">
</head>
<body class="bg-blue-light text-gray-900 min-h-screen">
    <main class="container mx-auto px-4 py-10">
            <h1 class="text-2xl md:text-3xl font-bold text-kinetic-900 mb-6">Portal del Paciente</h1>
            <!-- Próxima cita -->
            <div class="bg-card text-secondary rounded-xl shadow-sm p-6 mb-6 border border-card">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <div class="font-semibold text-2xl text-kinetic-900 mb-1">Tu Próxima Cita</div>
                        <div class="text-base text-lg text-kinetic-900 mb-1 font-semibold">16 de Mayo, 2025 - 14:30</div>
                        <div class="text-sm text-kinetic-700">Dr. Martinez - Kinesiología Aplicada</div>
                    </div>
                    <div class="flex gap-2 mt-4 md:mt-0">
                        <button class="px-4 py-1 rounded border border-kinetic-500 text-kinetic-500 font-semibold hover:bg-kinetic-100 transition">Reprogramar</button>
                    </div>
                </div>
            </div>
            <!-- Acciones rápidas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <button class="bg-card text-secondary border border-card rounded-xl p-6 flex flex-col items-center hover:bg-blue-50 transition">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar h-8 w-8 text-kinetic-600" data-lov-id="src\pages\PatientPortal.tsx:60:14" data-lov-name="Calendar" data-component-path="src\pages\PatientPortal.tsx" data-component-line="60" data-component-file="PatientPortal.tsx" data-component-name="Calendar" data-component-content="%7B%22className%22%3A%22h-8%20w-8%20text-kinetic-600%22%7D"><path d="M8 2v4"></path><path d="M16 2v4"></path><rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M3 10h18"></path></svg>
                    <span class="text-sm">Agendar Cita</span>
                </button>
                <button class="bg-card text-secondary border border-card rounded-xl p-6 flex flex-col items-center hover:bg-blue-50 transition">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle h-8 w-8 text-kinetic-600" data-lov-id="src\pages\PatientPortal.tsx:64:14" data-lov-name="MessageCircle" data-component-path="src\pages\PatientPortal.tsx" data-component-line="64" data-component-file="PatientPortal.tsx" data-component-name="MessageCircle" data-component-content="%7B%22className%22%3A%22h-8%20w-8%20text-kinetic-600%22%7D"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path></svg>
                    <span class="text-sm">Contactar Médico</span>
                </button>
                <button class="bg-card text-secondary border border-card rounded-xl p-6 flex flex-col items-center hover:bg-blue-50 transition">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text h-8 w-8 text-kinetic-600" data-lov-id="src\pages\PatientPortal.tsx:68:14" data-lov-name="FileText" data-component-path="src\pages\PatientPortal.tsx" data-component-line="68" data-component-file="PatientPortal.tsx" data-component-name="FileText" data-component-content="%7B%22className%22%3A%22h-8%20w-8%20text-kinetic-600%22%7D"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path><path d="M14 2v4a2 2 0 0 0 2 2h4"></path><path d="M10 9H8"></path><path d="M16 13H8"></path><path d="M16 17H8"></path></svg>
                    <span class="text-sm">Mis Informes</span>
                </button>
                <button class="bg-card text-secondary border border-card rounded-xl p-6 flex flex-col items-center hover:bg-blue-50 transition">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user h-8 w-8 text-kinetic-600" data-lov-id="src\pages\PatientPortal.tsx:72:14" data-lov-name="User" data-component-path="src\pages\PatientPortal.tsx" data-component-line="72" data-component-file="PatientPortal.tsx" data-component-name="User" data-component-content="%7B%22className%22%3A%22h-8%20w-8%20text-kinetic-600%22%7D"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    <span class="text-sm">Mi Perfil</span>
                </button>
            </div>
            <!-- Historial de sesiones -->
            <div>
                <div class="font-semibold text-lg text-kinetic-900 mb-4">Historial de Sesiones</div>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-card text-secondary border-card rounded-xl">
                        <thead>
                            <tr class="bg-gray-100 text-kinetic-900">
                                <th class="py-2 px-4 text-left text-sm font-semibold">Fecha</th>
                                <th class="py-2 px-4 text-left text-sm font-semibold">Tipo</th>
                                <th class="py-2 px-4 text-left text-sm font-semibold">Notas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="py-2 px-4 text-sm">2 de Mayo, 2025</td>
                                <td class="py-2 px-4 text-sm">Kinesiología Aplicada</td>
                                <td class="py-2 px-4 text-sm">Progreso notable en movilidad del hombro. Continuar con ejercicios en casa.</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-4 text-sm">18 de Abril, 2025</td>
                                <td class="py-2 px-4 text-sm">Liberación del Estrés Emocional</td>
                                <td class="py-2 px-4 text-sm">Sesión enfocada en técnicas de manejo del estrés. Resultados positivos.</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-4 text-sm">5 de Abril, 2025</td>
                                <td class="py-2 px-4 text-sm">Equilibrio Estructural</td>
                                <td class="py-2 px-4 text-sm">Primera evaluación. Se detectó desequilibrio en la postura.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-center mt-4">
                    <button class="px-6 py-2 btn-blue text-white rounded font-semibold hover:bg-kinetic-600 transition">Ver Historial Completo</button>
                </div>
            </div>
        </main>
    </body>
</html>