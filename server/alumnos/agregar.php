<?php
include_once '../db.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->nombre) && !empty($data->dni)) {
    $database = new Database();
    $db = $database->getConnection();

    $query = "INSERT INTO alumnos (nombre, dni, telefono, email) VALUES (:nombre, :dni, :telefono, :email)";
    $stmt = $db->prepare($query);

    $stmt->bindParam(":nombre", $data->nombre);
    $stmt->bindParam(":dni", $data->dni);
    $stmt->bindParam(":telefono", $data->telefono);
    $stmt->bindParam(":email", $data->email);

    if($stmt->execute()) {
        echo json_encode(array("message" => "Alumno agregado exitosamente."));
    } else {
        echo json_encode(array("message" => "Error al agregar el alumno."));
    }
} else {
    echo json_encode(array("message" => "Datos incompletos."));
}
?>
