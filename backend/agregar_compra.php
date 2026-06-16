<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $usuario_id = $data['usuario_id'] ?? null;
    $producto_id = $data['producto_id'] ?? null;
    $cantidad = $data['cantidad'] ?? 1;

    if (!$usuario_id || !$producto_id) {
        echo respuesta(false, 'usuario_id y producto_id requeridos');
        exit();
    }

    $stmt = $conn->prepare("SELECT id, cantidad FROM compras WHERE usuario_id = ? AND producto_id = ?");
    $stmt->execute([$usuario_id, $producto_id]);
    $compra_existente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($compra_existente) {
        $nueva_cantidad = $compra_existente['cantidad'] + $cantidad;
        $stmt = $conn->prepare("UPDATE compras SET cantidad = ? WHERE id = ?");
        $stmt->execute([$nueva_cantidad, $compra_existente['id']]);
        echo respuesta(true, 'Producto actualizado');
    } else {
        $stmt = $conn->prepare("INSERT INTO compras (usuario_id, producto_id, cantidad) VALUES (?, ?, ?)");
        if ($stmt->execute([$usuario_id, $producto_id, $cantidad])) {
            echo respuesta(true, 'Producto agregado');
        } else {
            echo respuesta(false, 'Error al agregar');
        }
    }
} else {
    echo respuesta(false, 'Método POST requerido');
}
?>
