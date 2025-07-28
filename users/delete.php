<?php
require_once '../config/cors.php';
require_once '../config/database.php';
require_once '../config/auth.php';

header('Content-Type: application/json');

// Leer ID desde el JSON
$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? null;

if (!$id || !is_numeric($id)) {
    http_response_code(400);
    echo json_encode(["error" => "ID inválido o no proporcionado."]);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["success" => true, "message" => "Usuario eliminado correctamente."]);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Usuario no encontrado."]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error al eliminar: " . $e->getMessage()]);
}

?>
