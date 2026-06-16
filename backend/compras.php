<?php
require_once 'config.php';

$metodo = $_SERVER['REQUEST_METHOD'];
$accion = isset($_GET['accion']) ? $_GET['accion'] : 'listar';

if ($metodo === 'GET' && $accion === 'listar') {
    listar_compras();
} elseif ($metodo === 'GET' && $accion === 'obtener') {
    obtener_compra();
} elseif ($metodo === 'POST' && $accion === 'crear') {
    crear_compra();
} elseif ($metodo === 'PUT' && $accion === 'actualizar_estado') {
    actualizar_estado_compra();
} elseif ($metodo === 'DELETE' && $accion === 'cancelar') {
    cancelar_compra();
} else {
    http_response_code(404);
    echo respuesta(false, 'Endpoint no encontrado');
}

function listar_compras() {
    global $conn;
    if (!isset($_GET['usuario_id'])) {
        http_response_code(400);
        echo respuesta(false, 'usuario_id requerido');
        return;
    }
    
    $usuario_id = intval($_GET['usuario_id']);
    $estado = isset($_GET['estado']) ? $_GET['estado'] : null;
    
    $sql = "SELECT c.*, u.nombre as usuario_nombre, COUNT(dc.id) as cantidad_items FROM compras c JOIN usuarios u ON c.usuario_id = u.id LEFT JOIN detalles_compra dc ON c.id = dc.compra_id WHERE c.usuario_id = ?";
    
    if ($estado) {
        $sql .= " AND c.estado = '" . $conn->real_escape_string($estado) . "'";
    }
    
    $sql .= " GROUP BY c.id ORDER BY c.fecha_compra DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    $compras = [];
    while ($row = $resultado->fetch_assoc()) {
        $compras[] = $row;
    }
    
    echo respuesta(true, 'Compras obtenidas', $compras);
    $stmt->close();
}

function obtener_compra() {
    global $conn;
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo respuesta(false, 'ID de compra requerido');
        return;
    }
    
    $compra_id = intval($_GET['id']);
    
    $sql = "SELECT * FROM compras WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $compra_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows === 0) {
        http_response_code(404);
        echo respuesta(false, 'Compra no encontrada');
        return;
    }
    
    $compra = $resultado->fetch_assoc();
    
    $sql = "SELECT dc.*, p.nombre as producto_nombre, p.imagen_url FROM detalles_compra dc JOIN productos p ON dc.producto_id = p.id WHERE dc.compra_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $compra_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    $detalles = [];
    while ($row = $resultado->fetch_assoc()) {
        $detalles[] = $row;
    }
    
    $compra['detalles'] = $detalles;
    echo respuesta(true, 'Compra obtenida', $compra);
    $stmt->close();
}

function crear_compra() {
    global $conn;
    $datos = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($datos['usuario_id'], $datos['items'])) {
        http_response_code(400);
        echo respuesta(false, 'usuario_id e items requeridos');
        return;
    }
    
    $usuario_id = intval($datos['usuario_id']);
    $items = $datos['items'];
    
    if (empty($items)) {
        http_response_code(400);
        echo respuesta(false, 'La compra debe tener al menos un item');
        return;
    }
    
    $conn->begin_transaction();
    
    try {
        $total = 0;
        
        foreach ($items as $item) {
            $producto_id = intval($item['producto_id']);
            $cantidad = intval($item['cantidad']);
            
            $sql = "SELECT precio, stock FROM productos WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $producto_id);
            $stmt->execute();
            $resultado = $stmt->get_result();
            
            if ($resultado->num_rows === 0) {
                throw new Exception("Producto $producto_id no encontrado");
            }
            
            $producto = $resultado->fetch_assoc();
            
            if ($producto['stock'] < $cantidad) {
                throw new Exception("Stock insuficiente para producto $producto_id");
            }
            
            $total += $producto['precio'] * $cantidad;
        }
        
        $sql = "INSERT INTO compras (usuario_id, total) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("id", $usuario_id, $total);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al crear compra: " . $conn->error);
        }
        
        $compra_id = $conn->insert_id;
        
        foreach ($items as $item) {
            $producto_id = intval($item['producto_id']);
            $cantidad = intval($item['cantidad']);
            
            $sql = "SELECT precio FROM productos WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $producto_id);
            $stmt->execute();
            $producto = $stmt->get_result()->fetch_assoc();
            $precio_unitario = $producto['precio'];
            $subtotal = $precio_unitario * $cantidad;
            
            $sql = "INSERT INTO detalles_compra (compra_id, producto_id, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiidd", $compra_id, $producto_id, $cantidad, $precio_unitario, $subtotal);
            $stmt->execute();
            
            $sql = "UPDATE productos SET stock = stock - ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $cantidad, $producto_id);
            $stmt->execute();
        }
        
        $conn->commit();
        
        http_response_code(201);
        echo respuesta(true, 'Compra creada exitosamente', ['compra_id' => $compra_id, 'total' => $total]);
        
    } catch (Exception $e) {
        $conn->rollback();
        http_response_code(400);
        echo respuesta(false, $e->getMessage());
    }
}

function actualizar_estado_compra() {
    global $conn;
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo respuesta(false, 'ID de compra requerido');
        return;
    }
    
    $compra_id = intval($_GET['id']);
    $datos = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($datos['estado'])) {
        http_response_code(400);
        echo respuesta(false, 'Estado requerido');
        return;
    }
    
    $estado = $datos['estado'];
    $estados_validos = ['pendiente', 'confirmada', 'en_preparacion', 'lista', 'entregada', 'cancelada'];
    
    if (!in_array($estado, $estados_validos)) {
        http_response_code(400);
        echo respuesta(false, 'Estado inválido');
        return;
    }
    
    $sql = "UPDATE compras SET estado = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $estado, $compra_id);
    
    if ($stmt->execute()) {
        echo respuesta(true, 'Estado actualizado', ['compra_id' => $compra_id, 'estado' => $estado]);
    } else {
        http_response_code(500);
        echo respuesta(false, 'Error al actualizar: ' . $conn->error);
    }
    $stmt->close();
}

function cancelar_compra() {
    global $conn;
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo respuesta(false, 'ID de compra requerido');
        return;
    }
    
    $compra_id = intval($_GET['id']);
    $conn->begin_transaction();
    
    try {
        $sql = "SELECT producto_id, cantidad FROM detalles_compra WHERE compra_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $compra_id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        while ($detalle = $resultado->fetch_assoc()) {
            $sql = "UPDATE productos SET stock = stock + ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $detalle['cantidad'], $detalle['producto_id']);
            $stmt->execute();
        }
        
        $estado = 'cancelada';
        $sql = "UPDATE compras SET estado = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $estado, $compra_id);
        $stmt->execute();
        
        $conn->commit();
        echo respuesta(true, 'Compra cancelada y stock devuelto');
        
    } catch (Exception $e) {
        $conn->rollback();
        http_response_code(500);
        echo respuesta(false, 'Error al cancelar: ' . $e->getMessage());
    }
}

$conn->close();
?>