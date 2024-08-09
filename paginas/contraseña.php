<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newPassword = $_POST['password'];
    $token = $_POST['token'];

    // Verificar el token en la base de datos
    // Si es válido, actualizar la contraseña
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    // Actualizar la contraseña en la base de datos y eliminar el token
    // (aquí debes hacer la conexión a tu base de datos y actualizarla)

    echo "Tu contraseña ha sido restablecida correctamente.";
    header('Location: login.html');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Contraseña</title>
    <link rel="stylesheet" href="../CSS/micontraseña.css">
</head>
<body>
    <div class="reset-container">
        <h2>Actualizar Contraseña</h2>
        <form action="reset_password.php" method="POST">
            <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>" required>
            <label for="password">Nueva Contraseña:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Actualizar Contraseña</button>
        </form>
        <p>Contraseña actualizada correctamente. Redirigiendo al inicio de sesión...</p>
    </div>
</body>
</html>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['Email'];

    // Generar un token único
    $token = bin2hex(random_bytes(32));
    
    // Guardar el token en la base de datos con una marca de tiempo
    // (aquí debes hacer la conexión a tu base de datos y guardarlo)

    // Crear el enlace de restablecimiento
    $resetLink = "http://tu-dominio.com/reset_password.php?token=" . $token;

    // Enviar el correo electrónico
    $subject = "Restablecer tu contraseña";
    $message = "Haz clic en el siguiente enlace para restablecer tu contraseña: " . $resetLink;
    $headers = "From: no-reply@tu-dominio.com";

    if (mail($email, $subject, $message, $headers)) {
        echo "El enlace de restablecimiento ha sido enviado a tu correo electrónico.";
    } else {
        echo "Hubo un error al enviar el correo electrónico.";
    }
}
?>
