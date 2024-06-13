<?php
include_once '../db.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->marca)) {
    $database = new Database();
    $db = $database->getConnection();

    $query = "INSERT INTO marcas (Marca) VALUES (:marca)";
    $stmt = $db->prepare($query);

    $stmt->bindParam(":marca", $data->marca);

    if($stmt->execute()) {
        echo json_encode(array("message" => "Marca agregada exitosamente."));
    } else {
        echo json_encode(array("message" => "Error al agregar la marca."));
    }
} else {
    echo json_encode(array("message" => "Datos incompletos."));
}
?>
