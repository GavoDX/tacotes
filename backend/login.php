<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $usuario = $data['usuario'] ?? null;
    $clave = $data['clave'] ?? null;

    if (!$usuario || !$clave) {
        echo respuesta(false, 'Usuario y contraseña requeridos');
        exit();
    }

    $stmt = $conn->prepare("SELECT id, usuario, clave FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resultado && password_verify($clave, $resultado['clave'])) {
        echo respuesta(true, 'Login exitoso', [
            'usuario_id' => $resultado['id'],
            'usuario' => $resultado['usuario']
        ]);
    } else {
        echo respuesta(false, 'Usuario o contraseña incorrectos');
    }
} else {
    echo respuesta(false, 'Método POST requerido');
}
?>
