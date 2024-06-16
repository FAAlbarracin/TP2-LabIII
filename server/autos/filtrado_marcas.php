<?php
include_once '../db.php';

$database = new Database();
$db = $database->getConnection();

$nombre = isset($_GET['marca']) ? $_GET['marca'] : '';

$query = "SELECT * FROM marcas WHERE Marca LIKE :marca AND Activa = :active";
$stmt = $db->prepare($query);
$stmt->bindValue(':marca', '%' . $nombre . '%', PDO::PARAM_STR);
$stmt->bindValue(':active', 1, PDO::PARAM_INT);
$stmt->execute();

$alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($alumnos);
?>
