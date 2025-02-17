<?php
include '../src/db.php';

// Mostrar lista de actores
function mostrarListaActores()
{
    global $conexion;

    try {
        $query = "SELECT id, nombre FROM actor ORDER BY id";
        $result = $conexion->query($query);

        if ($result->rowCount() > 0) {
            echo "<table border='1' cellpadding='10' cellspacing='0'>";
            echo "<thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Modificar</th>
                    </tr>
                  </thead>";
            echo "<tbody>";

            while ($fila = $result->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($fila['nombre']) . "</td>";
                echo "<td><a href='?action=formularioModificarActor&id=" . urlencode($fila['id']) . "'>Modificar</a></td>";
                echo "</tr>";
            }

            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p>No se encontraron actores.</p>";
        }
    } catch (PDOException $e) {
        echo "Error al recuperar los datos: " . $e->getMessage();
    }
}

// Formulario para insertar actor
function formularioInsertarActor()
{
    global $conexion;

    try {
        // Obtener la lista de videos
        $query = "SELECT id, titulo FROM video";
        $result = $conexion->query($query);

        echo "<form action='?action=insertarActor' method='POST' class='divForm'>
        <fieldset>
            <legend>Añadir Nuevo Actor</legend>

                  <label>Nombre del Actor:</label>
                  <input type='text' name='nombre' required><br><br>
                  <label>Seleccionar Película/Serie:</label>
                  <select name='video_id' required>
                      <option value=''>Seleccione una opción</option>";
        while ($fila = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='" . htmlspecialchars($fila['id']) . "'>" . htmlspecialchars($fila['titulo']) . "</option>";
        }
        echo "</select><br><br>
                  <input class='submitC' type='submit' value='Enviar'> </fieldset>
              </form>";
    } catch (Exception $e) {
        echo "Error al cargar el formulario: " . $e->getMessage();
    }
}

// Insertar actor
function insertarActor()
{
    global $conexion;

    try {
        $nombre = $_POST['nombre'];
        $videoId = $_POST['video_id'];

        if (empty($nombre)) {
            throw new Exception("El nombre del actor no puede estar vacío.");
        }

        if (empty($videoId)) {
            throw new Exception("Debe seleccionar una película o serie.");
        }

        // Obtener el último ID y calcular el nuevo
        $query = "SELECT MAX(id) AS max_id FROM actor";
        $result = $conexion->query($query);
        $fila = $result->fetch(PDO::FETCH_ASSOC);
        $nuevoId = $fila['max_id'] + 1;

        // Insertar el nuevo actor en la tabla `actor`
        $stmt = $conexion->prepare("INSERT INTO actor (id, nombre) VALUES (:id, :nombre)");
        $stmt->execute([
            ':id' => $nuevoId,
            ':nombre' => $nombre
        ]);

        // Insertar la relación en la tabla `video_actor`
        $stmt = $conexion->prepare("INSERT INTO video_actor (actor, video) VALUES (:actor_id, :video_id)");
        $stmt->execute([
            ':actor_id' => $nuevoId,
            ':video_id' => $videoId
        ]);

        echo "<p>Actor añadido con éxito y vinculado a la película/serie.</p>";
    } catch (Exception $e) {
        echo "Error al insertar actor: " . $e->getMessage();
    }
}

// Formulario para modificar actor
function formularioModificarActor($id)
{
    global $conexion;

    try {
        // Obtener los datos del actor
        $stmt = $conexion->prepare("SELECT nombre FROM actor WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $actor = $stmt->fetch(PDO::FETCH_ASSOC);

        // Obtener las películas o series en las que el actor está participando actualmente
        $stmt = $conexion->prepare("SELECT video.id, video.titulo FROM video 
                                    INNER JOIN video_actor ON video_actor.video = video.id 
                                    WHERE video_actor.actor = :actor_id");
        $stmt->execute([':actor_id' => $id]);
        $videos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Obtener todas las películas o series disponibles para asignar al actor
        $stmt = $conexion->prepare("SELECT id, titulo FROM video");
        $stmt->execute();
        $allVideos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($actor) {
            echo "<form action='?action=modificarActor' method='POST' class='divForm'>
            <fieldset>
            <legend>Modificar Actor</legend>
                      <input type='hidden' name='id' value='" . htmlspecialchars($id) . "'>
                      Nombre del Actor: <input type='text' name='nombre' value='" . htmlspecialchars($actor['nombre']) . "' required><br><br>";

            // Mostrar las películas/series en las que el actor está participando actualmente
            echo "Película/Serie/Documental actual en la que participa:<br>";
            foreach ($videos as $video) {
                echo "<p>" . htmlspecialchars($video['titulo']) . " (ID: " . htmlspecialchars($video['id']) . ")</p>";
            }

            // Mostrar todas las películas/series disponibles para cambiar
            echo "Seleccionar nueva película/serie:<br>";
            echo "<select name='nuevo_video_id' required>";

            foreach ($allVideos as $video) {
                echo "<option value='" . htmlspecialchars($video['id']) . "'>" . htmlspecialchars($video['titulo']) . "</option>";
            }

            echo "</select><br><br>";
            echo "<input class='submitC' type='submit' value='Enviar'>
                  </fieldset></form>";
        } else {
            echo "<p>Actor no encontrado.</p>";
        }
    } catch (PDOException $e) {
        echo "Error al recuperar los datos: " . $e->getMessage();
    }
}
// Modificar actor
function modificarActor()
{
    global $conexion;

    try {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $nuevo_video_id = $_POST['nuevo_video_id'];

        if (empty($nombre)) {
            throw new Exception("El nombre del actor no puede estar vacío.");
        }

        // Modificar el nombre del actor
        $stmt = $conexion->prepare("UPDATE actor SET nombre = :nombre WHERE id = :id");
        $stmt->execute([':nombre' => $nombre, ':id' => $id]);

        // Eliminar las relaciones anteriores del actor con los videos
        $stmt = $conexion->prepare("DELETE FROM video_actor WHERE actor = :actor_id");
        $stmt->execute([':actor_id' => $id]);

        // Añadir la nueva relación entre el actor y la nueva película/serie
        $stmt = $conexion->prepare("INSERT INTO video_actor (actor, video) VALUES (:actor_id, :video_id)");
        $stmt->execute([':actor_id' => $id, ':video_id' => $nuevo_video_id]);

        echo "<p>Actor modificado con éxito y nueva película/serie asignada.</p>";
    } catch (Exception $e) {
        echo "Error al modificar actor: " . $e->getMessage();
    }
}
