<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $id = $data['id'] ?? null;
    if (!$id) {
        echo respuesta(false, 'id del producto requerido');
        exit();
    }

    $stmt = $conn->prepare("
        UPDATE productos SET
            nombre = ?, taqueria = ?, pais_origen = ?, telefono = ?,
            nivel_picante = ?, tipo_tortilla = ?, variedad_carne = ?,
            perfil_sabor = ?, categoria = ?, porcion = ?, precio = ?, descripcion = ?
        WHERE id = ?
    ");

    $ok = $stmt->execute([
        $data['nombre'] ?? null,
        $data['taqueria'] ?? null,
        $data['pais_origen'] ?? null,
        $data['telefono'] ?? null,
        $data['nivel_picante'] ?? null,
        $data['tipo_tortilla'] ?? null,
        $data['variedad_carne'] ?? null,
        $data['perfil_sabor'] ?? null,
        $data['categoria'] ?? null,
        $data['porcion'] ?? null,
        $data['precio'] ?? 0,
        $data['descripcion'] ?? null,
        $id
    ]);

    if ($ok) {
        echo respuesta(true, 'Producto actualizado');
    } else {
        echo respuesta(false, 'Error al actualizar');
    }
} else {
    echo respuesta(false, 'Método POST requerido');
}
?>
