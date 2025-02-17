<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/resgistro.css">

    <title> Registro</title>

</head>

<body>

    <div class="divForm">

        <form action="../src/registro.php" method="POST" name="login">
            <fieldset>
                <legend>Registro</legend>
                <label for="usuario">Introduzca el nombre del Usuario</label><br>

                <input type="text" name="usuario" placeholder="Usuario"><br>
                <label for="password">Introduzca la contraseña</label><br>

                <input type="password" name="password" placeholder="Password"><br>
                <label for="password">Repita la contraseña</label><br>

                <input type="password" name="password2" placeholder="Repite Password"><br>
                <input class="submit" type="submit" value="Aceptar">
        </form>
    </div>
    <div class="divSesion">

        <p class="pSesion">¿Tienes cuenta?</p>
        <a href="../views/login.view.php">Inicia sesión</a>
    </div>

</body>

</html>