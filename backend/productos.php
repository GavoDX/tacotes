<?php
require 'config.php';

$stmt = $conn->prepare("SELECT * FROM productos ORDER BY fecha_creacion DESC");
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo respuesta(true, 'Productos obtenidos', $productos);
?>
