<?php
require_once '../config/database.php';

$numero = '1001';
$passwordPlano = '123456';
$hash = password_hash($passwordPlano, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE usuarios SET contraseña = ? WHERE numero_trabajador = ?");
$stmt->execute([$hash, $numero]);

echo "Contraseña actualizada y hasheada para el usuario $numero";
?>
