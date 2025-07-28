<?php
require_once '../config/cors.php';
require_once '../config/database.php';
require_once '../config/auth.php';

header('Content-Type: application/json');

if (!isAdmin()) {
    http_response_code(401);
    echo json_encode(["error" => "Acceso no autorizado."]);
    exit;
}

try {
    $stmt = $pdo->query("SELECT id, nombre, rol FROM usuarios ORDER BY id ASC");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($usuarios);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error al obtener usuarios: " . $e->getMessage()]);
}
?>
