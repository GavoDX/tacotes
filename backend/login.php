<?php
session_start();
$pdo = new PDO('pgsql:host=localhost;dbname=tu_bd', 'usuario', 'contraseña');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $clave = $_POST['clave'];
    
    $stmt = $pdo->prepare("SELECT id, clave FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $resultado = $stmt->fetch();

    if ($resultado && password_verify($clave, $resultado['clave'])) {
        $_SESSION['usuario_id'] = $resultado['id'];
        $_SESSION['usuario'] = $usuario;
        header('Location: compras.php');
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Tacos</title>
    <style>
        body { font-family: Arial; text-align: center; margin-top: 50px; background: #f5f5f5; }
        .container { width: 300px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { color: #333; }
        input { width: 100%; padding: 10px; margin: 10px 0; box-sizing: border-box; border: 1px solid #ddd; border-radius: 5px; }
        button { width: 100%; padding: 10px; background: #FF6B35; color: white; border: none; cursor: pointer; border-radius: 5px; font-weight: bold; }
        button:hover { background: #E55A2B; }
        .error { color: red; margin: 10px 0; }
        a { color: #FF6B35; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2> Iniciar Sesión</h2>
        
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        
        <form method="POST">
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="password" name="clave" placeholder="Contraseña" required>
            <button type="submit">Entrar</button>
        </form>
        
        <p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
    </div>
</body>
</html>
