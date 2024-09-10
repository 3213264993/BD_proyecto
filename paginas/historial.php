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
        exit;
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
                    <th>Fecha y Hora</th>
                    <th>Número de Documento</th>
                    <th>Observaciones</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($resultadoObservaciones && mysqli_num_rows($resultadoObservaciones) > 0) {
                    while ($fila = mysqli_fetch_assoc($resultadoObservaciones)) {
                        // Concatenar fecha y hora en una sola columna, usando formato militar (24 horas)
                        $fechaHora = date("d-m-Y H:i", strtotime($fila['Fecha_Hora']));
                        echo "<tr>";
                        echo "<td>$fechaHora</td>"; // Fecha y hora en una sola columna en formato militar
                        echo "<td>" . htmlspecialchars($fila['Numero_documento']) . "</td>";
                        echo "<td>" . htmlspecialchars($fila['Observacion']) . "</td>";
                        echo "<td>
                                <form method='post' style='display:inline;'>
                                    <input type='hidden' name='id' value='{$fila['id']}'>
                                    <button type='submit' name='eliminar' onclick='return confirm(\"¿Estás seguro de que deseas eliminar esta observación?\");'>Eliminar</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No se encontraron observaciones.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
