<?php
header('Content-Type: application/json');
$pdo = new PDO('pgsql:host=localhost;dbname=tu_bd', 'usuario', 'contraseña');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $usuario = $data['usuario'] ?? null;
    $clave = $data['clave'] ?? null;

    if (!$usuario || !$clave) {
        echo json_encode(['success' => false, 'message' => 'Usuario y contraseña requeridos']);
        exit();
    }

    $stmt = $pdo->prepare("SELECT id, usuario FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $resultado = $stmt->fetch();

    if ($resultado && password_verify($clave, $resultado['clave'])) {
        echo json_encode([
            'success' => true,
            'message' => 'Login exitoso',
            'usuario_id' => $resultado['id'],
            'usuario' => $resultado['usuario']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Usuario o contraseña incorrectos']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método POST requerido']);
}
?>
