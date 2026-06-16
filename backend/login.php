<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo respuesta(false, 'Método no permitido');
    exit();
}

$datos = json_decode(file_get_contents('php://input'), true);

if (!isset($datos['email'], $datos['password'])) {
    http_response_code(400);
    echo respuesta(false, 'Email y contraseña requeridos');
    exit();
}

$email = trim($datos['email']);
$password = $datos['password'];

$sql = "SELECT id, nombre, email, password, activo FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    http_response_code(401);
    echo respuesta(false, 'Credenciales inválidas');
    exit();
}

$usuario = $resultado->fetch_assoc();

if (!$usuario['activo']) {
    http_response_code(403);
    echo respuesta(false, 'Usuario desactivado');
    exit();
}

if (!password_verify($password, $usuario['password'])) {
    http_response_code(401);
    echo respuesta(false, 'Credenciales inválidas');
    exit();
}

$token = bin2hex(random_bytes(32));

http_response_code(200);
echo respuesta(true, 'Login exitoso', [
    'id' => $usuario['id'],
    'nombre' => $usuario['nombre'],
    'email' => $usuario['email'],
    'token' => $token
]);

$stmt->close();
$conn->close();
?>