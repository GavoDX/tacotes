<?php
require 'config.php';

$usuario_id = obtener_usuario_id();

if (!$usuario_id) {
    echo respuesta(false, 'usuario_id requerido');
    exit();
}

$stmt = $conn->prepare("
    SELECT c.id, p.id as producto_id, p.nombre, p.taqueria, p.precio, p.variedad_carne, 
           p.tipo_tortilla, p.nivel_picante, p.categoria, p.porcion, c.cantidad, 
           (p.precio * c.cantidad) as total, c.fecha_compra
    FROM compras c
    JOIN productos p ON c.producto_id = p.id
    WHERE c.usuario_id = ?
    ORDER BY c.fecha_compra DESC
");
$stmt->execute([$usuario_id]);
$compras = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_general = 0;
foreach ($compras as $compra) {
    $total_general += $compra['total'];
}

echo respuesta(true, 'Compras obtenidas', [
    'compras' => $compras,
    'total' => $total_general
]);
?>
$conn->close();
?>
