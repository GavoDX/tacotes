<?php
// ========================================
// Configuración de base de datos PostgreSQL (Railway)
// ========================================
// Detecta automáticamente si corre DENTRO de Railway o en LOCAL (XAMPP)
$enRailway = getenv('RAILWAY_ENVIRONMENT') !== false;

if ($enRailway) {
    // Dentro de Railway: usa la red INTERNA
    define('DB_HOST', 'postgres.railway.internal');
    define('DB_PORT', '5432');
} else {
    // En local (XAMPP / pruebas desde tu PC): usa el host PÚBLICO
    // Saca estos valores de DATABASE_PUBLIC_URL en Railway (pestaña Variables)
    define('DB_HOST', 'monorail.proxy.rlwy.net'); // <-- CAMBIA por tu host público real
    define('DB_PORT', '43210');                    // <-- CAMBIA por tu puerto público real
}

define('DB_USER', 'postgres');
define('DB_PASS', 'YrnzMWmJCxevNmdiwiBlNVMFWxqWMZLd');
define('DB_NAME', 'railway');

// Configuración de API
define('API_URL', 'https://tu-proyecto.up.railway.app/backend');
define('SECRET_KEY', 'tu_clave_secreta_super_segura_2024');

// Headers CORS
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Manejo de OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Conexión a BD PostgreSQL
try {
    $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME;
    $conn = new PDO($dsn, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("SET NAMES utf8");
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión a BD: ' . $e->getMessage()]);
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

function obtener_usuario_id() {
    if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
        return null;
    }

    $token = str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']);

    // Aquí deberías validar el token JWT
    // Por ahora retornamos el ID del header (simplificado)
    return isset($_GET['usuario_id']) ? intval($_GET['usuario_id']) : null;
}
?>
