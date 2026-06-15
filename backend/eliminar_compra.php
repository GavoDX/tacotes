<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $compra_id = $data['compra_id'] ?? null;

    if (!$compra_id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'compra_id requerido'
        ]);
        exit();
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM compras WHERE id = ?");
        if ($stmt->execute([$compra_id])) {
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Producto eliminado'
            ]);
        } else {
            throw new Exception('Error al eliminar el producto');
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
