<?php
header('Content-Type: application/json');

 = getenv('DATABASE_URL');

if (!) {
    echo json_encode(['error' => 'DATABASE_URL no configurada']);
    exit;
}

try {
     = new PDO();
    echo json_encode(['status' => 'ok', 'message' => 'Conectado a la BD']);
} catch (Exception ) {
    echo json_encode(['error' => ->getMessage()]);
}
?>
