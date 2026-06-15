<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $usuario_id = $data['usuario_id'] ?? null;
    $producto_id = $data['producto_id'] ?? null;
    $cantidad = $data['cantidad'] ?? 1;

    if (!$usuario_id || !$producto_id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'usuario_id y producto_id requeridos'
        ]);
        exit();
    }

    try {
        // Verificar si ya existe
        $stmt = $pdo->prepare("SELECT id, cantidad FROM compras WHERE usuario_id = ? AND producto_id = ?");
        $stmt->execute([$usuario_id, $producto_id]);
        $compra_existente = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($compra_existente) {
            $nueva_cantidad = $compra_existente['cantidad'] + $cantidad;
            $stmt = $pdo->prepare("UPDATE compras SET cantidad = ? WHERE id = ?");
            $stmt->execute([$nueva_cantidad, $compra_existente['id']]);
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Producto actualizado'
            ]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO compras (usuario_id, producto_id, cantidad, fecha_compra) VALUES (?, ?, ?, NOW())");
            if ($stmt->execute([$usuario_id, $producto_id, $cantidad])) {
                http_response_code(201);
                echo json_encode([
                    'success' => true,
                    'message' => 'Producto agregado'
                ]);
            } else {
                throw new Exception('Error al agregar el producto');
            }
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error en el servidor: ' . $e->getMessage()
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Método POST requerido'
    ]);
}
?>
