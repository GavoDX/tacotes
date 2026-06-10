<?php
header('Content-Type: application/json');
$pdo = new PDO('pgsql:host=localhost;dbname=tu_bd', 'usuario', 'contraseña');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $usuario_id = $data['usuario_id'] ?? null;
    $producto_id = $data['producto_id'] ?? null;
    $cantidad = $data['cantidad'] ?? 1;

    if (!$usuario_id || !$producto_id) {
        echo json_encode(['success' => false, 'message' => 'usuario_id y producto_id requeridos']);
        exit();
    }

    // Verificar si ya existe
    $stmt = $pdo->prepare("SELECT id, cantidad FROM compras WHERE usuario_id = ? AND producto_id = ?");
    $stmt->execute([$usuario_id, $producto_id]);
    $compra_existente = $stmt->fetch();

    if ($compra_existente) {
        $nueva_cantidad = $compra_existente['cantidad'] + $cantidad;
        $stmt = $pdo->prepare("UPDATE compras SET cantidad = ? WHERE id = ?");
        $stmt->execute([$nueva_cantidad, $compra_existente['id']]);
        echo json_encode(['success' => true, 'message' => 'Producto actualizado']);
    } else {
        $stmt = $pdo->prepare("INSERT INTO compras (usuario_id, producto_id, cantidad) VALUES (?, ?, ?)");
        if ($stmt->execute([$usuario_id, $producto_id, $cantidad])) {
            echo json_encode(['success' => true, 'message' => 'Producto agregado']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al agregar']);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método POST requerido']);
}
?>
