<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $usuario = $data['usuario'] ?? null;
    $clave = $data['clave'] ?? null;
    $email = $data['email'] ?? null;

    if (!$usuario || !$clave) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Usuario y contraseña requeridos'
        ]);
        exit();
    }

    try {
        // Verificar si el usuario ya existe
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = ?");
        $stmt->execute([$usuario]);
        if ($stmt->fetch()) {
            http_response_code(409);
            echo json_encode([
                'success' => false,
                'message' => 'El usuario ya existe'
            ]);
            exit();
        }

        // Crear nuevo usuario
        $clave_hash = password_hash($clave, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, clave, email) VALUES (?, ?, ?)");
        
        if ($stmt->execute([$usuario, $clave_hash, $email])) {
            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => 'Usuario registrado exitosamente'
            ]);
        } else {
            throw new Exception('Error al registrar el usuario');
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
