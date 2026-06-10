<?php
header('Content-Type: application/json');
$pdo = new PDO('pgsql:host=localhost;dbname=tu_bd', 'usuario', 'contraseña');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $usuario = $data['usuario'] ?? null;
    $celular = $data['celular'] ?? null;
    $clave = $data['clave'] ?? null;
    $confirmar = $data['confirmar'] ?? null;

    if (!$usuario || !$celular || !$clave || !$confirmar) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son requeridos']);
        exit();
    }

    if (strlen($usuario) < 3) {
        echo json_encode(['success' => false, 'message' => 'El usuario debe tener al menos 3 caracteres']);
        exit();
    }

    if (strlen($clave) < 6) {
        echo json_encode(['success' => false, 'message' => 'La contraseña debe tener al menos 6 caracteres']);
        exit();
    }

    if ($clave !== $confirmar) {
        echo json_encode(['success' => false, 'message' => 'Las contraseñas no coinciden']);
        exit();
    }

    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = ? OR celular = ?");
    $stmt->execute([$usuario, $celular]);
    
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'El usuario o celular ya está registrado']);
        exit();
    }

    $clave_hash = password_hash($clave, PASSWORD_BCRYPT);
    
    $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, celular, clave) VALUES (?, ?, ?)");
    
    if ($stmt->execute([$usuario, $celular, $clave_hash])) {
        echo json_encode(['success' => true, 'message' => 'Registro exitoso']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al registrar']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método POST requerido']);
}
?>
