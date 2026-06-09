<?php
session_start();
$pdo = new PDO('pgsql:host=localhost;dbname=tu_bd', 'usuario', 'contraseña');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $celular = $_POST['celular'];
    $contraseña = $_POST['contraseña'];
    $confirmar = $_POST['confirmar'];


    if (strlen($usuario) < 3) {
        $error = "El usuario debe tener al menos 3 caracteres";
    } elseif (strlen($contraseña) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres";
    } elseif ($contraseña !== $confirmar) {
        $error = "Las contraseñas no coinciden";
    } else {

        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = ? OR celular = ?");
        $stmt->execute([$usuario, $celular]);
        
        if ($stmt->fetch()) {
            $error = "El usuario o celular ya está registrado";
        } else {

            $contraseña_hash = password_hash($contraseña, PASSWORD_BCRYPT);
            

            $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, celular, contraseña) VALUES (?, ?, ?)");
            
            if ($stmt->execute([$usuario, $celular, $contraseña_hash])) {
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
    <title>Registro</title>
    <style>
        body { font-family: Arial; text-align: center; margin-top: 50px; }
        form { width: 300px; margin: 0 auto; }
        input { width: 100%; padding: 10px; margin: 10px 0; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: green; color: white; border: none; cursor: pointer; }
        .error { color: red; }
        .exito { color: green; }
    </style>
</head>
<body>
    <h2>Crear Cuenta</h2>
    
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    <?php if (isset($exito)) echo "<p class='exito'>$exito</p>"; ?>
    
    <form method="POST">
        <input type="text" name="usuario" placeholder="Usuario" required>
        <input type="text" name="celular" placeholder="Celular" required>
        <input type="password" name="contraseña" placeholder="Contraseña" required>
        <input type="password" name="confirmar" placeholder="Confirmar contraseña" required>
        <button type="submit">Registrarse</button>
    </form>
    
    <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
</body>
</html>
