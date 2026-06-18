<?php
// ========================================
// Configuración PostgreSQL (Railway)
// ========================================
// Railway inyecta estas variables automáticamente.
// Los valores de respaldo son solo para pruebas locales.

define('DB_HOST', getenv('PGHOST') ?: 'zephyr.proxy.rlwy.net');
define('DB_PORT', getenv('PGPORT') ?: '11187');
define('DB_USER', getenv('PGUSER') ?: 'postgres');
define('DB_PASS', getenv('PGPASSWORD') ?: 'CAMBIA_ESTA_CLAVE');
define('DB_NAME', getenv('PGDATABASE') ?: 'railway');

// Headers CORS
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Conexión PDO PostgreSQL
try {
    $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME;
    $conn = new PDO($dsn, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['exito' => false, 'mensaje' => 'Error de conexión: ' . $e->getMessage()]);
    exit();
}

// Funciones auxiliares
function respuesta($exito, $mensaje, $datos = null) {
    return json_encode([
        'exito' => $exito,
        'mensaje' => $mensaje,
        'datos' => $datos
    ]);
}
?>
