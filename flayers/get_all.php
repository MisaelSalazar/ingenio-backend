<?php
require_once '../config/cors.php';
require_once '../config/database.php';

header('Content-Type: application/json');

$sql = "SELECT * FROM flayers ORDER BY fecha_creacion DESC";
$stmt = $pdo->query($sql);
$flayers = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($flayers);
?>
