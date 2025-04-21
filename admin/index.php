<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start(); 


if (!isset($_SESSION['nombre'])) {
    header("Location: login.php?error=Debe iniciar sesión");
    exit();
}

$nombre = $_SESSION['nombre']; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin</title>
    <style>
        @keyframes slideIn {
            from { transform: translateX(-100%); }
            to { transform: translateX(0); }
        }
        
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        :root {
            --primary-color: #2a5329;
            --secondary-color: #53a252;
            --bg-dark: #2a5329;
            --text-light: #f1faee;
            --menu-width: 70px;
            --menu-expanded-width: 240px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('../img/fondo.jpg') no-repeat center center fixed; 
            background-size: cover;
            color: var(--text-light);
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.8));
            z-index: -1;
        }

        /* Nuevo menú lateral colapsable */
        .sidebar {
            width: var(--menu-width);
            background-color: var(--bg-dark);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            overflow: hidden;
            transition: width 0.3s ease;
            box-shadow: 3px 0 15px rgba(0,0,0,0.2);
            z-index: 1000;
            animation: slideIn 0.6s ease-out forwards;
        }

        .sidebar:hover {
            width: var(--menu-expanded-width);
        }

        .logo {
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(83, 162, 82, 0.2);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .logo span {
            display: none;
            white-space: nowrap;
            margin-left: 15px;
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--secondary-color);
        }

        .sidebar:hover .logo span {
            display: block;
        }

        .menu-items {
            list-style: none;
            padding: 0;
        }

        .menu-items li {
            margin-bottom: 5px;
        }

        .menu-items a,
        .menu-items .dropdown-btn {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: var(--text-light);
            padding: 12px 15px;
            transition: all 0.3s;
            white-space: nowrap;
            border-left: 3px solid transparent;
            background: none;
            border: none;
            font-size: 1rem;
            text-align: left;
            cursor: pointer;
            width: 100%;
        }

        .menu-items i {
            font-size: 1.3rem;
            min-width: 40px;
            display: flex;
            justify-content: center;
        }

        .menu-items span {
            display: none;
        }

        .sidebar:hover .menu-items span {
            display: block;
        }

        .menu-items a:hover,
        .menu-items .dropdown-btn:hover {
            background-color: rgba(83, 162, 82, 0.2);
            border-left: 3px solid var(--secondary-color);
        }

        .dropdown-container {
            display: none;
            background-color: rgba(0, 0, 0, 0.3);
            padding-left: 0;
        }

        /* Aquí está el cambio clave para los menús desplegables */
        .sidebar:hover .dropdown-container.active {
            display: block;
        }

        .dropdown-container a {
            padding-left: 60px;
            font-size: 0.9rem;
        }

        .logout {
            position: absolute;
            bottom: 20px;
            width: 100%;
        }

        .logout a {
            display: flex;
            align-items: center;
            color: #e63946;
            text-decoration: none;
            padding: 12px 15px;
            transition: all 0.3s;
            white-space: nowrap;
            border-left: 3px solid transparent;
        }

        .logout i {
            font-size: 1.3rem;
            min-width: 40px;
            display: flex;
            justify-content: center;
        }

        .logout span {
            display: none;
        }

        .sidebar:hover .logout span {
            display: block;
        }

        .logout a:hover {
            background-color: rgba(230, 57, 70, 0.1);
            border-left: 3px solid #e63946;
        }

        /* Contenido principal */
        .main-wrapper {
            margin-left: var(--menu-width);
            width: calc(100% - var(--menu-width));
            transition: margin-left 0.3s ease, width 0.3s ease;
        }

        .sidebar:hover ~ .main-wrapper {
            margin-left: var(--menu-expanded-width);
            width: calc(100% - var(--menu-expanded-width));
        }

        header {
            background-color: rgba(42, 83, 41, 0.7);
            backdrop-filter: blur(10px);
            padding: 15px 25px;
            border-bottom: 1px solid rgba(83, 162, 82, 0.3);
            display: flex;
            align-items: center;
            justify-content: space-between;
            animation: fadeUp 0.8s ease-out forwards;
        }

        .welcome-message {
            font-size: 1.2rem;
            font-weight: 300;
        }

        .welcome-message strong {
            color: var(--secondary-color);
            font-weight: 500;
        }

        .user-section {
            display: flex;
            align-items: center;
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background-color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 15px;
            font-weight: bold;
        }

        .main-content {
            padding: 25px;
            animation: fadeUp 1s ease-out forwards;
            animation-delay: 0.2s;
            opacity: 0;
        }

        .content-card {
            background-color: rgba(42, 83, 41, 0.6);
            backdrop-filter: blur(5px);
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 25px;
        }

        h2 {
            margin-bottom: 20px;
            font-weight: 400;
            color: var(--secondary-color);
        }

        /* Responsive pa movil */
        @media (max-width: 800px) {
            .sidebar {
                width: 15%;
            }
            
            .sidebar:hover {
                width: var(--menu-expanded-width);
            }
            
            .main-wrapper {
                margin-left: 0;
                width: 100%;
            }
            
            .sidebar:hover ~ .main-wrapper {
                margin-left: var(--menu-expanded-width);
                width: calc(100% - var(--menu-expanded-width));
            }
            
            .toggle-menu {
                display: block;
                background: none;
                border: none;
                color: var(--text-light);
                font-size: 1.5rem;
                cursor: pointer;
            }
        }
    </style>
    <!-- Incluir Font Awesome para íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <!-- Menú lateral colapsable -->
    <nav class="sidebar">
        <div class="logo">
            <i class="fas fa-tachometer-alt"></i>
            <span>Panel Admin</span>
        </div>
        
        <ul class="menu-items">
            <li>
                <a href="index.php">
                    <i class="fas fa-home"></i>
                    <span>Inicio</span>
                </a>
            </li>
            <li>
                <button class="dropdown-btn">
                    <i class="fas fa-file"></i>
                    <span>Documentos</span>
                </button>
                <div class="dropdown-container">
                    <a href="documentos/añadirdoc.html">Añadir</a>
                    <a href="documentos/listardoc.php">Listar</a>
                    <a href="documentos/buscardoc.html">Buscar</a>
                    <a href="documentos/modificardoc.html">Modificar</a>
                    <a href="documentos/borrardoc.php">Borrar</a>
                </div>
            </li>
            <li>
                <button class="dropdown-btn">
                    <i class="fas fa-users"></i>
                    <span>Usuarios</span>
                </button>
                <div class="dropdown-container">
                    <a href="usuarios/añadirusuarios.html">Añadir</a>
                    <a href="usuarios/listarusuarios.php">Listar</a>
                    <a href="usuarios/buscarusuarios.html">Buscar</a>
                    <a href="usuarios/modificarusuarios.html">Modificar</a>
                    <a href="usuarios/borrarusuarios.php">Borrar</a>
                </div>
            </li>
            <li>
                <button class="dropdown-btn">
                    <i class="fas fa-chart-bar"></i>
                    <span>Informes</span>
                </button>
                <div class="dropdown-container">
                    <a href="informes/listarinformes.php">Listar</a>
                    <a href="informes/borrarinformes.php">Borrar</a>
                </div>
            </li>
        </ul>
        
        <div class="logout">
            <a href="../logout.php">
                <i class="fas fa-sign-out-alt"></i>
                <span>Cerrar Sesión</span>
            </a>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="main-wrapper">
        <header>
            <div class="welcome-message">
                ¡Te damos la bienvenida, <strong><?php echo htmlspecialchars($nombre); ?></strong>!
            </div>
            <div class="user-section">
                <div class="user-avatar">
                    <?php echo substr($nombre, 0, 1); ?>
                </div>
            </div>
        </header>

        <div class="main-content">
            <div class="content-card">
                <h2>Panel de Control</h2>
                <p>Selecciona una opción del menú lateral para comenzar.</p>
            </div>
        </div>
    </div>

    <script>
        // JavaScript mejorado para manejar los dropdowns
        document.addEventListener('DOMContentLoaded', function() {
            var dropdowns = document.querySelectorAll('.dropdown-btn');
            
            // Función para cerrar todos los dropdowns
            function closeAllDropdowns() {
                var containers = document.querySelectorAll('.dropdown-container');
                containers.forEach(function(container) {
                    container.classList.remove('active');
                });
            }
            
            // Evento click para cada botón dropdown
            dropdowns.forEach(function(dropdown) {
                dropdown.addEventListener('click', function(e) {
                    e.stopPropagation(); // Evita que el clic se propague
                    
                    // Si el dropdown ya está activo, solo lo cerramos
                    if (this.nextElementSibling.classList.contains('active')) {
                        this.nextElementSibling.classList.remove('active');
                    } else {
                        // Cerramos todos los dropdowns activos
                        closeAllDropdowns();
                        
                        // Abrimos el dropdown actual
                        this.nextElementSibling.classList.add('active');
                    }
                });
            });
            
            // Al hacer clic en cualquier lugar fuera de un dropdown, cerramos todos
            document.addEventListener('click', function() {
                closeAllDropdowns();
            });
            
            // Evitar que los clicks dentro de los dropdown-container cierren el menú
            var dropdownContainers = document.querySelectorAll('.dropdown-container');
            dropdownContainers.forEach(function(container) {
                container.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });
        });
    </script>
</body>
</html>