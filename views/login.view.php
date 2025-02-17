<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Jeilsyn Dailid Ayala Braithwite">
    <link rel="stylesheet" href="../css/login.css">
    <title>Iniciar Sesión</title>

</head>

<body>

    <form action="../src/cerrarSesion.php" method="POST">
        <button type="submit" class="btn-cerrar-sesion">Cerrar sesión</button>
    </form>
    <!--     <button class="btn-cerrar-sesion" >Cerrar sesión</button>
 -->
    <div class="divForm">
        <form action="../src/login.php" method="POST" name="login">
            <fieldset>
                <legend>Login</legend>
                <label  for="usuario">Introduzca el nombre del Usuario</label><br>
                <input type="text" name="usuario" placeholder="Usuario"><br>
                <label for="password">Introduzca la contraseña</label><br>
                <input type="password" name="password" placeholder="Password"><br>
                <input class="submit" type="submit" value="Enviar">
            </fieldset>
        </form>
    </div>
    <div class="divRegistrar">
        <p class="pLogin">¿No tienes cuenta?</p>
        <a href="../views/registro.view.php">Registrate</a>
    </div>

 
</body>

</html>