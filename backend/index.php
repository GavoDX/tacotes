<?php
require_once 'config.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = str_replace('/tacotes/backend', '', $path);
$path = trim($path, '/');

$rutas = [
    'registro' => 'registro.php',
    'login' => 'login.php',
    'productos' => 'productos.php',
    'compras' => 'compras.php',
];

if (empty($path)) {
    echo json_encode([
        'nombre' => 'API Tacotes',
        'version' => '1.0',
        'endpoints' => [
            'POST /registro' => 'Registrar nuevo usuario',
            'POST /login' => 'Iniciar sesión',
            'GET /productos' => 'Listar productos',
            'GET /productos?accion=obtener&id=1' => 'Obtener producto específico',
            'POST /productos?accion=crear' => 'Crear producto',
            'PUT /productos?accion=actualizar&id=1' => 'Actualizar producto',
            'DELETE /productos?accion=eliminar&id=1' => 'Eliminar producto',
            'GET /compras?usuario_id=1' => 'Listar compras del usuario',
            'GET /compras?accion=obtener&id=1' => 'Obtener detalles de compra',
            'POST /compras?accion=crear' => 'Crear nueva compra',
            'PUT /compras?accion=actualizar_estado&id=1' => 'Actualizar estado de compra',
            'DELETE /compras?accion=cancelar&id=1' => 'Cancelar compra',
        ]
    ]);
    exit();
}

$archivo_ruta = null;
foreach ($rutas as $ruta => $archivo) {
    if (strpos($path, $ruta) === 0) {
        $archivo_ruta = $archivo;
        break;
    }
}

if ($archivo_ruta && file_exists($archivo_ruta)) {
    require_once $archivo_ruta;
} else {
    http_response_code(404);
    echo json_encode([
        'error' => 'Endpoint no encontrado',
        'path' => $path
    ]);
}
?>