<?php
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
    // Verificar si el campo 'Email' está definido y no está vacío
    if (isset($_POST['Email']) && !empty($_POST['Email'])) {
        $Email = $_POST['Email'];

        // Verificar si el correo electrónico está registrado en la tabla usuarios
        $query = "SELECT * FROM usuarios WHERE Email = ?";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "s", $Email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            // El correo electrónico está registrado, generar un token de restablecimiento
            $token = bin2hex(random_bytes(50)); // Generar un token único
            $expires = date("Y-m-d H:i:s", strtotime("+1 hour")); // Token válido por 1 hora

            // Insertar el token en la tabla de restablecimientos de contraseñas
            $insert = "INSERT INTO password (Email, token, expires) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conexion, $insert);
            mysqli_stmt_bind_param($stmt, "sss", $Email, $token, $expires);

            if (mysqli_stmt_execute($stmt)) {
                // Enviar el correo electrónico con el enlace de restablecimiento
                $resetLink = "http://localhost/proyecto.cdae/paginas/nuevacontra.php?token=$token";
                $subject = "Restablecimiento de Contraseña";
                $message = "Para restablecer su contraseña, haga clic en el siguiente enlace: $resetLink";
                $headers = "From: no-reply@example.com";

                if (mail($email, $subject, $message, $headers)) {
                    echo "<div class='alert alert-success' role='alert'>Se ha enviado un enlace de restablecimiento de contraseña a su correo electrónico.</div>";
                } else {
                    echo "<div class='alert alert-danger' role='alert'>Error al enviar el correo electrónico.</div>";
                }
            } else {
                echo "<div class='alert alert-danger' role='alert'>Error al guardar el token en la base de datos.</div>";
            }
        } else {
            echo "<div class='alert alert-danger' role='alert'>El correo electrónico no está registrado.</div>";
        }
    }
}

mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Restablecer Contraseña</h2>

        <!-- Mostrar mensaje de error si existe -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="contraseña.php" method="POST">
            <div class="form-group">
                <label for="email">Correo Electrónico:</label>
                <input type="email" class="form-control" id="Email" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Enviar enlace de restablecimiento</button>
        </form>
    </div>
</body>
</html>
