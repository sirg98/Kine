<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
?>
<header class="shadow-sm transition-colors duration-200">
  <div class="container mx-auto flex items-center justify-between py-6 px-6">
    <div class="flex items-center space-x-3">
      <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
      <span class="font-bold text-2xl text-main">KineticCare</span>
    </div>
    <nav class="hidden md:flex space-x-8">
      <a href="/inicio" class="text-main  hover:text-blue-500 dark:text-gray-300 dark:hover:text-blue-400 text-lg font-medium transition-colors duration-200">Inicio</a>
      <a href="/servicios" class="text-main  hover:text-blue-500 dark:text-gray-300 dark:hover:text-blue-400 text-lg font-medium transition-colors duration-200">Servicios</a>
      <a href="/nosotros" class="text-main  hover:text-blue-500 dark:text-gray-300 dark:hover:text-blue-400 text-lg font-medium transition-colors duration-200">Sobre Nosotros</a>
      <a href="/cita" class="text-main  hover:text-blue-500 dark:text-gray-300 dark:hover:text-blue-400 text-lg font-medium transition-colors duration-200">Cita</a>
    </nav>
    <div class="flex items-center space-x-4">
      <button id="toggleIcon" class="p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-200">
        <div class="relative w-6 h-6 overflow-hidden">
          <!-- Sol (empieza visible en posición "normal") -->
          <svg
            id="iconSun"
            class="absolute inset-0 transform transition-all duration-300 translate-y-0 opacity-100"
            fill="none" stroke="currentColor" viewBox="0 0 24 24"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 3v1m0 16v1m9-9h-1M4 12H3
                    m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707
                    m12.728 0l-.707.707M6.343 17.657l-.707.707
                    M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
          </svg>
          <!-- Luna (empieza oculta arriba de la vista) -->
          <svg
            id="iconMoon"
            class="absolute inset-0 transform transition-all duration-300 -translate-y-full opacity-0"
            fill="none" stroke="white" viewBox="0 0 24 24"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M20.354 15.354A9 9 0  
                    018.646 3.646 9.003 9.003 0 0012 21
                    a9.003 9.003 0 008.354-5.646z"/>
          </svg>
        </div>
      </button>
      <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] == 'paciente'): ?>
        <a href="/paciente" class="hidden md:inline-block px-5 py-2 btn-blue rounded-lg font-medium transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">Paciente</a>
        <a href="/logout" class="hidden md:inline-block px-5 py-2 bg-red-500 text-white rounded-lg font-medium hover:bg-red-600 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">Cerrar sesión</a>
      <?php elseif (isset($_SESSION['tipo']) && $_SESSION['tipo'] == 'doctor'): ?>
        <a href="/doctor" class="hidden md:inline-block px-5 py-2 btn-blue rounded-lg font-medium transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">Doctor</a>
        <a href="/logout" class="hidden md:inline-block px-5 py-2 bg-red-500 text-white rounded-lg font-medium hover:bg-red-600 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">Cerrar sesión</a>
      <?php else: ?>
        <a href="/login" class="hidden md:inline-block px-5 py-2 btn-blue rounded-lg font-medium transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">Iniciar sesión</a>
      <?php endif; ?>
    </div>
  </div>
</header>

<script>
  function isDarkMode() {
    return document.documentElement.classList.contains('dark');
  }

  function applyDarkMode(isDark) {
    if (isDark) {
      document.documentElement.classList.add('dark');
      sun.classList.add('translate-y-full', 'opacity-0');
      sun.classList.remove('opacity-100');
      moon.classList.remove('-translate-y-full', 'opacity-0');
      moon.classList.add('opacity-100');
    } else {
      document.documentElement.classList.remove('dark');
      sun.classList.remove('translate-y-full', 'opacity-0');
      sun.classList.add('opacity-100');
      moon.classList.add('-translate-y-full', 'opacity-0');
      moon.classList.remove('opacity-100');
    }
  }

  const btn = document.getElementById('toggleIcon');
  const sun = document.getElementById('iconSun');
  const moon = document.getElementById('iconMoon');

  btn.addEventListener('click', () => {
    const newDarkMode = !isDarkMode();
    applyDarkMode(newDarkMode);
    localStorage.setItem('darkMode', newDarkMode);
  });

  // Solo aplicar el modo cuando todo esté cargado
  window.addEventListener('load', () => {
    const savedDarkMode = localStorage.getItem('darkMode') === 'true';
    applyDarkMode(savedDarkMode);
  });
</script>
