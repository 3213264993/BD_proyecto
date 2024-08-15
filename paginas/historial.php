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

// Verificar si se envió el formulario para guardar una observación
if (isset($_POST['enviar'])) {
    // Obtener los datos del formulario
    $Numero_Documento = $_POST['Numero_Documento'] ?? '';
    $Observacion = $_POST['Observacion'] ?? '';
    $fechaHora = date("Y-m-d H:i:s"); // Formato de fecha y hora

    // Debugging: Verificar que el Número de Documento se recibe correctamente
    if (empty($Numero_Documento)) {
        echo "<script>alert('Error: El campo Número de Documento está vacío.');</script>";
    } else {
        echo "Número de Documento recibido: " . htmlspecialchars($Numero_Documento) . "<br>";
        
        // Comprobar si el Numero_Documento existe en la tabla usuarios
        $comprobarUsuario = "SELECT * FROM usuarios WHERE Numero_Documento = ?";
        $stmt = mysqli_prepare($conexion, $comprobarUsuario);
        mysqli_stmt_bind_param($stmt, "s", $Numero_Documento);
        mysqli_stmt_execute($stmt);
        $resultadoUsuario = mysqli_stmt_get_result($stmt);

        // Verificar si la consulta devolvió resultados
        if ($resultadoUsuario) {
            if (mysqli_num_rows($resultadoUsuario) > 0) {
                echo "Número de Documento encontrado en la base de datos.<br>";
                
                // Insertar los datos en la base de datos
                $insertar = "INSERT INTO observaciones (Numero_Documento, Observacion, Fecha_Hora) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($conexion, $insertar);
                mysqli_stmt_bind_param($stmt, "sss", $Numero_Documento, $Observacion, $fechaHora);
                if (mysqli_stmt_execute($stmt)) {
                    echo "<script>alert('Observación guardada correctamente.');</script>";
                } else {
                    echo "<script>alert('Error al guardar la observación: " . mysqli_error($conexion) . "');</script>";
                }
            } else {
                echo "<script>alert('El Número de Documento no existe en la base de datos de usuarios.');</script>";
            }
        } else {
            echo "<script>alert('Error en la consulta: " . mysqli_error($conexion) . "');</script>";
        }
    }
}

// Buscar observaciones por fecha
$fechaBusqueda = $_POST['fecha'] ?? '';
$query = "SELECT * FROM observaciones";
if ($fechaBusqueda) {
    $query .= " WHERE DATE(Fecha_Hora) = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "s", $fechaBusqueda);
    mysqli_stmt_execute($stmt);
    $resultadoObservaciones = mysqli_stmt_get_result($stmt);
} else {
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_execute($stmt);
    $resultadoObservaciones = mysqli_stmt_get_result($stmt);
}

// Eliminar una observación
if (isset($_POST['eliminar'])) {
    $id = $_POST['id'];
    $eliminar = "DELETE FROM observaciones WHERE id = ?";
    $stmt = mysqli_prepare($conexion, $eliminar);
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Observación eliminada correctamente.');</script>";
        header("Refresh:0"); // Refrescar la página para mostrar los cambios
    } else {
        echo "<script>alert('Error al eliminar la observación: " . mysqli_error($conexion) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Observaciones</title>
    <link rel="stylesheet" href="../CSS/mihistori.css"> 
</head>
<body>
    <div class="container">
        <img src="../img/logosena.png" alt="Logo SENA" class="logo">
        <div class="btn-volver">
            <button id="Observaciones" class="btn" onclick="location.href='observaciones.php'">Volver</button>
        </div>
        <div class="header">
            <h1>Historial de Observaciones</h1>
            <form action="" method="post">
                <div>
                    <label for="fecha">Buscar por Fecha:</label>
                    <input type="date" id="fecha" name="fecha">
                    <button type="submit">Buscar</button>
                </div>
            </form>
        </div>
        <table id="tabla-observaciones">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Número_documento</th>
                    <th>Observaciones</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                while ($fila = mysqli_fetch_assoc($resultadoObservaciones)) {
                    $fechaHora = explode(' ', $fila['Fecha_Hora']);
                    $fecha = $fechaHora[0];
                    $hora = $fechaHora[1];
                    echo "<tr>";
                    echo "<td>$fecha</td>";
                    echo "<td>$hora</td>";
                    echo "<td>" . htmlspecialchars($fila['Numero_documento']) . "</td>"; // Corregido: Nombre de columna sin tilde
                    echo "<td>" . htmlspecialchars($fila['Observacion']) . "</td>";
                    echo "<td>
                            <form method='post' style='display:inline;'>
                                <input type='hidden' name='id' value='{$fila['id']}'>
                                <button type='submit' name='eliminar' onclick='return confirm(\"¿Estás seguro de que deseas eliminar esta observación?\");'>Eliminar</button>
                            </form>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
