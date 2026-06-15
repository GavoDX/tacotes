<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $usuario_id = $_GET['usuario_id'] ?? null;

    if (!$usuario_id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'usuario_id requerido'
        ]);
        exit();
    }

    try {
        $stmt = $pdo->prepare("
            SELECT c.id, p.id as producto_id, p.nombre, p.taqueria, p.precio, 
                   p.variedad_carne, p.tipo_tortilla, p.nivel_picante, p.categoria, 
                   p.porcion, c.cantidad, (p.precio * c.cantidad) as total, c.fecha_compra
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

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'compras' => $compras,
            'total' => $total_general
        ]);
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
        'message' => 'Método GET requerido'
    ]);
}
?>
