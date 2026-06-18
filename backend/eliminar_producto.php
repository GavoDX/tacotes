<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'] ?? null;

    if (!$id) {
        echo respuesta(false, 'id requerido');
        exit();
    }

    $stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");

    if ($stmt->execute([$id])) {
        echo respuesta(true, 'Producto eliminado');
    } else {
        echo respuesta(false, 'Error al eliminar');
    }
} else {
    echo respuesta(false, 'Método POST requerido');
}
?>
