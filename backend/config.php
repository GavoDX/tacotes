<?php
// Configuración de la base de datos
// IMPORTANTE: Actualiza estas credenciales con las tuyas

define('DB_HOST', 'localhost');
define('DB_NAME', 'tu_bd');
define('DB_USER', 'usuario');
define('DB_PASS', 'contraseña');

// Conexión a PostgreSQL
try {
    $pdo = new PDO(
        'pgsql:host=' . DB_HOST . ';dbname=' . DB_NAME,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error de conexión a BD: ' . $e->getMessage()
    ]);
    exit();
}

// Headers para respuestas JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
?>
