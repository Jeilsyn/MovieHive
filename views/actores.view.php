<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/contenido.css">
    <title>Gestión de Actores</title>
</head>

<body>
    <header>
    <div class="form2">
            <form action="../src/cerrarSesion.php" method="POST">
                <button type="submit" class="btn-cerrar-sesion">Cerrar sesión</button>
            </form>
        </div>

        <div class="form1">
            <form action="../views/menu.php" method="POST">
                <button type="submit" class="btn-cerrar-sesion">Menú</button>
            </form>
        </div>
        <h1>Gestión de Actores</h1>
        <nav>
            <a href="?action=mostrarListaActores">Ver Actores</a>
            <a href="?action=formularioInsertarActor">Añadir Nuevo Actor</a>
        </nav>
    </header>

 
        <?php
        include '../src/actores.php';

        $action = $_GET['action'] ?? 'mostrarListaActores';
        switch ($action) {
            case 'mostrarListaActores':
                mostrarListaActores();
                break;
            case 'formularioInsertarActor':
                formularioInsertarActor();
                break;
            case 'insertarActor':
                insertarActor();
                break;
            case 'formularioModificarActor':
                formularioModificarActor($_GET['id']);
                break;
            case 'modificarActor':
                modificarActor();
                break;
            default:
                echo "<p>Acción no reconocida.</p>";
                break;
        }
        ?>
    
</body>

</html>
