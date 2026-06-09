<?php
session_start();
$pdo = new PDO('pgsql:host=localhost;dbname=tu_bd', 'usuario', 'contraseña');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $celular = $_POST['celular'];
    $clave = $_POST['clave'];
    $confirmar = $_POST['confirmar'];


    if (strlen($usuario) < 3) {
        $error = "El usuario debe tener al menos 3 caracteres";
    } elseif (strlen($clave) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres";
    } elseif ($clave !== $confirmar) {
        $error = "Las contraseñas no coinciden";
    } else {

        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = ? OR celular = ?");
        $stmt->execute([$usuario, $celular]);
        
        if ($stmt->fetch()) {
            $error = "El usuario o celular ya está registrado";
        } else {

            $clave_hash = password_hash($clave, PASSWORD_BCRYPT);
            

            $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, celular, clave) VALUES (?, ?, ?)");
            
            if ($stmt->execute([$usuario, $celular, $clave_hash])) {
                $exito = "¡Registro exitoso! Ahora puedes <a href='login.php'>iniciar sesión</a>";
            } else {
                $error = "Error al registrar. Intenta de nuevo";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro - Tacos</title>
    <style>
        body { font-family: Arial; text-align: center; margin-top: 50px; background: #f5f5f5; }
        .container { width: 300px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { color: #333; }
        input { width: 100%; padding: 10px; margin: 10px 0; box-sizing: border-box; border: 1px solid #ddd; border-radius: 5px; }
        button { width: 100%; padding: 10px; background: #4CAF50; color: white; border: none; cursor: pointer; border-radius: 5px; font-weight: bold; }
        button:hover { background: #45a049; }
        .error { color: red; margin: 10px 0; }
        .exito { color: green; margin: 10px 0; }
        a { color: #4CAF50; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>🌮 Crear Cuenta</h2>
        
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <?php if (isset($exito)) echo "<p class='exito'>$exito</p>"; ?>
        
        <form method="POST">
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="text" name="celular" placeholder="Celular" required>
            <input type="password" name="clave" placeholder="Contraseña" required>
            <input type="password" name="confirmar" placeholder="Confirmar contraseña" required>
            <button type="submit">Registrarse</button>
        </form>
        
        <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
    </div>
</body>
</html>
