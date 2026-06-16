<?php
require 'config.php';

$endpoints = [
    'login' => 'POST /login.php - Iniciar sesión',
    'registro' => 'POST /registro.php - Registrarse',
    'productos' => 'GET /productos.php - Obtener todos los tacos',
    'compras' => 'GET /compras.php?usuario_id=1 - Obtener compras del usuario',
    'agregar_compra' => 'POST /agregar_compra.php - Agregar taco al carrito',
    'eliminar_compra' => 'POST /eliminar_compra.php - Eliminar taco del carrito'
];

echo respuesta(true, 'API de Tacos', $endpoints);
?>
