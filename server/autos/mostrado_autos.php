<?php
include_once '../db.php';

$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM autos";
$stmt = $db->prepare($query);
$stmt->execute();

$autos = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($autos);
?>
