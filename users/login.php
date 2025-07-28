<?php
require_once '../config/cors.php';
require_once '../config/database.php';

header('Content-Type: application/json');

// Leer datos del frontend
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['nombre']) || !isset($data['contrasenha'])) {
    http_response_code(400);
    echo json_encode(["error" => "Datos incompletos."]);
    exit;
}

// Buscar usuario por nombre
$sql = "SELECT * FROM usuarios WHERE nombre = :nombre LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute(['nombre' => $data['nombre']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar contraseña
if ($user && password_verify($data['contrasenha'], $user['contrasenha'])) {
    // Autenticación exitosa, devuelve un token simple
    echo json_encode([
        "success" => true,
        "token" => "token_admin", // Simple por ahora
        "nombre" => $user['nombre']
    ]);
} else {
    http_response_code(401);
    echo json_encode(["error" => "Credenciales incorrectas."]);
}
?>
