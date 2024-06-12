<?php
include_once '../db.php';

$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM alumnos";
$stmt = $db->prepare($query);
$stmt->execute();

$alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($alumnos);
?>
