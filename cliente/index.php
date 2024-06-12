<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Alumnos</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1 class="my-4">Lista de Alumnos</h1>

        <form method="get" action="index.php">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre">
            </div>
            <div class="form-group">
                <label for="dni">DNI</label>
                <input type="text" class="form-control" id="dni" name="dni">
            </div>
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </form>

        <?php
        $nombre = isset($_GET['nombre']) ? $_GET['nombre'] : '';
        $dni = isset($_GET['dni']) ? $_GET['dni'] : '';

        // Verificar si se usan filtros
        if (!empty($nombre) || !empty($dni)) {
            $url = 'http://localhost/TP2-LabIII/server/alumnos/filtrado.php?nombre=' . urlencode($nombre) . '&dni=' . urlencode($dni);
        } else {
            $url = 'http://localhost/TP2-LabIII/server/alumnos/mostrar.php';
        }

        // Obtener la respuesta de la API
        $response = @file_get_contents($url);

        // Verificar si la solicitud fue exitosa
        if ($response === FALSE) {
            echo '<p>Error al obtener la lista de alumnos.</p>';
        } else {
            $alumnos = json_decode($response, true);

            if (!empty($alumnos)) {
                echo '<table class="table table-bordered">';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Legajo</th>';
                echo '<th>Nombre</th>';
                echo '<th>DNI</th>';
                echo '<th>Teléfono</th>';
                echo '<th>Email</th>';
                echo '<th>Acciones</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                foreach ($alumnos as $alumno) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($alumno['legajo']) . '</td>';
                    echo '<td>' . htmlspecialchars($alumno['nombre']) . '</td>';
                    echo '<td>' . htmlspecialchars($alumno['dni']) . '</td>';
                    echo '<td>' . htmlspecialchars($alumno['telefono']) . '</td>';
                    echo '<td>' . htmlspecialchars($alumno['email']) . '</td>';
                    echo '<td>
                            <form method="post" action="index.php" onsubmit="return confirm(\'¿Estás seguro de que quieres eliminar este alumno?\');" style="display:inline;">
                                <input type="hidden" name="legajo" value="' . htmlspecialchars($alumno['legajo']) . '">
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editModal" 
                                    data-legajo="' . htmlspecialchars($alumno['legajo']) . '" 
                                    data-nombre="' . htmlspecialchars($alumno['nombre']) . '" 
                                    data-dni="' . htmlspecialchars($alumno['dni']) . '" 
                                    data-telefono="' . htmlspecialchars($alumno['telefono']) . '" 
                                    data-email="' . htmlspecialchars($alumno['email']) . '">Actualizar</button>
                          </td>';
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<p>No hay alumnos registrados.</p>';
            }
        }

        // Manejo de la solicitud de eliminación
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['legajo'])) {
            $legajo = $_POST['legajo'];
            $url = 'http://localhost/TP2-LabIII/server/alumnos/borrar.php';
            
            $data = array('legajo' => $legajo);
            $options = array(
                'http' => array(
                    'header'  => "Content-type: application/json\r\n",
                    'method'  => 'DELETE',
                    'content' => json_encode($data),
                ),
            );

            $context  = stream_context_create($options);
            $result = @file_get_contents($url, false, $context);

            if ($result === FALSE) {
                echo '<p>Error al eliminar el alumno.</p>';
            } else {
                $response = json_decode($result, true);
                echo '<p>' . htmlspecialchars($response['message']) . '</p>';
                // Recargar la página para reflejar los cambios
                echo '<meta http-equiv="refresh" content="0">';
                }
            } elseif (isset($_POST['update'])) {
                $legajo = $_POST['legajo'];
                $nombre = $_POST['nombre'];
                $dni = $_POST['dni'];
                $telefono = $_POST['telefono'];
                $email = $_POST['email'];
                $url = 'http://localhost/TP2-LabIII/server/alumnos/actualizar.php';

                $data = array(
                    'legajo' => $legajo,
                    'nombre' => $nombre,
                    'dni' => $dni,
                    'telefono' => $telefono,
                    'email' => $email
                );
                $options = array(
                    'http' => array(
                        'header'  => "Content-type: application/json\r\n",
                        'method'  => 'PUT',
                        'content' => json_encode($data),
                    ),
                );

                $context  = stream_context_create($options);
                $result = @file_get_contents($url, false, $context);

                if ($result === FALSE) {
                    echo '<p>Error al actualizar el alumno.</p>';
                } else {
                    $response = json_decode($result, true);
                    echo '<p>' . htmlspecialchars($response['message']) . '</p>';
                    // Recargar la página para reflejar los cambios
                    echo '<meta http-equiv="refresh" content="0">';
                }
            }
        
        ?>

        <!-- Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Actualizar Alumno</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="index.php">
                            <input type="hidden" name="legajo" id="edit-legajo">
                            <div class="form-group">
                                <label for="edit-nombre">Nombre</label>
                                <input type="text" class="form-control" id="edit-nombre" name="nombre">
                            </div>
                            <div class="form-group">
                                <label for="edit-dni">DNI</label>
                                <input type="text" class="form-control" id="edit-dni" name="dni">
                            </div>
                            <div class="form-group">
                                <label for="edit-telefono">Teléfono</label>
                                <input type="text" class="form-control" id="edit-telefono" name="telefono">
                            </div>
                            <div class="form-group">
                                <label for="edit-email">Email</label>
                                <input type="email" class="form-control" id="edit-email" name="email">
                            </div>
                            <button type="submit" name="update" class="btn btn-primary">Actualizar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
            $('#editModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var legajo = button.data('legajo');
                var nombre = button.data('nombre');
                var dni = button.data('dni');
                var telefono = button.data('telefono');
                var email = button.data('email');

                var modal = $(this);
                modal.find('#edit-legajo').val(legajo);
                modal.find('#edit-nombre').val(nombre);
                modal.find('#edit-dni').val(dni);
                modal.find('#edit-telefono').val(telefono);
                modal.find('#edit-email').val(email);
            });
        </script>
    </div>
</body>
</html>
