
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/contenido.css">

    <title>Gestión de Series</title>
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
        <h1>Gestión de Series</h1>
        <nav>
<!--             <button class="btn-cerrar-sesion">Cerrar sesión</button>
 -->
            <!-- Botones de navegación -->
            <a href="?action=mostrarListaContenido&tipoContenido=2">Ver Lista</a>
            
            <a href="?action=formularioInsertarContenido&tipoContenido=2">Añadir Nueva
                Serie</a>

        </nav>
    </header>
    <?php
// Incluimos las funciones necesarias
require '../src/gestionTipoContenido.php';

?>


</body>

</html>