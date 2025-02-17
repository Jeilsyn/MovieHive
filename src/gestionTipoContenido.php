<?php
 include '../src/gestionContenido.php';


// Establecemos el tipo de contenido como "Películas" (ID = 1)
$tipoContenido = isset($_GET['tipoContenido']) ? (int) $_GET['tipoContenido'] : 3;
$action = isset($_GET['action']) ? $_GET['action'] : 'mostrarListaContenido';

// Ejecutamos la acción correspondiente
switch ($action) {
    case 'mostrarListaContenido':
        mostrarListaContenido($tipoContenido);
        break;
    case 'verDetallesContenido':
        if (isset($_GET['id'])) {
            verDetallesContenido($_GET['id'], $tipoContenido);
        }
        break;
    case 'formularioInsertarContenido':
        formularioInsertarContenido($tipoContenido);
        break;
    case 'insertarContenido':
        insertarContenido($tipoContenido);
        break;
    case 'formularioModificarContenido':
        if (isset($_GET['id'])) {
            formularioModificarContenido($_GET['id'], $tipoContenido);
        }
        break;
    case 'modificarContenido':
        modificarContenido($tipoContenido);
        break;
    case 'borrarContenido':
        if (isset($_GET['id'])) {
            borrarContenido($_GET['id'], $tipoContenido);
        }
        break;
}

