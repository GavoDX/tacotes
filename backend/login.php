<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $usuario = $data['usuario'] ?? null;
    $clave = $data['clave'] ?? null;

    if (!$usuario || !$clave) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Usuario y contraseña requeridos'
        ]);
        exit();
    }

    try {
        $stmt = $pdo->prepare("SELECT id, usuario, clave FROM usuarios WHERE usuario = ?");
        $stmt->execute([$usuario]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado && password_verify($clave, $resultado['clave'])) {
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Login exitoso',
                'usuario_id' => $resultado['id'],
                'usuario' => $resultado['usuario']
            ]);
        } else {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Usuario o contraseña incorrectos'
            ]);
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
