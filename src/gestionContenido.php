<?php
include '../src/db.php';

// Función para mostrar la lista de contenidos (películas, series, documentales)
function mostrarListaContenido($tipoContenido)
{
    global $conexion;

    // Consulta para obtener los contenidos (1: Películas, 2: Series, 3: Documentales)
    $stmt = $conexion->prepare("SELECT * FROM video 
    WHERE tipo_video = :tipoContenido ORDER BY fecha_estreno DESC");
    $stmt->bindParam(':tipoContenido', $tipoContenido, PDO::PARAM_INT);

    $stmt->execute();

    $contenidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($contenidos) > 0) {
        echo "<table border='1'>
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Duración</th>
                        <th>Fecha de Estreno</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>";

        foreach ($contenidos as $contenido) {
            echo "<tr>
                    <td>{$contenido['titulo']}</td>
                    <td>{$contenido['minuto_duracion']} minutos</td>
                    <td>{$contenido['fecha_estreno']}</td>
                    <td>
                        <a href='?action=formularioModificarContenido&id={$contenido['id']}&tipoContenido=$tipoContenido'>Modificar</a> |
                        <a href='?action=borrarContenido&id={$contenido['id']}&tipoContenido=$tipoContenido'>Borrar</a> |
                        <a href='?action=verDetallesContenido&id={$contenido['id']}&tipoContenido=$tipoContenido'>Ver Detalles</a>
                    </td>
                  </tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "No se encontraron contenidos.";
    }
}

// Función para mostrar los detalles de un contenido (película, serie, documental)
function verDetallesContenido($id, $tipoContenido)
{
    global $conexion;

    // Obtener el contenido
    $stmt = $conexion->prepare("SELECT * FROM video
    WHERE id = :id AND tipo_video = :tipoContenido");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':tipoContenido', $tipoContenido, PDO::PARAM_INT);
    $stmt->execute();
    $contenido = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($contenido) {
        echo "<div class='divForm'><h1>Detalles del Contenido</h1>";
        echo "<p><strong>Título:</strong> {$contenido['titulo']}</p>";
        echo "<p><strong>Duración:</strong> {$contenido['minuto_duracion']} minutos</p>";
        echo "<p><strong>Fecha de Estreno:</strong> {$contenido['fecha_estreno']}</p>";
        echo "<p><strong>Tipo:</strong> " . getTipoContenido($tipoContenido) . "</p>";

        // Consultamos los actores asociados a este video
        $actorResult =  $conexion->prepare("SELECT actor.nombre
        FROM video_actor
        INNER JOIN actor ON video_actor.actor = actor.id
        WHERE video_actor.video = :id");
        $actorResult->bindParam(':id', $id, PDO::PARAM_INT);
        $actorResult->execute();

        echo "<h3>Actores:</h3>";
        if ($actorResult->rowCount() > 0) {
            echo "<ul>";
            while ($actor = $actorResult->fetch(PDO::FETCH_ASSOC)) {
                echo "<li>" . $actor['nombre'] . "</li>";
            }
            echo "</ul></div>";
        } else {
            echo "<p>No se encontraron actores para este contenido.</p>";
        }
    } else {
        echo "<p>Contenido no encontrado.</p>";
    }
}

// Función para insertar un nuevo contenido
function formularioInsertarContenido($tipoContenido)
{

    echo "<form action='?action=insertarContenido&tipoContenido=$tipoContenido' method='POST' class='divForm'>
    <fieldset>
    <legend>Formulario de Alta de " . getTipoContenido($tipoContenido) . "</legend>
            <label>Título:</label>
            <input type='text' name='titulo' required><br>
            
            <label>Duración (minutos):</label>
            <input type='number' name='minuto_duracion' required><br>
            
            <label>Fecha de Estreno:</label>
            <input type='date' name='fecha_estreno' required><br>
            
            <input class='submitC'type='submit' value='Enviar'>
            </fieldset>
          </form>";
}

// Función para insertar un nuevo contenido
function insertarContenido($tipoContenido)
{
    global $conexion;

    // Obtener los datos del formulario
    $titulo = $_POST['titulo'];
    $minuto_duracion = $_POST['minuto_duracion'];
    $fecha_estreno = $_POST['fecha_estreno'];

    // Insertar el contenido en la base de datos
    $stmt = $conexion->prepare("INSERT INTO video (titulo, minuto_duracion, fecha_estreno, tipo_video)
                                VALUES (:titulo, :minuto_duracion, :fecha_estreno, :tipoContenido)");

    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':minuto_duracion', $minuto_duracion);
    $stmt->bindParam(':fecha_estreno', $fecha_estreno);
    $stmt->bindParam(':tipoContenido', $tipoContenido, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Contenido añadido con éxito.";
    } else {
        echo "Error al añadir el contenido.";
    }
}

// Función para modificar un contenido
function formularioModificarContenido($id, $tipoContenido)
{
    global $conexion;

    // Obtener el contenido a modificar
    $stmt = $conexion->prepare("SELECT * FROM video WHERE id = :id AND tipo_video = :tipoContenido");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':tipoContenido', $tipoContenido, PDO::PARAM_INT);
    $stmt->execute();
    $contenido = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($contenido) {


        echo "<form action='?action=modificarContenido&tipoContenido=$tipoContenido' method='POST' class='divForm'>
                <fieldset>
                                <legend>Modificar " . getTipoContenido($tipoContenido) . "</legend>

                <input type='hidden' name='id' value='{$contenido['id']}'>
                <label>Título:</label>
                <input type='text' name='titulo' value='{$contenido['titulo']}' required><br>
                
                <label>Duración (minutos):</label>
                <input type='number' name='minuto_duracion' value='{$contenido['minuto_duracion']}' required><br>
                
                <label>Fecha de Estreno:</label>
                <input type='date' name='fecha_estreno' value='{$contenido['fecha_estreno']}' required><br>
                
                <input class='submitC' type='submit' value='Enviar'>
                </fielset>
              </form>";
    } else {
        echo "Contenido no encontrado.";
    }
}

// Función para modificar el contenido en la base de datos
function modificarContenido($tipoContenido)
{
    global $conexion;

    // Obtener los datos del formulario
    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $minuto_duracion = $_POST['minuto_duracion'];
    $fecha_estreno = $_POST['fecha_estreno'];

    // Actualizar el contenido en la base de datos
    $stmt = $conexion->prepare("UPDATE video SET titulo = :titulo, minuto_duracion = :minuto_duracion, fecha_estreno = :fecha_estreno WHERE id = :id AND tipo_video = :tipoContenido");

    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':minuto_duracion', $minuto_duracion);
    $stmt->bindParam(':fecha_estreno', $fecha_estreno);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':tipoContenido', $tipoContenido, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Contenido modificado con éxito.";
    } else {
        echo "Error al modificar el contenido.";
    }
}

// Función para eliminar un contenido
function borrarContenido($id, $tipoContenido)
{
    global $conexion;

    // Eliminar primero los registros relacionados en video_actor
    $stmt1 = $conexion->prepare("DELETE FROM video_actor WHERE video = :id");
    $stmt1->bindParam(':id', $id, PDO::PARAM_INT);

    // Ejecutar la eliminación de video_actor
    if ($stmt1->execute()) {
        // Luego eliminar el contenido de la tabla video
        $stmt2 = $conexion->prepare("DELETE FROM video WHERE id = :id AND tipo_video = :tipoContenido");
        $stmt2->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt2->bindParam(':tipoContenido', $tipoContenido, PDO::PARAM_INT);

        // Ejecutar la eliminación de video
        if ($stmt2->execute()) {
            echo "Contenido eliminado con éxito.";
        } else {
            echo "Error al eliminar el contenido de la tabla video."; 
        }
    } else {
        echo "Error al eliminar los registros de video_actor.";
    }
}

// Función para obtener el nombre del tipo de contenido (película, serie, documental)
function getTipoContenido($tipoContenido)
{
    switch ($tipoContenido) {
        case 1:
            return "Película";
        case 2:
            return "Serie";
        case 3:
            return "Documental";
        default:
            return "Desconocido";
    }
}
