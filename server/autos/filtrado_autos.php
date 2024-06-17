<?php
include_once '../db.php';

$database = new Database();
$db = $database->getConnection();
$marca = isset($_GET['marca']) ? $_GET['marca'] : '';
$dominio = isset($_GET['dominio']) ? $_GET['dominio'] : '';

$query = "SELECT * FROM autos WHERE 1=1";

if (!empty($marca)) {
    $query .= " AND Marca LIKE :marca";
}
if (!empty($dominio)) {
    $query .= " AND Dominio LIKE :dominio";
}

$stmt = $db->prepare($query);

if (!empty($marca)) {
    $stmt->bindValue(':marca', '%' . $marca . '%', PDO::PARAM_STR);
}
if (!empty($dominio)) {
    $stmt->bindValue(':dominio', '%' . $dominio . '%', PDO::PARAM_STR);
}

$stmt->execute();

$autos = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($autos);
?>

