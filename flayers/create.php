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

// Verificar datos
$titulo = $_POST['titulo'] ?? '';
$slug = $_POST['slug'] ?? '';
$video = $_POST['video'] ?? null;
$contenido = $_POST['contenido'] ?? '';

if (!$titulo || !$slug || !$contenido || !isset($_FILES['imagen'])) {
    http_response_code(400);
    echo json_encode(["error" => "Datos incompletos"]);
    exit;
}

// Guardar imagen
$uploadDir = '../uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}
$imagenName = uniqid() . '_' . basename($_FILES['imagen']['name']);
$targetPath = $uploadDir . $imagenName;

if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $targetPath)) {
    http_response_code(500);
    echo json_encode(["error" => "Error al subir la imagen"]);
    exit;
}



// Insertar en la base de datos
$sql = "INSERT INTO flayers (titulo, slug, imagen, video, contenido) 
        VALUES (:titulo, :slug, :imagen, :video, :contenido)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'titulo' => $titulo,
    'slug' => $slug,
    'imagen' => 'uploads/' . $imagenName,
    'video' => $video,
    'contenido' => $contenido
]);

echo json_encode(["success" => true]);
?>
