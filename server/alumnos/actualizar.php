<?php
include_once '../db.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->legajo) && !empty($data->nombre) && !empty($data->dni)) {
    $database = new Database();
    $db = $database->getConnection();

    $query = "UPDATE alumnos SET nombre = :nombre, dni = :dni, telefono = :telefono, email = :email WHERE legajo = :legajo";
    $stmt = $db->prepare($query);

    $stmt->bindParam(":nombre", $data->nombre);
    $stmt->bindParam(":dni", $data->dni);
    $stmt->bindParam(":telefono", $data->telefono);
    $stmt->bindParam(":email", $data->email);
    $stmt->bindParam(":legajo", $data->legajo);

    if($stmt->execute()) {
        echo json_encode(array("message" => "Alumno actualizado exitosamente."));
    } else {
        echo json_encode(array("message" => "Error al actualizar el alumno."));
    }
} else {
    echo json_encode(array("message" => "Datos incompletos."));
}
?>
