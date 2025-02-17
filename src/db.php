<?php
// db.php
$bdname = 'streaming';
$dsn = "mysql:host=db;dbname=$bdname";
$usuario = 'root';
$password = 'test';

try {
    // Crear la conexión
    $conexion = new PDO($dsn, $usuario, $password);
    // Establecer el modo de error
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Mostrar mensaje de error
}
?>
