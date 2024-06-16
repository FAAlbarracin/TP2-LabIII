<?php
include_once '../db.php';

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->marca)) {
    $database = new Database();
    $db = $database->getConnection();
    $queryFilter = "SELECT Activa FROM marcas WHERE Marca = :marca";
    $stmtFilter = $db->prepare($queryFilter);
    $stmtFilter->bindValue(":marca", $data->marca);
    $stmtFilter->execute();
    $result = $stmtFilter->fetch(PDO::FETCH_ASSOC);

    if ($result === false) {
        echo json_encode(array("message" => "La marca no existe"));
    } else {
        if (!$result['Activa']) {
            // Marca exists and is not active, proceed with the UPDATE
            echo json_encode(array("message" => "La marca ya fue eliminada"));
        } else {
            // Marca exists and is already active
            $updateQuery = "UPDATE marcas SET Activa = :active WHERE Marca = :marca";
            $updateStmt = $db->prepare($updateQuery);
            $updateStmt->bindValue(':active', false, PDO::PARAM_BOOL);
            $updateStmt->bindValue(':marca', $data->marca, PDO::PARAM_STR);

            if ($updateStmt->execute()) {
                echo json_encode(array("message" => "Marca eliminada"));
            } else {
                echo json_encode(array("message" => "Error de eliminación"));
            }
        }
    }
} else {
    echo json_encode(array("message" => "Datos incompletos."));
}
?>