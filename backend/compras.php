<?php
session_start();
$pdo = new PDO('pgsql:host=localhost;dbname=tu_bd', 'usuario', 'contraseña');

// Proteger página
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Agregar producto a compra
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['producto_id'])) {
    $producto_id = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'] ?? 1;

    // Verificar si ya existe la compra
    $stmt = $pdo->prepare("SELECT id, cantidad FROM compras WHERE usuario_id = ? AND producto_id = ?");
    $stmt->execute([$usuario_id, $producto_id]);
    $compra_existente = $stmt->fetch();

    if ($compra_existente) {
        // Actualizar cantidad
        $nueva_cantidad = $compra_existente['cantidad'] + $cantidad;
        $stmt = $pdo->prepare("UPDATE compras SET cantidad = ? WHERE id = ?");
        $stmt->execute([$nueva_cantidad, $compra_existente['id']]);
        $mensaje = "Producto actualizado en tu carrito";
    } else {
        // Insertar nueva compra
        $stmt = $pdo->prepare("INSERT INTO compras (usuario_id, producto_id, cantidad) VALUES (?, ?, ?)");
        $stmt->execute([$usuario_id, $producto_id, $cantidad]);
        $mensaje = "Producto agregado al carrito";
    }
}

// Eliminar producto del carrito
if (isset($_GET['eliminar'])) {
    $compra_id = $_GET['eliminar'];
    $stmt = $pdo->prepare("DELETE FROM compras WHERE id = ? AND usuario_id = ?");
    $stmt->execute([$compra_id, $usuario_id]);
    $mensaje = "Producto eliminado del carrito";
}

// Obtener todos los productos disponibles
$stmt = $pdo->prepare("SELECT * FROM productos ORDER BY fecha_creacion DESC");
$stmt->execute();
$productos = $stmt->fetchAll();

// Obtener compras del usuario
$stmt = $pdo->prepare("
    SELECT c.id, p.nombre, p.taqueria, p.precio, c.cantidad, (p.precio * c.cantidad) as total, c.fecha_compra
    FROM compras c
    JOIN productos p ON c.producto_id = p.id
    WHERE c.usuario_id = ?
    ORDER BY c.fecha_compra DESC
");
$stmt->execute([$usuario_id]);
$compras = $stmt->fetchAll();

// Calcular total
$total_general = 0;
foreach ($compras as $compra) {
    $total_general += $compra['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mis Compras - Tacos</title>
    <style>
        body { font-family: Arial; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; }
        h1 { color: #333; }
        .header { display: flex; justify-content: space-between; align-items: center; }
        .logout { background: #f44336; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; }
        .logout:hover { background: #da190b; }
        .mensaje { padding: 10px; margin: 10px 0; border-radius: 5px; background: #d4edda; color: #155724; }
        

        .productos-section { margin: 30px 0; }
        .productos-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
        .producto-card { background: white; border: 1px solid #ddd; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .producto-card h3 { margin: 0 0 10px 0; color: #FF6B35; }
        .producto-card p { margin: 5px 0; font-size: 14px; }
        .producto-card .precio { font-weight: bold; color: #4CAF50; font-size: 18px; }
        .producto-card input { width: 60px; padding: 5px; border: 1px solid #ddd; border-radius: 3px; }
        .producto-card button { background: #FF6B35; color: white; padding: 8px 15px; border: none; cursor: pointer; border-radius: 3px; margin-top: 10px; }
        .producto-card button:hover { background: #E55A2B; }
        

        table { width: 100%; border-collapse: collapse; margin: 20px 0; background: white; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #FF6B35; color: white; }
        tr:hover { background: #f9f9f9; }
        .eliminar-btn { background: #f44336; color: white; padding: 5px 10px; border: none; cursor: pointer; border-radius: 3px; }
        .eliminar-btn:hover { background: #da190b; }
        .total-row { font-weight: bold; background: #fff3cd; }
        .vacio { text-align: center; padding: 20px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🌮 Mis Compras de Tacos</h1>
            <a href="logout.php" class="logout">Cerrar Sesión</a>
        </div>
        
        <p>Bienvenido, <strong><?php echo $_SESSION['usuario']; ?></strong></p>
        
        <?php if (isset($mensaje)) echo "<div class='mensaje'>$mensaje</div>"; ?>
        

        <div class="productos-section">
            <h2>🌮 Tacos Disponibles</h2>
            <div class="productos-grid">
                <?php foreach ($productos as $producto): ?>
                    <div class="producto-card">
                        <h3><?php echo $producto['nombre']; ?></h3>
                        <p><strong>Taquería:</strong> <?php echo $producto['taqueria']; ?></p>
                        <p><strong>País:</strong> <?php echo $producto['pais_origen']; ?></p>
                        <p><strong>Carne:</strong> <?php echo $producto['variedad_carne']; ?></p>
                        <p><strong>Tortilla:</strong> <?php echo $producto['tipo_tortilla']; ?></p>
                        <p><strong>Picante:</strong> <?php echo str_repeat('🌶️', $producto['nivel_picante']); ?></p>
                        <p><strong>Categoría:</strong> <?php echo $producto['categoria']; ?></p>
                        <p><strong>Porción:</strong> <?php echo $producto['porcion']; ?></p>
                        <p class="precio">$<?php echo number_format($producto['precio'], 2); ?></p>
                        
                        <form method="POST" style="display: flex; gap: 10px;">
                            <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                            <input type="number" name="cantidad" value="1" min="1" max="100">
                            <button type="submit">Agregar</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        

        <h2> Tu Carrito</h2>
        
        <?php if (count($compras) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Taco</th>
                        <th>Taquería</th>
                        <th>Precio Unitario</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                        <th>Fecha</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($compras as $compra): ?>
                        <tr>
                            <td><?php echo $compra['nombre']; ?></td>
                            <td><?php echo $compra['taqueria']; ?></td>
                            <td>$<?php echo number_format($compra['precio'], 2); ?></td>
                            <td><?php echo $compra['cantidad']; ?></td>
                            <td>$<?php echo number_format($compra['total'], 2); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($compra['fecha_compra'])); ?></td>
                            <td>
                                <a href="?eliminar=<?php echo $compra['id']; ?>" class="eliminar-btn" onclick="return confirm('¿Eliminar?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="total-row">
                        <td colspan="4">TOTAL A PAGAR:</td>
                        <td>$<?php echo number_format($total_general, 2); ?></td>
                        <td colspan="2"></td>
                    </tr>
                </tbody>
            </table>
        <?php else: ?>
            <div class="vacio">
                <p>Tu carrito está vacío. ¡Agrega algunos tacos deliciosos!</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
