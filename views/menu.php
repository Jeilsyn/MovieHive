<?php


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú</title>
    <link rel="stylesheet" href="../css/menu.css">
</head>

<body>
<form action="../src/cerrarSesion.php" method="POST">
    <button type="submit" class="btn-cerrar-sesion">Cerrar sesión</button>
</form>
    <div class="container">
        <div class="divNom">
            <h1>MovieHive</h1>
        </div>
        <div class="divenlaces">

            <a href="../views/peliculas.view.php?action=mostrarListaContenido&tipoContenido=1">Péliculas</a><!--  hacer el php de las Peliculas -->
            <a href="../views/series.view.php?action=mostrarListaContenido&tipoContenido=2">Series</a><!--  hacer el php de las Series -->
            <a href="../views/documentales.view.php?action=mostrarListaContenido&tipoContenido=3">Documentales</a><!--  hacer el php de las Documentales -->
            <a href="../views/actores.view.php">Actores</a>


        </div>
    </div>

</body>

</html>