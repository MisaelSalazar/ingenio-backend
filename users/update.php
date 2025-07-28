<?php
require_once '../config/cors.php';
require_once '../config/database.php';
require_once '../config/auth.php';

header('Content-Type: application/json');

// Verifica token admin
//if (!isAdmin()) {
  //  http_response_code(401);
//    echo json_encode(["error" => "Acceso no autorizado."]);
  //  exit;
//}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id']) || !is_numeric($data['id'])) {
    http_response_code(400);
    echo json_encode(["error" => "ID inválido o no proporcionado."]);
    exit;
}

$campos = [];
$valores = [];

if (isset($data['nombre'])) {
    $campos[] = "nombre = ?";
    $valores[] = $data['nombre'];
}

if (isset($data['contraseña'])) {
    $campos[] = "contrasenha = ?";
    $valores[] = password_hash($data['contrasenha'], PASSWORD_DEFAULT);
}

if (isset($data['rol']) && in_array($data['rol'], ['admin', 'invitado'])) {
    $campos[] = "rol = ?";
    $valores[] = $data['rol'];
}

if (empty($campos)) {
    http_response_code(400);
    echo json_encode(["error" => "No se proporcionó ningún campo para actualizar."]);
    exit;
}

$valores[] = $data['id']; // al final va el ID para el WHERE

$sql = "UPDATE usuarios SET " . implode(", ", $campos) . " WHERE id = ?";
$stmt = $pdo->prepare($sql);

try {
    $stmt->execute($valores);
    echo json_encode(["success" => true, "message" => "Usuario actualizado correctamente."]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error en la base de datos: " . $e->getMessage()]);
}
?>
