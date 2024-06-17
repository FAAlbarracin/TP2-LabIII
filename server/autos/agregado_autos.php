<?php
include_once '../db.php';

$data = json_decode(file_get_contents("php://input"));

if (!empty($data)) {
    $database = new Database();
    $db = $database->getConnection();
    $queryFilter = "SELECT * FROM autos WHERE Dominio = :dominio";
    $stmtFilter = $db->prepare($queryFilter);
    $stmtFilter->bindValue(":dominio", $data->dominio);
    $stmtFilter->execute();
    $result = $stmtFilter->fetch(PDO::FETCH_ASSOC);

    if ($result === false) {
        $query = "INSERT INTO autos (Dominio, Marca, Modelo, Fabricacion, Kilometraje) VALUES (:dominio, :marca, :modelo, :fabricacion, :km)";
        $stmt = $db->prepare($query);

        $stmt->bindValue(":dominio", $data->dominio, PDO::PARAM_STR);
        $stmt->bindValue(':marca', $data->marca, PDO::PARAM_STR);
        $stmt->bindValue(':modelo', $data->modelo, PDO::PARAM_STR);
        $stmt->bindValue('fabricacion', $data->fabricacion, PDO::PARAM_INT);
        $stmt->bindValue('km', $data->kilometraje, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(array("message" => "Auto agregado exitosamente."));
        } else {
            echo json_encode(array("message" => "Error al agregar la marca."));
        }
    } else {
        echo json_encode(array("message" => "Ya existe un auto con este dominio"));
        ;
    }
} else {
    echo json_encode(array("message" => "Datos incompletos."));
}
?>