<?php
require 'config.php';

$usuario_id = isset($_GET['usuario_id']) ? intval($_GET['usuario_id']) : null;

if (!$usuario_id) {
    echo respuesta(false, 'usuario_id requerido');
    exit();
}

$stmt = $conn->prepare("SELECT * FROM productos WHERE usuario_id = ? ORDER BY fecha_creacion DESC");
$stmt->execute([$usuario_id]);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo respuesta(true, 'Productos obtenidos', $productos);
?>
