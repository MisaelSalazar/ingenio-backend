<?php
require_once '../config/cors.php';
require_once '../config/database.php';
require_once '../config/auth.php';

header('Content-Type: application/json');

if (!isAdmin()) {
    http_response_code(401);
    echo json_encode(["error" => "Acceso no autorizado"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(["error" => "ID no proporcionado"]);
    exit;
}

// Eliminar imagen (opcional)
$stmtImg = $pdo->prepare("SELECT imagen FROM flayers WHERE id = ?");
$stmtImg->execute([$id]);
$flayer = $stmtImg->fetch(PDO::FETCH_ASSOC);
if ($flayer && file_exists('../' . $flayer['imagen'])) {
    unlink('../' . $flayer['imagen']);
}

// Eliminar de la base de datos
$stmt = $pdo->prepare("DELETE FROM flayers WHERE id = ?");
$stmt->execute([$id]);

echo json_encode(["success" => true]);
?>
