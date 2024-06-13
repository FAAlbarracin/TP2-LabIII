<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h2>Buscar Marcas</h2><br><br>
    <form method="get" action="registro_de_marcas.php">
        <input type="text" name="nombre">
        <button type="submit">Buscar</button><br><br>
    </form>
    <?php
    $nombre = isset($_GET['nombre']) ? $_GET['nombre'] : '';

    if (!empty($nombre)) {
        $url = 'http://localhost/TP2-LabIII/server/autos/filtrado_marcas.php?marca=' . urlencode($nombre);
    } else {
        $url = 'http://localhost/TP2-LabIII/server/autos/mostrado_marcas.php';
    }
    $response = @file_get_contents($url);
    if ($response === FALSE) {
        echo '<p>Error al obtener la lista de alumnos.</p>';
    } else {
        $alumnos = json_decode($response, true);

        if (!empty($alumnos)) {
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Marca</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            foreach ($alumnos as $alumno) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($alumno['Marca']) . '</td>';
            }
            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p>No hay alumnos registrados.</p>';
        }
    }
    ?>
    <h2>Agregar Marca</h2>
    <form method="post" action="registro_de_marcas.php">
        <input type="text" name="marca" required>
        <button type="submit" name="agregar">Agregar</button>
    </form>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["agregar"])) {
        $marca = $_POST['marca'];
        $data = array(
            'marca' => $marca,
        );

        $url = 'http://localhost/TP2-LabIII/server/autos/agregado_marcas.php'; // URL del endpoint de la API
        $options = array(
            'http' => array(
                'header' => "Content-Type: application/json\r\n",
                'method' => 'POST',
                'content' => json_encode($data),
            ),
        );

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        if ($result === FALSE) {
            echo '<p>Error al agregar la marca.</p>';
        } else {
            $response = json_decode($result, true);
            echo '<p>' . $response['message'] . '</p>';
        }
    }
    ?>

    <form method="post" action="registro_de_marcas.php">
        <div>
            <input type="text" name="marcaOriginal" id="marcaOriginal" required>
            <input type="text" name="marcaFinal" id="marcaFinal" required>
            <button name="modificar">Modificar</button>
        </div>
    </form>


    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["modificar"])) {
        $original = isset($_POST['marcaOriginal']) ? $_POST['marcaOriginal'] : '';
        $final = isset($_POST['marcaFinal']) ? $_POST['marcaFinal'] : '';

        $url = 'http://localhost/TP2-LabIII/server/autos/modificado_marcas.php';
        $data = array('marcaOriginal' => $original, 'marcaFinal' => $final);
        $options = array(
            'http' => array(
                'header' => "Content-type: application/json\r\n",
                'method' => 'POST',
                'content' => json_encode($data),
            ),
        );
        $context = stream_context_create($options);
        $result = @file_get_contents($url, false, $context);
        if ($result === FALSE) {
            echo '<p>Error al modificar la marca.</p>';
        } else {
            $response = json_decode($result, true);
            echo '<p>' . htmlspecialchars($response['message']) . '</p>';
            // Recargar la página para reflejar los cambios
    
        }
    }

    ?>
</body>

</html>