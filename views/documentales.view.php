
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/contenido.css">

    <title>Gestión de Documentales</title>
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
        <h1>Gestión de Documentales</h1>
        <nav>
       

            <!-- Botones de navegación -->
            <a href="?action=mostrarListaContenido&tipoContenido=3">Ver Lista</a> 
            <a href="?action=formularioInsertarContenido&tipoContenido=3">Añadir Nueva
                Documental</a>
        </nav>
    </header>

    <?php
// Incluimos las funciones necesarias
include '../src/gestionTipoContenido.php';

?>

</body>

</html>