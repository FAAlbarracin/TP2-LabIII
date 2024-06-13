<?php
include_once '../db.php';

$database = new Database();
$db = $database->getConnection();

$nombre = isset($_GET['marca']) ? $_GET['marca'] : '';

$query = "SELECT * FROM marcas WHERE Marca LIKE :marca";
$stmt = $db->prepare($query);
$stmt->bindValue(':marca', '%' . $nombre . '%');
$stmt->execute();

$alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($alumnos);
?>
