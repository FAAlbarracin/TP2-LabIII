<?php
include_once '../db.php';

$database = new Database();
$db = $database->getConnection();

$nombre = isset($_GET['nombre']) ? $_GET['nombre'] : '';
$dni = isset($_GET['dni']) ? $_GET['dni'] : '';

$query = "SELECT * FROM alumnos WHERE nombre LIKE :nombre AND dni LIKE :dni";
$stmt = $db->prepare($query);
$stmt->bindValue(':nombre', '%' . $nombre . '%');
$stmt->bindValue(':dni', '%' . $dni . '%');
$stmt->execute();

$alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($alumnos);
?>
