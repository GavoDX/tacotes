<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $usuario_id = $data['usuario_id'] ?? null;
    $nombre = $data['nombre'] ?? null;

    if (!$usuario_id || !$nombre) {
        echo respuesta(false, 'usuario_id y nombre son requeridos');
        exit();
    }

    $stmt = $conn->prepare("
        INSERT INTO productos
        (usuario_id, nombre, taqueria, pais_origen, telefono, nivel_picante,
         tipo_tortilla, variedad_carne, perfil_sabor, categoria, porcion)
        VALUES (?,?,?,?,?,?,?,?,?,?,?)
    ");

    $ok = $stmt->execute([
        $usuario_id,
        $nombre,
        $data['taqueria'] ?? null,
        $data['pais_origen'] ?? null,
        $data['telefono'] ?? null,
        $data['nivel_picante'] ?? null,
        $data['tipo_tortilla'] ?? null,
        $data['variedad_carne'] ?? null,
        $data['perfil_sabor'] ?? null,
        $data['categoria'] ?? null,
        $data['porcion'] ?? null
    ]);

    if ($ok) {
        echo respuesta(true, 'Taco guardado');
    } else {
        echo respuesta(false, 'Error al guardar');
    }
} else {
    echo respuesta(false, 'Método POST requerido');
}
?>
