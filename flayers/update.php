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

// Datos requeridos
$id = $_POST['id'] ?? null;
$titulo = $_POST['titulo'] ?? null;
$slug = $_POST['slug'] ?? null;
$video = $_POST['video'] ?? null;
$contenido = $_POST['contenido'] ?? null;

if (!$id || !$titulo || !$slug || !$contenido) {
    http_response_code(400);
    echo json_encode(["error" => "Datos incompletos"]);
    exit;
}

// Preparar campos para UPDATE
$campos = [
    'titulo' => $titulo,
    'slug' => $slug,
    'video' => $video,
    'contenido' => $contenido
];

// Manejar nueva imagen si se incluye
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
    $uploadDir = '../uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $imagenName = uniqid() . '_' . basename($_FILES['imagen']['name']);
    $targetPath = $uploadDir . $imagenName;

    if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $targetPath)) {
        http_response_code(500);
        echo json_encode(["error" => "Error al subir la nueva imagen"]);
        exit;
    }

    $campos['imagen'] = 'uploads/' . $imagenName;
}

// Generar consulta dinámica
$sql = "UPDATE flayers SET ";
$sql .= implode(', ', array_map(fn($k) => "$k = :$k", array_keys($campos)));
$sql .= " WHERE id = :id";

$stmt = $pdo->prepare($sql);
$campos['id'] = $id;

try {
    $stmt->execute($campos);
    echo json_encode(["success" => true]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error en la base de datos", "detalle" => $e->getMessage()]);
}
?>
