<?php
require 'config.php';

// Test de conexión
try {
    $stmt = $conn->prepare("SELECT 1");
    $stmt->execute();
    echo "✅ Conexión OK\n";
} catch (Exception $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "\n";
    exit();
}

// Test de inserción
$usuario = 'testuser' . time();
$celular = '1234567890';
$clave = password_hash('password123', PASSWORD_BCRYPT);

try {
    $stmt = $conn->prepare("INSERT INTO usuarios (usuario, celular, clave) VALUES (?, ?, ?)");
    $stmt->execute([$usuario, $celular, $clave]);
    echo "✅ Usuario insertado: $usuario\n";
} catch (Exception $e) {
    echo "❌ Error al insertar: " . $e->getMessage() . "\n";
}

// Test de lectura
try {
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($resultado) {
        echo "✅ Usuario encontrado: " . json_encode($resultado) . "\n";
    } else {
        echo "❌ Usuario no encontrado\n";
    }
} catch (Exception $e) {
    echo "❌ Error al leer: " . $e->getMessage() . "\n";
}
?>
