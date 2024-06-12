<?php
include_once '../db.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->legajo)) {
    $database = new Database();
    $db = $database->getConnection();

    $query = "DELETE FROM alumnos WHERE legajo = :legajo";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":legajo", $data->legajo);

    if($stmt->execute()) {
        echo json_encode(array("message" => "Alumno borrado exitosamente."));
    } else {
        echo json_encode(array("message" => "Error al borrar el alumno."));
    }
} else {
    echo json_encode(array("message" => "Datos incompletos."));
}
?>
