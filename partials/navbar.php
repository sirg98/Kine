<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
?>
<script>
  // Prevent flash of light mode
  if (localStorage.getItem('darkMode') === 'true') {
    document.documentElement.classList.add('dark');
  }
</script>
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
      <a href="/contacto" class="text-main  hover:text-blue-500 dark:text-gray-300 dark:hover:text-blue-400 text-lg font-medium transition-colors duration-200">Contactanos</a>
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
      <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] == 'admin'): ?>
        <!-- Dropdown admin en escritorio -->
        <div class="relative hidden md:inline-block" id="admin-dropdown-container">
          <button id="admin-dropdown-btn" type="button" class="px-5 py-2 bg-blue-600 text-white rounded-lg font-medium shadow-md flex items-center gap-2 focus:outline-none">
            Admin
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
          </button>
          <div id="admin-dropdown-menu" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-900 rounded-lg shadow-lg py-2 z-50 hidden">
            <a href="admin" class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Panel Admin</a>
            <a href="ajustes" class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Ajustes</a>
            <a href="logout" class="block px-4 py-2 text-red-600 hover:bg-gray-100 dark:hover:bg-gray-800">Cerrar sesión</a>
          </div>
        </div>
      <?php elseif (isset($_SESSION['tipo']) && ($_SESSION['tipo'] == 'paciente' || $_SESSION['tipo'] == 'terapeuta')): ?>
        <!-- Dropdown simple en escritorio -->
        <div class="relative hidden md:inline-block" id="user-dropdown-container">
          <button id="user-dropdown-btn" type="button" class="px-5 py-2 bg-blue-600 text-white rounded-lg font-medium shadow-md flex items-center gap-2 focus:outline-none">
            <?php if ($_SESSION['tipo'] == 'paciente'): ?>
              Paciente
            <?php else: ?>
              Terapeuta
            <?php endif; ?>
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
          </button>
          <div id="user-dropdown-menu" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-900 rounded-lg shadow-lg py-2 z-50 hidden">
            <?php if ($_SESSION['tipo'] == 'paciente'): ?>
              <a href="paciente" class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Portal Paciente</a>
            <?php else: ?>
              <a href="terapeuta" class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Portal Terapeuta</a>
            <?php endif; ?>
            <a href="ajustes" class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Ajustes</a>
            <a href="logout" class="block px-4 py-2 text-red-600 hover:bg-gray-100 dark:hover:bg-gray-800">Cerrar sesión</a>
          </div>
        </div>
      <?php elseif (isset($_SESSION['tipo'])): ?>
        <!-- Si hay otro tipo de usuario -->
        <a href="logout" class="hidden md:inline-block px-5 py-2 bg-red-500 text-white rounded-lg font-medium hover:bg-red-600 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">Cerrar sesión</a>
      <?php else: ?>
        <a href="login" class="hidden md:inline-block px-5 py-2 btn-blue rounded-lg font-medium transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">Iniciar sesión</a>
      <?php endif; ?>
      <!-- Botón hamburguesa solo en móvil -->
      <button id="menu-btn" class="md:hidden text-main focus:outline-none ml-4">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
    </div>
  </div>
  <!-- Menú hamburguesa -->
<div id="menu-mobile" class="hidden fixed inset-0 w-full h-full bg-black bg-opacity-40 z-50 md:hidden flex items-start justify-center">
  <div class="w-full max-w-lg bg-blue rounded-b-2xl shadow-lg p-6 pt-8 flex flex-col space-y-4 animate-slide-down">
    <div class="flex items-center justify-between mb-4">
      <div class="flex items-center space-x-3">
        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        <span class="font-bold text-2xl text-main">KineticCare</span>
      </div>
      <button id="close-menu" class="text-gray-700 dark:text-gray-200">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
    <a href="inicio" class="block text-main hover:text-blue-500 dark:text-gray-300 dark:hover:text-blue-400 text-lg font-medium text-center">Inicio</a>
    <a href="servicios" class="block text-main hover:text-blue-500 dark:text-gray-300 dark:hover:text-blue-400 text-lg font-medium text-center">Servicios</a>
    <a href="nosotros" class="block text-main hover:text-blue-500 dark:text-gray-300 dark:hover:text-blue-400 text-lg font-medium text-center">Sobre Nosotros</a>
    <a href="contacto" class="block text-main hover:text-blue-500 dark:text-gray-300 dark:hover:text-blue-400 text-lg font-medium text-center">Contactanos</a>
    <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] == 'admin'): ?>
      <div class="flex flex-col space-y-1">
        <a href="/src/pages/admin/index.php" class="block px-5 py-2 btn-blue rounded-lg font-medium text-center">Panel Admin</a>
        <a href="/src/pages/ajustes.php" class="block px-5 py-2 bg-gray-200 dark:bg-gray-800 rounded-lg font-medium text-center">Ajustes</a>
        <a href="/logout.php" class="block px-5 py-2 bg-red-500 text-white rounded-lg font-medium text-center">Cerrar sesión</a>
      </div>
    <?php elseif (isset($_SESSION['tipo']) && ($_SESSION['tipo'] == 'paciente' || $_SESSION['tipo'] == 'terapeuta')): ?>
      <div class="flex flex-col space-y-1">
        <?php if ($_SESSION['tipo'] == 'paciente'): ?>
          <a href="paciente" class="block px-5 py-2 btn-blue rounded-lg font-medium text-center">Portal Paciente</a>
        <?php else: ?>
          <a href="terapeuta" class="block px-5 py-2 btn-blue rounded-lg font-medium text-center">Portal Terapeuta</a>
        <?php endif; ?>
        <a href="ajustes" class="block px-5 py-2 bg-gray-200 dark:bg-gray-800 rounded-lg font-medium text-center">Ajustes</a>
        <a href="logout" class="block px-5 py-2 bg-red-500 text-white rounded-lg font-medium text-center">Cerrar sesión</a>
      </div>
    <?php elseif (isset($_SESSION['tipo'])): ?>
      <a href="logout" class="block px-5 py-2 bg-red-500 text-white rounded-lg font-medium text-center">Cerrar sesión</a>
    <?php else: ?>
      <a href="login" class="block px-5 py-2 btn-blue rounded-lg font-medium text-center">Iniciar sesión</a>
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

  // Apply initial state
  document.addEventListener('DOMContentLoaded', () => {
    const savedDarkMode = localStorage.getItem('darkMode') === 'true';
    applyDarkMode(savedDarkMode);
  });

  // Menú hamburguesa
  const menuBtn = document.getElementById('menu-btn');
  const menuMobile = document.getElementById('menu-mobile');
  const closeMenu = document.getElementById('close-menu');
  menuBtn.addEventListener('click', () => {
    menuMobile.classList.remove('hidden');
  });
  closeMenu.addEventListener('click', () => {
    menuMobile.classList.add('hidden');
  });
  // Cerrar menú al hacer click fuera del panel
  menuMobile.addEventListener('click', (e) => {
    if (e.target === menuMobile) menuMobile.classList.add('hidden');
  });

  // Dropdown usuario simple (solo click)
  document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('user-dropdown-btn');
    const menu = document.getElementById('user-dropdown-menu');
    if (btn && menu) {
      btn.addEventListener('click', function(e) {
        e.stopPropagation();
        menu.classList.toggle('hidden');
      });
      document.addEventListener('click', function(e) {
        if (!menu.classList.contains('hidden') && !menu.contains(e.target) && e.target !== btn) {
          menu.classList.add('hidden');
        }
      });
    }
    // Dropdown admin
    const adminBtn = document.getElementById('admin-dropdown-btn');
    const adminMenu = document.getElementById('admin-dropdown-menu');
    if (adminBtn && adminMenu) {
      adminBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        adminMenu.classList.toggle('hidden');
      });
      document.addEventListener('click', function(e) {
        if (!adminMenu.classList.contains('hidden') && !adminMenu.contains(e.target) && e.target !== adminBtn) {
          adminMenu.classList.add('hidden');
        }
      });
    }
  });
</script>

<style>
@keyframes slideDown {
  0% { transform: translateY(-100%); opacity: 0; }
  100% { transform: translateY(0); opacity: 1; }
}
.animate-slide-down {
  animation: slideDown 0.35s cubic-bezier(.23,1,.32,1);
}
</style>
