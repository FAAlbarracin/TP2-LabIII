<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Autos</title>
</head>

<body>
    <h2>Registro de Autos</h2>
    <form action="registro_de_autos.php" method="post">
        <label for="Dominio">Dominio</label>
        <input type="text" name="dominio" required>
        <label for="Marca">Marca:</label>
        <select name="marca" id="marca" required>
            <!-- Las opciones se cargarán dinámicamente con JavaScript -->
        </select>
        <label for="Modelo">Modelo</label>
        <input type="text" name="modelo" required>
        <label for="Fabricación">Año de Fabricación</label>
        <input type="number" name="año" required>
        <label for="Kilometraje">Kilometraje</label>
        <input type="number" name="km">
        <br><br>
        <!-- Otros campos del formulario -->
        <input type="submit" value="Enviar" name="agregar">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["agregar"])) {
        $dominio = trim($_POST['dominio']);
        $marca = trim($_POST['marca']);
        $modelo = trim($_POST['modelo']);
        $fabricacion = trim($_POST['año']);
        $km = trim($_POST['km']);
        $data = array(
            'dominio' => $dominio,
            'marca' => $marca,
            'modelo' => $modelo,
            'fabricacion' => $fabricacion,
            'kilometraje' => $km
        );

        $url = 'http://localhost/TP2-LabIII/server/autos/agregado_autos.php'; // URL del endpoint de la API
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
            echo '<p>Error al agregar el auto.</p>';
        } else {
            $response = json_decode($result, true);
            echo '<p>' . $response['message'] . '</p>';
        }
    }
    ?>
    <h2>Buscar Autos</h2><br><br>
    <form method="get" action="registro_de_autos.php">
        <input type="text" name="marca">
        <input type="text" name="dominio">
        <button type="submit">Buscar</button><br><br>
    </form>
    <?php
    $marca = isset($_GET['marca']) ? trim($_GET['marca']) : '';
    $dominio = isset($_GET['dominio']) ? trim($_GET['dominio']) : '';
    $url="";

    if (!empty($marca) || (!empty($dominio))) {
        $url = 'http://localhost/TP2-LabIII/server/autos/filtrado_autos.php?marca=' . urlencode($marca) ."&dominio=". urlencode($dominio);
    } else {
        $url = 'http://localhost/TP2-LabIII/server/autos/mostrado_autos.php';
    }
    $response = @file_get_contents($url);
    if ($response === FALSE) {
        echo '<p>Error al obtener la lista de autos.</p>';
    } else {
        
        $autos = json_decode($response, true);

        if (!empty($autos)) {
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Dominio</th>';
            echo '<th>Marca</th>';
            echo '<th>Modelo</th>';
            echo '<th>Año de fabricación</th>';
            echo '<th>Kilometraje</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            foreach ($autos as $auto) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($auto['Dominio']) . '</td>';
                echo '<td>' . htmlspecialchars($auto['Marca']) . '</td>';
                echo '<td>' . htmlspecialchars($auto['Modelo']) . '</td>';
                echo '<td>' . htmlspecialchars($auto['Fabricacion']) . '</td>';
                echo '<td>' . htmlspecialchars($auto['Kilometraje']) . '</td>';
            }
            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p>No hay coincidencias.</p>';
        }
    }
    ?>
    <!-- Incluir jQuery (opcional) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function () {
            // Realizar una solicitud AJAX para obtener las marcas desde el archivo PHP
            $.getJSON('../server/autos/mostrado_marcas.php', function (marcas) {
                // Iterar sobre las marcas y agregarlas al menú desplegable
                $.each(marcas, function (index, marca) {
                    $('#marca').append('<option value="' + marca.Marca + '">' + marca.Marca + '</option>');
                });
            });
        });
    </script>
</body>

</html>