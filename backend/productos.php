<?php
require_once 'config.php';

$metodo = $_SERVER['REQUEST_METHOD'];
$accion = isset($_GET['accion']) ? $_GET['accion'] : 'listar';

if ($metodo === 'GET' && $accion === 'listar') {
    listar_productos();
} elseif ($metodo === 'GET' && $accion === 'obtener') {
    obtener_producto();
} elseif ($metodo === 'POST' && $accion === 'crear') {
    crear_producto();
} elseif ($metodo === 'PUT' && $accion === 'actualizar') {
    actualizar_producto();
} elseif ($metodo === 'DELETE' && $accion === 'eliminar') {
    eliminar_producto();
} else {
    http_response_code(404);
    echo respuesta(false, 'Endpoint no encontrado');
}

function listar_productos() {
    global $conn;
    $categoria = isset($_GET['categoria']) ? $_GET['categoria'] : null;
    $disponible = isset($_GET['disponible']) ? $_GET['disponible'] === 'true' : null;
    
    $sql = "SELECT * FROM productos WHERE 1=1";
    if ($categoria) {
        $sql .= " AND categoria = '" . $conn->real_escape_string($categoria) . "'";
    }
    if ($disponible !== null) {
        $sql .= " AND disponible = " . ($disponible ? 1 : 0);
    }
    $sql .= " ORDER BY fecha_creacion DESC";
    
    $resultado = $conn->query($sql);
    if (!$resultado) {
        http_response_code(500);
        echo respuesta(false, 'Error en la consulta: ' . $conn->error);
        return;
    }
    
    $productos = [];
    while ($row = $resultado->fetch_assoc()) {
        $productos[] = $row;
    }
    echo respuesta(true, 'Productos obtenidos', $productos);
}

function obtener_producto() {
    global $conn;
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo respuesta(false, 'ID de producto requerido');
        return;
    }
    
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM productos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows === 0) {
        http_response_code(404);
        echo respuesta(false, 'Producto no encontrado');
        return;
    }
    
    $producto = $resultado->fetch_assoc();
    echo respuesta(true, 'Producto obtenido', $producto);
}

function crear_producto() {
    global $conn;
    $datos = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($datos['nombre'], $datos['precio'], $datos['categoria'])) {
        http_response_code(400);
        echo respuesta(false, 'Faltan campos: nombre, precio, categoria');
        return;
    }
    
    $nombre = $datos['nombre'];
    $descripcion = isset($datos['descripcion']) ? $datos['descripcion'] : '';
    $precio = floatval($datos['precio']);
    $categoria = $datos['categoria'];
    $imagen_url = isset($datos['imagen_url']) ? $datos['imagen_url'] : '';
    $stock = isset($datos['stock']) ? intval($datos['stock']) : 0;
    
    $sql = "INSERT INTO productos (nombre, descripcion, precio, categoria, imagen_url, stock) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdssi", $nombre, $descripcion, $precio, $categoria, $imagen_url, $stock);
    
    if ($stmt->execute()) {
        $producto_id = $conn->insert_id;
        http_response_code(201);
        echo respuesta(true, 'Producto creado', ['id' => $producto_id, 'nombre' => $nombre]);
    } else {
        http_response_code(500);
        echo respuesta(false, 'Error al crear producto: ' . $conn->error);
    }
    $stmt->close();
}

function actualizar_producto() {
    global $conn;
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo respuesta(false, 'ID de producto requerido');
        return;
    }
    
    $id = intval($_GET['id']);
    $datos = json_decode(file_get_contents('php://input'), true);
    
    $campos = [];
    $tipos = "";
    $valores = [];
    
    if (isset($datos['nombre'])) {
        $campos[] = "nombre = ?";
        $tipos .= "s";
        $valores[] = $datos['nombre'];
    }
    if (isset($datos['precio'])) {
        $campos[] = "precio = ?";
        $tipos .= "d";
        $valores[] = floatval($datos['precio']);
    }
    if (isset($datos['stock'])) {
        $campos[] = "stock = ?";
        $tipos .= "i";
        $valores[] = intval($datos['stock']);
    }
    if (isset($datos['disponible'])) {
        $campos[] = "disponible = ?";
        $tipos .= "i";
        $valores[] = $datos['disponible'] ? 1 : 0;
    }
    
    if (empty($campos)) {
        http_response_code(400);
        echo respuesta(false, 'No hay campos para actualizar');
        return;
    }
    
    $sql = "UPDATE productos SET " . implode(", ", $campos) . " WHERE id = ?";
    $tipos .= "i";
    $valores[] = $id;
    
    $stmt = $conn->prepare($sql);
    call_user_func_array([$stmt, 'bind_param'], array_merge([$tipos], $valores));
    
    if ($stmt->execute()) {
        echo respuesta(true, 'Producto actualizado');
    } else {
        http_response_code(500);
        echo respuesta(false, 'Error al actualizar: ' . $conn->error);
    }
    $stmt->close();
}

function eliminar_producto() {
    global $conn;
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo respuesta(false, 'ID de producto requerido');
        return;
    }
    
    $id = intval($_GET['id']);
    $sql = "DELETE FROM productos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo respuesta(true, 'Producto eliminado');
        } else {
            http_response_code(404);
            echo respuesta(false, 'Producto no encontrado');
        }
    } else {
        http_response_code(500);
        echo respuesta(false, 'Error al eliminar: ' . $conn->error);
    }
    $stmt->close();
}

$conn->close();
?>