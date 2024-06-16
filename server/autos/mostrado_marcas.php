<?php
include_once '../db.php';

$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM marcas WHERE Activa = :active";
$stmt = $db->prepare($query);
$stmt->bindValue(':active', '1', PDO::PARAM_INT);
$stmt->execute();

$marcas = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($marcas);
?>
