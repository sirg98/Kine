<?php
session_start();


if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$nombre = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Usuario';

// Obtener el filtro si existe
$filtro = isset($_GET['tipo']) ? $_GET['tipo'] : 'todos';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios</title>
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
            background: url('../../img/fondo.jpg') no-repeat center center fixed; 
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

        /* Menú lateral colapsable */
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
            opacity: 1;
        }

        h1 {
            margin-bottom: 20px;
            font-weight: 400;
            color: var(--secondary-color);
            font-size: 2rem;
        }

        /* Estilos para la tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: rgba(42, 83, 41, 0.4);
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        th {
            background-color: var(--primary-color);
            color: var(--text-light);
            padding: 12px 15px;
            text-align: left;
            font-weight: 500;
        }

        td {
            padding: 10px 15px;
            border-bottom: 1px solid rgba(83, 162, 82, 0.3);
        }

        tr:nth-child(even) {
            background-color: rgba(42, 83, 41, 0.2);
        }

        tr:hover {
            background-color: rgba(83, 162, 82, 0.3);
        }

        p {
            color: var(--text-light);
            font-size: 1rem;
        }

        /* Estilos para el filtro */
        .filter-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            background-color: rgba(42, 83, 41, 0.4);
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .filter-label {
            font-size: 1rem;
            margin-right: 15px;
            font-weight: 500;
        }

        .filter-select {
            background-color: rgba(241, 250, 238, 0.9);
            border: none;
            border-radius: 4px;
            padding: 8px 12px;
            font-size: 0.95rem;
            color: var(--bg-dark);
            cursor: pointer;
            outline: none;
            transition: all 0.2s;
        }

        .filter-select:hover {
            background-color: var(--text-light);
        }

        .filter-select:focus {
            box-shadow: 0 0 0 2px rgba(83, 162, 82, 0.5);
        }

        .filter-button {
            background-color: var(--secondary-color);
            color: var(--text-light);
            border: none;
            border-radius: 4px;
            padding: 8px 15px;
            margin-left: 10px;
            cursor: pointer;
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        .filter-button:hover {
            background-color: var(--primary-color);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
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
            
            table {
                width: 100%;
                font-size: 0.9rem;
            }
            
            th, td {
                padding: 8px 10px;
            }
            
            .filter-container {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .filter-select, .filter-button {
                margin-top: 10px;
                width: 100%;
            }
            
            .filter-button {
                margin-left: 0;
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
                <a href="../index.php">
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
                    <a href="../documentos/añadirdoc.html">Añadir</a>
                    <a href="../documentos/listardoc.php">Listar</a>
                    <a href="../documentos/buscardoc.html">Buscar</a>
                    <a href="../documentos/modificardoc.html">Modificar</a>
                    <a href="../documentos/borrardoc.php">Borrar</a>
                </div>
            </li>
            <li>
                <button class="dropdown-btn">
                    <i class="fas fa-users"></i>
                    <span>Usuarios</span>
                </button>
                <div class="dropdown-container active">
                    <a href="añadirusuarios.html">Añadir</a>
                    <a href="listarusuarios.php">Listar</a>
                    <a href="buscarusuarios.html">Buscar</a>
                    <a href="modificarusuarios.html">Modificar</a>
                    <a href="borrarusuarios.php">Borrar</a>
                </div>
            </li>
            <li>
                <button class="dropdown-btn">
                    <i class="fas fa-chart-bar"></i>
                    <span>Informes</span>
                </button>
                <div class="dropdown-container">
                    <a href="../informes/listarinformes.php">Listar</a>
                    <a href="../informes/borrarinformes.php">Borrar</a>
                </div>
            </li>
        </ul>
        
        <div class="logout">
            <a href="../../logout.php">
                <i class="fas fa-sign-out-alt"></i>
                <span>Cerrar Sesión</span>
            </a>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="main-wrapper">
        <header>
            <div class="welcome-message">
                ¡Bienvenido, <strong><?php echo htmlspecialchars($nombre); ?></strong>!
            </div>
            <div class="user-section">
                <div class="user-avatar">
                    <?php echo substr($nombre, 0, 1); ?>
                </div>
            </div>
        </header>

        <div class="main-content">
            <h1>Lista de Usuarios</h1>
            
            <!-- Filtro de usuarios -->
            <div class="filter-container">
                <span class="filter-label">Filtrar por tipo:</span>
                <form action="" method="GET" id="filterForm">
                    <select name="tipo" class="filter-select" onchange="this.form.submit()">
                        <option value="todos" <?php echo ($filtro == 'todos') ? 'selected' : ''; ?>>Todos</option>
                        <option value="admin" <?php echo ($filtro == 'admin') ? 'selected' : ''; ?>>Admin</option>
                        <option value="doctor" <?php echo ($filtro == 'doctor') ? 'selected' : ''; ?>>Doctores</option>
                        <option value="paciente" <?php echo ($filtro == 'paciente') ? 'selected' : ''; ?>>Pacientes</option>
                    </select>
                </form>
            </div>

            <?php
            $conn = mysqli_connect("localhost", "root", "rootroot") 
                or die("No se puede conectar con el servidor");

            mysqli_select_db($conn, "kine") 
                or die("No se puede seleccionar la base de datos");
            
            // Consulta SQL con filtro
            $sql = "SELECT * FROM Usuarios";
            
            // Aplicar filtro si no es "todos"
            if ($filtro != 'todos') {
                $filtro_escaped = mysqli_real_escape_string($conn, $filtro);
                $sql .= " WHERE tipo = '$filtro_escaped'";
            }
            
            $result = mysqli_query($conn, $sql) 
                or die("Error en la consulta: " . mysqli_error($conn));

            if (!$result) 
            {
                die("Error en la consulta: " . mysqli_error($conn));
            }

            $nfilas = mysqli_num_rows($result);
            if ($nfilas > 0) 
            {
                print ("<table>\n");
                print ("<tr>\n");
                print ("<th>Id</th>\n");
                print ("<th>Nombre</th>\n");
                print ("<th>Apellido</th>\n");
                print ("<th>Fecha de Nacimiento</th>\n");
                print ("<th>Email</th>\n");
                print ("<th>Telefono</th>\n");
                print ("<th>Ciudad</th>\n");
                print ("<th>Tipo</th>\n");
                print ("</tr>\n");

                for ($i = 0; $i < $nfilas; $i++) 
                {
                    $resultado = mysqli_fetch_array($result);
                    print ("<tr>\n");
                    print ("<td>" . $resultado['id'] . "</td>\n");
                    print ("<td>" . $resultado['nombre'] . "</td>\n");
                    print ("<td>" . $resultado['apellido'] . "</td>\n");
                    print ("<td>" . $resultado['fecha_nacimiento'] . "</td>\n");
                    print ("<td>" . $resultado['email'] . "</td>\n");
                    print ("<td>" . $resultado['telefono'] . "</td>\n");
                    print ("<td>" . $resultado['ciudad'] . "</td>\n");
                    print ("<td>" . $resultado['tipo'] . "</td>\n");
                    print ("</tr>\n");
                }

                print ("</table>\n");
            } 
            else 
            {
                print ("<p>No hay usuarios registrados que coincidan con el filtro seleccionado.</p>");
            }

            mysqli_close($conn);
            ?>
        </div>
    </div>

    <script>
        // JavaScript para manejar los dropdowns
        document.addEventListener('DOMContentLoaded', function() {
            var dropdowns = document.querySelectorAll('.dropdown-btn');
            
            dropdowns.forEach(function(dropdown) {
                dropdown.addEventListener('click', function() {
                    var dropdownContent = this.nextElementSibling;
                    dropdownContent.classList.toggle('active');
                });
            });
            
            // Al pasar el ratón sobre el sidebar, mostrar los dropdowns activos
            document.querySelector('.sidebar').addEventListener('mouseenter', function() {
                var activeDropdowns = document.querySelectorAll('.dropdown-container.active');
                activeDropdowns.forEach(function(dropdown) {
                    dropdown.style.display = 'block';
                });
            });
            
            // Al salir del sidebar, ocultar todos los dropdowns
            document.querySelector('.sidebar').addEventListener('mouseleave', function() {
                var allDropdowns = document.querySelectorAll('.dropdown-container');
                allDropdowns.forEach(function(dropdown) {
                    dropdown.style.display = 'none';
                });
            });
        });
    </script>
</body>
</html>