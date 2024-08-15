<?php
$servidor = "localhost";
$usuario = "root";
$clave = "";
$bd = "ambicontrol";

$coneccion = mysqli_connect($servidor, $usuario, $clave, $bd);

if (!$coneccion) {
    die("Error de conexión: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $nueva_contrasena = $_POST['nueva_contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];

    // Verificar que las contraseñas coincidan
    if ($nueva_contrasena !== $confirmar_contrasena) {
        echo "<div class='alert alert-danger' role='alert'>Las contraseñas no coinciden.</div>";
        exit;
    }

    // Verificar si el token es válido y no ha expirado
    $consulta = "SELECT * FROM password WHERE Token = ? AND Expiracion >= NOW()";
    $stmt = mysqli_prepare($coneccion, $consulta);
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($resultado) > 0) {
        // Obtener el correo electrónico del token
        $fila = mysqli_fetch_assoc($resultado);
        $email = $fila['Email'];

        // Actualizar la contraseña del usuario (asegurarse de que esté encriptada)
        $nueva_contrasena_hash = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
        $actualizarContrasena = "UPDATE usuarios SET password = ? WHERE Email = ?";
        $stmt = mysqli_prepare($coneccion, $actualizarContrasena);
        mysqli_stmt_bind_param($stmt, "ss", $nueva_contrasena_hash, $email);
        mysqli_stmt_execute($stmt);

        // Eliminar el token usado
        $eliminarToken = "DELETE FROM password WHERE Token = ?";
        $stmt = mysqli_prepare($coneccion, $eliminarToken);
        mysqli_stmt_bind_param($stmt, "s", $token);
        mysqli_stmt_execute($stmt);

        echo "<div class='alert alert-success' role='alert'>La contraseña ha sido restablecida con éxito.</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>El token de restablecimiento es inválido o ha expirado.</div>";
    }

    mysqli_close($coneccion);
}
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
        <form action="" method="POST">
            <div class="form-group">
                <label for="nueva_contrasena">Nueva Contraseña:</label>
                <input type="password" class="form-control" id="nueva_contrasena" name="nueva_contrasena" required>
            </div>
            <div class="form-group">
                <label for="confirmar_contrasena">Confirmar Contraseña:</label>
                <input type="password" class="form-control" id="confirmar_contrasena" name="confirmar_contrasena" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Actualizar Contraseña</button>
        </form>
    </div>
</body>
</html>

