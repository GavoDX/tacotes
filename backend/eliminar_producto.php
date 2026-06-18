<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $compra_id = $data['compra_id'] ?? null;
    $usuario_id = $data['usuario_id'] ?? null;

    if (!$compra_id || !$usuario_id) {
        echo respuesta(false, 'compra_id y usuario_id requeridos');
        exit();
    }

    $stmt = $conn->prepare("DELETE FROM compras WHERE id = ? AND usuario_id = ?");
    
    if ($stmt->execute([$compra_id, $usuario_id])) {
        echo respuesta(true, 'Producto eliminado');
    } else {
        echo respuesta(false, 'Error al eliminar');
    }
} else {
    echo respuesta(false, 'Método POST requerido');
}
?>
