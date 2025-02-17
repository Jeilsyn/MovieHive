<?php
session_start();
// Verificar si la sesión ya está activa (el usuario ya ha iniciado sesión)
if (isset($_SESSION['usuario'])) {
    // Si la sesión está activa, redirigir al menú
    header('Location: ../views/menu.php');
    exit(); // Asegurarse de que no se ejecute más código
}

$host = "db";
$dbUsername = "root";
$dbPassword = "test";
$db = "streaming";
if (isset($_POST['usuario']) && isset($_POST['password'])) {

    $usuario = strtolower($_POST['usuario']);
    $password = hash('sha512', $_POST['password']);

    try {
        $conn = new PDO("mysql:host=$host;dbname=$db", $dbUsername, $dbPassword);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $statement = $conn->prepare('SELECT * FROM usuarios WHERE usuario = :usuario AND password = :password LIMIT 1');

        $statement->execute(array(
            ':usuario' => $usuario,
            ':password' => $password
        ));

        $resultado = $statement->fetch();

        if ($resultado) {
            $_SESSION['usuario'] = $usuario;


            // Verificar si el usuario ha solicitado recordar su sesión mediante cookies
            if (isset($_POST['recordar'])) {
                // Crear una cookie que dure 30 días
                setcookie('usuario', $usuario, time() + (30 * 24 * 60 * 60), '/'); // 30 días
                setcookie('password', $_POST['password'], time() + (30 * 24 * 60 * 60), '/'); // Cookie con la contraseña (usada en texto plano aquí, ¡esto no es recomendable!).
            }



            header("Location: ../views/menu.php");
            exit();
        } else {
            //header("Location: registro.php");
            echo " <script>alert('Usuario no existe');</script>";
        }
    } catch (PDOException $e) {
        echo "<p>Error de conexión a la base de datos: " . $e->getMessage() . "</p>";
    }
}

if (isset($_COOKIE['usuario']) && isset($_COOKIE['password'])) {
    $usuario = $_COOKIE['usuario'];
    $password = hash('sha512', $_COOKIE['password']);  // Usar hash para comparar

    try {
        $conn = new PDO("mysql:host=$host;dbname=$db", $dbUsername, $dbPassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $statement = $conn->prepare('SELECT * FROM usuarios WHERE usuario = :usuario AND password = :password LIMIT 1');
        $statement->execute(array(':usuario' => $usuario, ':password' => $password));
        $resultado = $statement->fetch();

        if ($resultado) {
            $_SESSION['usuario'] = $usuario;
            header("Location: ../views/menu.php");
            exit();
        } else {
            // Cookies inválidas, eliminarlas
            setcookie('usuario', '', time() - 3600, '/');
            setcookie('password', '', time() - 3600, '/');
        }
    } catch (PDOException $e) {
        echo "<p>Error de conexión a la base de datos: " . $e->getMessage() . "</p>";
    }
}


require '../views/login.view.php';
