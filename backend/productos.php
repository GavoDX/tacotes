<?php
header('Content-Type: application/json');
$pdo = new PDO('pgsql:host=localhost;dbname=tu_bd', 'usuario', 'contraseña');

$stmt = $pdo->prepare("SELECT * FROM productos ORDER BY fecha_creacion DESC");
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'success' => true,
    'productos' => $productos
]);
?>
