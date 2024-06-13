<?php
include_once '../db.php';

$json = file_get_contents('php://input');

// Decodificar el JSON en un array asociativo
$data = json_decode($json, true);

echo "Raw JSON: $json";
echo "Decoded Data: ";
print_r($data);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(array("message" => "Error al decodificar el JSON: " . json_last_error_msg()));
    exit;
}

if (json_last_error() === JSON_ERROR_NONE && !empty($data['marcaOriginal']) && !empty($data['marcaFinal'])) {
    $original = $data['marcaOriginal'];
    $final = $data['marcaFinal'];

    echo "<p>Original: " . htmlspecialchars($original) . "</p>";
    echo "<p>Final: " . htmlspecialchars($final) . "</p>";

    $database = new Database();
    $db = $database->getConnection();
    $queryBusqueda = "SELECT * FROM marcas WHERE Marca = :marcaOriginal";
    $stmt = $db->prepare($queryBusqueda);
    $stmt->bindParam(':marcaOriginal', $original);
    $stmt->execute();
    $resultadoBusqueda = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($resultadoBusqueda)) {
        $query = "UPDATE marcas SET Marca = :marcaFinal WHERE Marca = :marcaOriginal";
        $stmt2 = $db->prepare($query);
        $stmt2->bindParam(":marcaFinal", $final);
        $stmt2->bindParam(":marcaOriginal", $original);
        if ($stmt2->execute()) {
            echo json_encode(array("message" => "Marca actualizada exitosamente."));
        } else {
            echo json_encode(array("message" => "Error al actualizar la marca."));
        }
    } else {
        echo json_encode(array("message" => "Datos incompletos."));
    }
} else {
    echo json_encode(array("message" => "No existe la marca que se desea modificar"));
}