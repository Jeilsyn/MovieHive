<?php
session_start(); // Iniciar la sesión para acceder a las variables de sesión

// Eliminar las variables de sesión
$_SESSION = [];

// Destruir la sesión
session_unset();
session_destroy();

// Eliminar las cookies establecidas
if (isset($_COOKIE['usuario'])) {
    setcookie('usuario', '', time() - 3600, '/'); // Eliminar cookie de usuario
}

if (isset($_COOKIE['password'])) {
    setcookie('password', '', time() - 3600, '/'); // Eliminar cookie de contraseña
}

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}


// Redirigir al login
header("Location: ../views/login.view.php");
exit();
?>
