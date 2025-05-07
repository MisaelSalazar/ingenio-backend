<?php
header('Content-Type: application/json');
require_once '../config/database.php';

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->numero_trabajador) || !isset($data->contraseña)) {
    http_response_code(400);
    echo json_encode(["error" => "Datos incompletos."]);
    exit;
}

$sql = "SELECT u.contraseña, e.id, e.nombre_completo FROM usuarios u
        JOIN empleados e ON u.numero_trabajador = e.numero_trabajador
        WHERE u.numero_trabajador = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$data->numero_trabajador]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($data->contraseña, $user['contraseña'])) {
    echo json_encode([
        "success" => true,
        "id" => $user['id'],
        "nombre" => $user['nombre_completo']
    ]);
} else {
    http_response_code(401);
    echo json_encode(["error" => "Credenciales incorrectas."]);
}
?>
