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

$mensaje = ""; // Variable para almacenar el mensaje

// Procesar el formulario si se ha enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Numero_documento = $_POST['Numero_documento'];
    $tipo_asignacion = $_POST['tipo'];
    $Numero_ambiente = $_POST['Numero_ambiente'];
    $fechaHora = date("Y-m-d H:i:s");

    // Insertar los datos en la base de datos
    $insertar = "INSERT INTO asignaciones (Numero_documento, Tipo_asignacion, Numero_ambiente, Fecha_Hora) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $insertar);
    mysqli_stmt_bind_param($stmt, "ssss", $Numero_documento, $tipo_asignacion, $Numero_ambiente, $fechaHora);

    if (mysqli_stmt_execute($stmt)) {
        $mensaje = "Asignación guardada correctamente.";
    } else {
        $mensaje = "Error: " . mysqli_error($conexion);
    }

    // Cerrar la declaración
    mysqli_stmt_close($stmt);
}

// Cerrar la conexión
mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Asignación del Ambiente</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/miambi.css">
</head>
<body>
    <div class="container mt-3">
        <div class="text-center mb-3">
            <img src="../img/logosena.png" alt="Logo SENA" class="logo">
        </div>
        <div class="d-flex justify-content-end mb-4">
            <!-- Cambiar el enlace y el texto del botón -->
            <a href="http://localhost/proyecto.cdae/paginas/pagina2.html" class="btn btn-home">Volver</a>
            <a href="http://localhost/proyecto.cdae/paginas/inventario.php" class="btn btn-inventory">Inventario</a>
        </div>
        <h1 class="text-center mb-4">Registro de Asignación del Ambiente</h1>

        <!-- Aquí se mostrará el mensaje -->
        <?php if ($mensaje): ?>
            <div class="alert alert-info text-center">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <form action="" method="post" name="assignment-form">
            <div class="form-group">
                <label for="Numero_documento">Numero_documento:</label>
                <input type="text" id="Numero_documento" name="Numero_documento" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="Numero_ambiente">Numero_ambiente:</label>
                <select id="Numero_ambiente" name="Numero_ambiente" class="form-control" required>
                    <option value="" disabled selected>Seleccione...</option>
                    <option value="ambiente1">Ambiente 101</option>
                    <option value="ambiente2">Ambiente 102</option>
                    <option value="ambiente3">Ambiente 103</option>
                    <option value="ambiente4">Ambiente 104</option>
                    <option value="ambiente5">Ambiente 105</option>
                    <option value="ambiente6">Ambiente 106</option>
                    <option value="ambiente7">Ambiente 107</option>
                    <option value="ambiente8">Ambiente 108</option>
                    <option value="ambiente9">Ambiente 109</option>
                    <option value="ambiente10">Ambiente 110</option>
                    <option value="ambiente11">Ambiente 111</option>
                    <option value="ambiente12">Ambiente 112</option>
                    <option value="ambiente13">Ambiente poli_1</option>
                    <option value="ambiente14">Ambiente poli_2</option>
                    <option value="ambiente15">Ambiente poli_3</option>
                    <option value="ambiente16">Ambiente Laboratorio De Suelos</option>
                    <option value="ambiente17">Ambiente Alturas</option>
                    <option value="ambiente18">Ambiente Deportes</option>
                    <option value="ambiente19">Ambiente Biblioteca</option>
                    <option value="ambiente20">Ambiente Laboratorio De Alcohol</option>
                    <option value="ambiente21">Sala De Conferencia</option>
                    <option value="ambiente22">Ambiente De Musica</option>
                    <option value="ambiente23">Ambiente Casa Hotel</option>
                    <option value="ambiente24">Ambiente De Pasteleria</option>
                    <option value="ambiente25">Ambiente De Soldadura</option>
                    <option value="ambiente26">Cuarto De Mantenimiento</option>
                    <option value="ambiente27">Auditorio</option>
                </select>
            </div>
            <div class="form-group">
                <label for="tipo">Tipo_Asignación:</label>
                <select id="tipo" name="tipo" class="form-control" required>
                    <option value="" disabled selected>Seleccione...</option>
                    <option value="Entrada">Entrada</option>
                    <option value="Salida">Salida</option>
                </select>
            </div>
            <div class="form-footer mb-3">
                <input type="submit" value="Guardar" class="btn btn-success btn-submit">
                <a href="http://localhost/proyecto.cdae/paginas/observaciones.php" class="btn btn-custom-warning btn-submit">Observaciones</a>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrap.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
