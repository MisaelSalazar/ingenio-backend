<?php
require_once '../config/cors.php';
require_once '../config/database.php';
require_once '../config/auth.php';

header('Content-Type: application/json');

// Validar token de administrador
if (!isAdmin()) {
    http_response_code(401);
    echo json_encode(["error" => "Acceso no autorizado."]);
    exit;
}

// Aceptar solo método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
    exit;
}

// Obtener datos desde FormData
$nombre = $_POST['nombre'] ?? '';
$contraseña = $_POST['contrasenha'] ?? '';
$rol = $_POST['rol'] ?? '';

// Validar campos
if (!$nombre || !$contraseña || !in_array($rol, ['admin', 'invitado'])) {
    http_response_code(400);
    echo json_encode(["error" => "Datos inválidos o incompletos."]);
    exit;
}

try {
    // Verificar si el usuario ya existe
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE nombre = ?");
    $stmt->execute([$nombre]);
    if ($stmt->fetchColumn() > 0) {
        http_response_code(409);
        echo json_encode(["error" => "El usuario ya existe."]);
        exit;
    }

    // Hashear contraseña
    $hashedPassword = password_hash($contraseña, PASSWORD_DEFAULT);

    // Insertar usuario
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, contrasenha, rol) VALUES (?, ?, ?)");
    $stmt->execute([
        $nombre,
        $hashedPassword,
        $rol
    ]);

    echo json_encode(["success" => true, "message" => "Usuario creado exitosamente."]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error en la base de datos: " . $e->getMessage()]);
}
?>
