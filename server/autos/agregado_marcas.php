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
        $query = "INSERT INTO marcas (Marca, Activa) VALUES (:marca, :active)";
        $stmt = $db->prepare($query);

        $stmt->bindValue(":marca", $data->marca);
        $stmt->bindValue(':active', true, PDO::PARAM_BOOL);

        if ($stmt->execute()) {
            echo json_encode(array("message" => "Marca agregada exitosamente."));
        } else {
            echo json_encode(array("message" => "Error al agregar la marca."));
        }
    } else {
        if (!$result['Activa']) {
            // Marca exists and is not active, proceed with the UPDATE
            $updateQuery = "UPDATE marcas SET Activa = :active WHERE Marca = :marca";
            $updateStmt = $db->prepare($updateQuery);
            $updateStmt->bindValue(':active', true, PDO::PARAM_BOOL);
            $updateStmt->bindValue(':marca', $data->marca, PDO::PARAM_STR);

            if ($updateStmt->execute()) {
                echo json_encode(array("message" => "Marca reactivada"));
            } else {
                echo json_encode(array("message" => "Error de reactivación"));;
            }
        } else {
            // Marca exists and is already active
            echo json_encode(array("message" => "La marca ya existe y está activa"));;
        }
    }


} else {
    echo json_encode(array("message" => "Datos incompletos."));
}
?>