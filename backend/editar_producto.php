<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $id = $data['id'] ?? null;
    if (!$id) {
        echo respuesta(false, 'id del producto requerido');
        exit();
    }

    try {
        $stmt = $conn->prepare("
            UPDATE productos SET
                nombre = ?, taqueria = ?, pais_origen = ?, telefono = ?,
                nivel_picante = ?, tipo_tortilla = ?, variedad_carne = ?,
                perfil_sabor = ?, categoria = ?, porcion = ?
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
            $id
        ]);

        if ($ok) {
            echo respuesta(true, 'Producto actualizado');
        } else {
            echo respuesta(false, 'No se pudo actualizar');
        }
    } catch (Exception $e) {
        // Devolvemos el error real como JSON limpio (no HTML)
        echo respuesta(false, 'Error BD: ' . $e->getMessage());
    }
} else {
    echo respuesta(false, 'Método POST requerido');
}
?>
