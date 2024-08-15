<?php
session_start();
$servidor = "localhost";
$usuario = "root";
$clave = "";
$bd = "ambicontrol";

// Establecer conexión a la base de datos
$conexion = mysqli_connect($servidor, $usuario, $clave, $bd);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Inicializar variable de error
$error = "";

// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si el campo 'email' está definido y no está vacío
    if (isset($_POST['email']) && !empty($_POST['email'])) {
        $email = $_POST['email'];

        // Verificar si el correo electrónico está registrado en la tabla usuarios
        $query = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            // El correo electrónico está registrado, generar un token de restablecimiento
            $token = bin2hex(random_bytes(50)); // Generar un token único
            $expires = date("Y-m-d H:i:s", strtotime("+1 hour")); // Token válido por 1 hora

            // Insertar el token en la tabla de restablecimientos de contraseñas
            $insert = "INSERT INTO password (email, token, expires) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conexion, $insert);
            mysqli_stmt_bind_param($stmt, "sss", $email, $token, $expires);

            if (mysqli_stmt_execute($stmt)) {
                // Enviar el correo electrónico con el enlace de restablecimiento
                $resetLink = "http://localhost/proyecto.cdae/paginas/nuevacontra.php?token=$token";
                $subject = "Restablecimiento de Contraseña";
                $message = "Para restablecer su contraseña, haga clic en el siguiente enlace: <a href=\"$resetLink\">$resetLink</a>";
                $headers = "From: no-reply@example.com\r\n";
                $headers .= "Reply-To: no-reply@example.com\r\n";
                $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

                // Configuración del SMTP (si es necesario)
                ini_set('SMTP', 'localhost'); // Ajusta esto si usas un servidor SMTP diferente
                ini_set('smtp_port', '25');    // Ajusta el puerto según tu configuración

                if (mail($email, $subject, $message, $headers)) {
                    $statusMsg = "Se ha enviado un enlace de restablecimiento de contraseña a su correo electrónico.";
                    $statusMsgType = "success";
                } else {
                    $statusMsg = "Error al enviar el correo electrónico.";
                    $statusMsgType = "danger";
                }
            } else {
                $statusMsg = "Error al guardar el token en la base de datos.";
                $statusMsgType = "danger";
            }
        } else {
            $statusMsg = "El correo electrónico no está registrado.";
            $statusMsgType = "danger";
        }
    } else {
        $statusMsg = "Por favor, ingrese su correo electrónico.";
        $statusMsgType = "warning";
    }
}

mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecimiento de Contraseña</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Página para solicitar restablecimiento de contraseña -->
    <div class="contenedor">
        <form action="cuentaUsuario.php" method="post">  
            <input type="email" name="email" placeholder="CORREO ELECTRÓNICO" required>    
            <input type="password" name="password" placeholder="CONTRASEÑA" required>    
            <div class="boton-envio"> 
                <input type="submit" name="loginSubmit" value="INICIAR SESIÓN">   
            </div>
            <a href="forgotPassword.php">¿Olvidó su contraseña?</a> 
        </form>
    </div>

    <h2>Ingrese el correo electrónico de su cuenta para restablecer la nueva contraseña</h2>

    <?php 
    if (!empty($statusMsg)) {
        echo '<p class="' . $statusMsgType . '">' . $statusMsg . '</p>';
    }
    ?>

    <div class="contenedor"> 
        <div class="regisFrm"> 
            <form action="cuentaUsuario.php" method="post">  
                <input type="email" name="email" placeholder="CORREO ELECTRÓNICO" required>    
                <div class="boton-envio"> 
                    <input type="submit" name="forgotSubmit" value="CONTINUAR">   
                </div>
            </form>
        </div>
    </div>

    <h2>Restablecer la contraseña de su cuenta</h2>

    <?php 
    if (!empty($statusMsg)) {
        echo '<p class="' . $statusMsgType . '">' . $statusMsg . '</p>';
    }
    ?>

    <div class="contenedor"> 
        <div class="regisFrm"> 
            <form action="cuentaUsuario.php" method="post">  
                <input type="password" name="password" placeholder="CONTRASEÑA" required>    
                <input type="password" name="confirm_password" placeholder="CONFIRMAR CONTRASEÑA" required>    
                <div class="boton-envio"> 
                    <input type="hidden" name="fp_code" value="<?php echo htmlspecialchars($_REQUEST['fp_code']); ?>"> 
                    <input type="submit" name="resetSubmit" value="RESTABLECER CONTRASEÑA">   
                </div>
            </form>
        </div>
    </div>
</body>
</html>
