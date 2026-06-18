<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $usuario = $data['usuario'] ?? null;
    $celular = $data['celular'] ?? null;
    $clave = $data['clave'] ?? null;
    $confirmar = $data['confirmar'] ?? null;

    if (!$usuario || !$celular || !$clave || !$confirmar) {
        echo respuesta(false, 'Todos los campos son requeridos');
        exit();
    }

    if (strlen($usuario) < 3) {
        echo respuesta(false, 'El usuario debe tener al menos 3 caracteres');
        exit();
    }

    if (strlen($clave) < 6) {
        echo respuesta(false, 'La contraseña debe tener al menos 6 caracteres');
        exit();
    }

    if ($clave !== $confirmar) {
        echo respuesta(false, 'Las contraseñas no coinciden');
        exit();
    }

    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE usuario = ? OR celular = ?");
    $stmt->execute([$usuario, $celular]);

    if ($stmt->fetch()) {
        echo respuesta(false, 'El usuario o celular ya está registrado');
        exit();
    }

    $clave_hash = password_hash($clave, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO usuarios (usuario, celular, clave) VALUES (?, ?, ?)");

    if ($stmt->execute([$usuario, $celular, $clave_hash])) {
        echo respuesta(true, 'Registro exitoso');
    } else {
        echo respuesta(false, 'Error al registrar');
    }
} else {
    echo respuesta(false, 'Método POST requerido');
}
?>
