<?php
header('Content-Type: application/json');
$pdo = new PDO('pgsql:host=localhost;dbname=tu_bd', 'usuario', 'contraseña');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $compra_id = $data['compra_id'] ?? null;
    $usuario_id = $data['usuario_id'] ?? null;

    if (!$compra_id || !$usuario_id) {
        echo json_encode(['success' => false, 'message' => 'compra_id y usuario_id requeridos']);
        exit();
    }

    $stmt = $pdo->prepare("DELETE FROM compras WHERE id = ? AND usuario_id = ?");
    
    if ($stmt->execute([$compra_id, $usuario_id])) {
        echo json_encode(['success' => true, 'message' => 'Producto eliminado']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método POST requerido']);
}
?>
