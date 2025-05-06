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
        <h1 class="text-2xl md:text-3xl font-bold text-kinetic-900 mb-6">Portal del Médico</h1>
        <!-- Acciones rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <button class="bg-card text-secondary border border-card rounded-xl p-6 flex flex-col items-center hover:bg-blue-50 transition">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="#21637f" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z"/>
                </svg>
                <span class="text-sm">Gestionar Citas</span>
            </button>
            <button class="bg-card text-secondary border border-card rounded-xl p-6 flex flex-col items-center hover:bg-blue-50 transition">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="#21637f" stroke-linecap="round" stroke-width="2" d="M16 19h4a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-2m-2.236-4a3 3 0 1 0 0-4M3 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                </svg>
                <span class="text-sm">Ver Pacientes</span>
            </button>
            <button class="bg-card text-secondary border border-card rounded-xl p-6 flex flex-col items-center hover:bg-blue-50 transition">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="#21637f" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 3v4a1 1 0 0 1-1 1H5m4 8h6m-6-4h6m4-8v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1Z"/>
                </svg>
                <span class="text-sm">Historial Clínico</span>
            </button>
            <button class="bg-card text-secondary border border-card rounded-xl p-6 flex flex-col items-center hover:bg-blue-50 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="#21637f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings h-8 w-8 text-kinetic-600" data-lov-id="src\pages\DoctorPortal.tsx:57:14" data-lov-name="Settings" data-component-path="src\pages\DoctorPortal.tsx" data-component-line="57" data-component-file="DoctorPortal.tsx" data-component-name="Settings" data-component-content="%7B%22className%22%3A%22h-8%20w-8%20text-kinetic-600%22%7D"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                <span class="text-sm">Configuración</span>
            </button>
        </div>
        <!-- Próximas citas -->
        <div class="mb-10">
            <div class="flex items-center justify-between mb-4">
                <div class="font-semibold text-lg text-kinetic-900">Próximas Citas</div>
                <button class="px-4 py-2 bg-kinetic-500 text-white rounded font-semibold hover:bg-kinetic-600 transition">Todas las Citas</button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-card text-secondary border border-card rounded-xl p-6 flex flex-col">
                    <div class="flex items-center mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users h-8 w-8 text-kinetic-600" data-lov-id="src\pages\DoctorPortal.tsx:49:14" data-lov-name="Users" data-component-path="src\pages\DoctorPortal.tsx" data-component-line="49" data-component-file="DoctorPortal.tsx" data-component-name="Users" data-component-content="%7B%22className%22%3A%22h-8%20w-8%20text-kinetic-600%22%7D"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <div>
                            <div class="font-semibold text-kinetic-900">Sarah Johnson</div>
                            <span class="inline-block bg-healing-600 text-white text-xs px-2 py-1 rounded">Structural Balance</span>
                        </div>
                    </div>
                    <div class="text-xs text-kinetic-700 mb-1">Last Session<br><span class="font-semibold text-kinetic-900">May 2, 2025</span></div>
                    <div class="text-xs text-kinetic-700">Next Session<br><span class="font-semibold text-kinetic-900">May 16, 2025</span></div>
                </div>
                <div class="bg-card text-secondary border border-card rounded-xl p-6 flex flex-col">
                    <div class="flex items-center mb-2">
                        <span class="bg-blue-100 text-kinetic-900 font-bold rounded-full w-10 h-10 flex items-center justify-center mr-3">MC</span>
                        <div>
                            <div class="font-semibold text-kinetic-900">Michael Chen</div>
                            <span class="inline-block bg-healing-600 text-white text-xs px-2 py-1 rounded">Pain Management</span>
                        </div>
                    </div>
                    <div class="text-xs text-kinetic-700 mb-1">Last Session<br><span class="font-semibold text-kinetic-900">Apr 28, 2025</span></div>
                    <div class="text-xs text-kinetic-700">Next Session<br><span class="font-semibold text-kinetic-900">May 12, 2025</span></div>
                </div>
                <div class="bg-card text-secondary border border-card rounded-xl p-6 flex flex-col">
                    <div class="flex items-center mb-2">
                        <span class="bg-blue-100 text-kinetic-900 font-bold rounded-full w-10 h-10 flex items-center justify-center mr-3">DW</span>
                        <div>
                            <div class="font-semibold text-kinetic-900">David Wilson</div>
                            <span class="inline-block bg-healing-600 text-white text-xs px-2 py-1 rounded">Emotional Stress Release</span>
                        </div>
                    </div>
                    <div class="text-xs text-kinetic-700 mb-1">Last Session<br><span class="font-semibold text-kinetic-900">Apr 25, 2025</span></div>
                    <div class="text-xs text-kinetic-700">Next Session<br><span class="font-semibold text-kinetic-900">May 9, 2025</span></div>
                </div>
            </div>
        </div>
        <!-- Estadísticas -->
        <div>
            <div class="font-semibold text-lg text-kinetic-900 mb-4">Estadísticas</div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-card text-secondary border border-card rounded-xl p-6 flex flex-col">
                    <div class="font-semibold text-kinetic-900 mb-1">Pacientes Activos</div>
                    <div class="text-3xl font-bold text-kinetic-900 mb-1">24</div>
                    <div class="text-xs text-kinetic-700">+3 desde el mes pasado</div>
                </div>
                <div class="bg-card text-secondary border border-card rounded-xl p-6 flex flex-col">
                    <div class="font-semibold text-kinetic-900 mb-1">Sesiones Este Mes</div>
                    <div class="text-3xl font-bold text-kinetic-900 mb-1">42</div>
                    <div class="text-xs text-kinetic-700">+8 desde el mes pasado</div>
                </div>
                <div class="bg-card text-secondary border border-card rounded-xl p-6 flex flex-col">
                    <div class="font-semibold text-kinetic-900 mb-1">Tasa de Recuperación</div>
                    <div class="text-3xl font-bold text-kinetic-900 mb-1">87%</div>
                    <div class="text-xs text-kinetic-700">+2% desde el mes pasado</div>
                </div>
            </div>
        </div>
    </main>
</body>
</html> 