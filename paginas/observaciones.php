<?php
// Conexión a la base de datos
$servidor = "localhost";
$usuario = "root";
$clave = "";
$bd = "ambicontrol";
$conexion = mysqli_connect($servidor, $usuario, $clave, $bd);

if (!$conexion) {
    die("Conexión fallida: " . mysqli_connect_error());
}

$mensaje = ""; // Asegúrate de que la variable esté inicializada

// Verificar si se envió el formulario de observaciones
if (isset($_POST['enviar'])) {
    // Obtener los datos del formulario
    $Numero_Documento = $_POST['Numero_Documento'];
    $Observacion = $_POST['Observacion'];
    $fechaHora = date("Y-m-d H:i:s"); // Formato de fecha y hora

    // Comprobar si el Numero_Documento existe en la tabla usuarios
    $comprobarUsuario = "SELECT * FROM usuarios WHERE Numero_Documento = '$Numero_Documento'";
    $resultadoUsuario = mysqli_query($conexion, $comprobarUsuario);

    if (mysqli_num_rows($resultadoUsuario) > 0) {
        // Comprobar si ya existe una observación con el mismo Numero_Documento y fechaHora
        $verificarObservacion = "SELECT * FROM observaciones WHERE Numero_Documento = '$Numero_Documento' AND Fecha_Hora = '$fechaHora'";
        $resultadoObservacion = mysqli_query($conexion, $verificarObservacion);

        if (mysqli_num_rows($resultadoObservacion) > 0) {
            $mensaje = "Ya se ha registrado una observación para este documento en el mismo momento.";
        } else {
            // Insertar los datos en la base de datos
            $insertar = "INSERT INTO observaciones (Numero_Documento, Observacion, Fecha_Hora) 
                         VALUES ('$Numero_Documento', '$Observacion', '$fechaHora')";
            if (mysqli_query($conexion, $insertar)) {
                $mensaje = "Observación guardada correctamente.";
            } else {
                $mensaje = "Error: " . mysqli_error($conexion);
            }
        }
    } else {
        $mensaje = "El Número de Documento no existe en la base de datos de usuarios.";
    }
}

// Cerrar la conexión a la base de datos
mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Observaciones del Ambiente</title>
    <link rel="stylesheet" href="../CSS/miobser.css">
    <style>
        body {
            background-image: url("../img/SENA4.jpg");
        }
        .alert {
            margin-top: 10px;
            padding: 15px;
            background-color: #f4f4f4;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="../img/logosena.png" alt="Logo SENA" class="logo">
        <div class="header">
            <a href="http://localhost/proyecto.cdae/paginas/ambientes.php" class="btn-nav btn-black">Volver</a>
        </div>
        <h1>Observaciones del Ambiente</h1>

        <!-- Mostrar mensaje -->
        <?php if ($mensaje): ?>
            <div class="alert">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <form action="" method="post">
            <div class="form-group">
                <label for="Numero_Documento">Numero_Documento:</label>
                <input type="text" id="Numero_Documento" name="Numero_Documento" required>
            </div>
            <div class="form-group">
                <label for="Observacion">Observación:</label>
                <textarea id="Observacion" name="Observacion" rows="6" required></textarea>
            </div>
            <button type="submit" class="btn-submit" name="enviar">Enviar</button>
        </form>
        <div class="form-footer">
            <button id="historial-btn" class="btn-nav btn-green" onclick="location.href='historial.php'">Historial</button>
        </div>
    </div>
</body>
</html>
