<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo respuesta(false, 'Método no permitido');
    exit();
}

$datos = json_decode(file_get_contents('php://input'), true);

if (!isset($datos['nombre'], $datos['email'], $datos['password'])) {
    http_response_code(400);
    echo respuesta(false, 'Faltan campos requeridos: nombre, email, password');
    exit();
}

$nombre = trim($datos['nombre']);
$email = trim($datos['email']);
$password = $datos['password'];
$telefono = isset($datos['telefono']) ? trim($datos['telefono']) : '';
$direccion = isset($datos['direccion']) ? trim($datos['direccion']) : '';

if (strlen($nombre) < 3) {
    http_response_code(400);
    echo respuesta(false, 'El nombre debe tener al menos 3 caracteres');
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo respuesta(false, 'Email inválido');
    exit();
}

if (strlen($password) < 6) {
    http_response_code(400);
    echo respuesta(false, 'La contraseña debe tener al menos 6 caracteres');
    exit();
}

$sql = "SELECT id FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    http_response_code(409);
    echo respuesta(false, 'El email ya está registrado');
    exit();
}

$password_hash = password_hash($password, PASSWORD_BCRYPT);

$sql = "INSERT INTO usuarios (nombre, email, password, telefono, direccion) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $nombre, $email, $password_hash, $telefono, $direccion);

if ($stmt->execute()) {
    $usuario_id = $conn->insert_id;
    http_response_code(201);
    echo respuesta(true, 'Usuario registrado exitosamente', [
        'id' => $usuario_id,
        'nombre' => $nombre,
        'email' => $email
    ]);
} else {
    http_response_code(500);
    echo respuesta(false, 'Error al registrar usuario: ' . $conn->error);
}

$stmt->close();
$conn->close();
?>