<?php
// Configuración de la zona horaria
date_default_timezone_set('America/Bogota');

// Conexión a la base de datos
$servidor = "localhost";
$usuario = "root";
$clave = "";
$bd = "ambicontrol";
$conexion = mysqli_connect($servidor, $usuario, $clave, $bd);

if (!$conexion) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Inicializar variables
$mensaje = '';
$elementos = [];

// Verificar si se ha enviado el formulario de búsqueda
if ($_SERVER['REQUEST_METHOD'] == 'GET' && (isset($_GET['fecha']) || isset($_GET['numero_documento']))) {
    // Recoger los parámetros de búsqueda y construir la consulta
    $fecha = $_GET['fecha'] ?? '';
    $numero_documento = $_GET['numero_documento'] ?? '';
    
    $query = "SELECT DATE_FORMAT(fecha, '%d-%m-%Y') AS fecha_formateada, 
                     Numero_Documento, Elemento, Disponibilidad, Cantidad, descripcion 
              FROM inventario WHERE 1=1";
    $params = [];
    $types = '';
    
    if (!empty($fecha)) {
        $query .= " AND fecha = ?";
        $params[] = $fecha;
        $types .= 's';
    }
    if (!empty($numero_documento)) {
        $query .= " AND Numero_Documento = ?";
        $params[] = $numero_documento;
        $types .= 's';
    }
    
    $query .= " ORDER BY fecha ASC";
    
    $stmt = mysqli_prepare($conexion, $query);
    
    if ($stmt) {
        if (!empty($params)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
        
        mysqli_stmt_execute($stmt);
        $resultadoInventario = mysqli_stmt_get_result($stmt);
        
        // Verificar si la consulta devolvió resultados
        if ($resultadoInventario) {
            $elementos = mysqli_fetch_all($resultadoInventario, MYSQLI_ASSOC);
        } else {
            $mensaje = "Error al recuperar los datos del inventario: " . mysqli_error($conexion);
        }
    } else {
        $mensaje = "Error en la preparación de la consulta: " . mysqli_error($conexion);
    }
} else {
    // Consulta por defecto si no se ha realizado ninguna búsqueda
    $query = "SELECT DATE_FORMAT(fecha, '%d-%m-%Y') AS fecha_formateada, 
                     Numero_Documento, Elemento, Disponibilidad, Cantidad, descripcion 
              FROM inventario ORDER BY fecha ASC";
    $resultadoInventario = mysqli_query($conexion, $query);
    
    if ($resultadoInventario) {
        $elementos = mysqli_fetch_all($resultadoInventario, MYSQLI_ASSOC);
    } else {
        $mensaje = "Error al ejecutar la consulta por defecto: " . mysqli_error($conexion);
    }
}

// Cerrar la conexión
mysqli_close($conexion);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Ambiente</title>
    <link rel="stylesheet" href="../CSS/historialinver.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>
    <style>
        body {
            background-image: url(../img/SENA4.jpg);
        }
        .form-table td, .form-table th {
            padding: 8px;
            text-align: left;
        }
        .button-back {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="inventario.php" class="button-back">Volver</a>
        <img src="../img/logosena.png" alt="Logo SENA" class="logo">
        <h1 class="title">Historial De Ambiente</h1>

        <!-- Formulario de búsqueda -->
        <form method="GET" action="">
            <label for="fecha">Buscar por Fecha:</label>
            <input type="date" id="fecha" name="fecha">
            
            <label for="numero_documento">Buscar por Número de Documento:</label>
            <input type="text" id="numero_documento" name="numero_documento" placeholder="Numero_Documento">
            
            <button type="submit">Buscar</button>
        </form>

        <!-- Mostrar mensajes aquí -->
        <div class="message">
            <?php if (isset($mensaje)) echo htmlspecialchars($mensaje); ?>
        </div>

        <!-- Tabla de resultados -->
        <table class="form-table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Numero_Documento</th>
                    <th>Elemento</th>
                    <th>Disponibilidad</th>
                    <th>Cantidad</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody id="table-body">
            <?php 
            if (!empty($elementos)) {
                foreach ($elementos as $elemento): 
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($elemento['fecha_formateada']); ?></td>
                    <td><?php echo htmlspecialchars($elemento['Numero_Documento']); ?></td>
                    <td><?php echo htmlspecialchars($elemento['Elemento']); ?></td>
                    <td><?php echo htmlspecialchars($elemento['Disponibilidad']); ?></td>
                    <td><?php echo htmlspecialchars($elemento['Cantidad']); ?></td>
                    <td><?php echo htmlspecialchars($elemento['descripcion']); ?></td>
                </tr>
            <?php endforeach; } ?>
            </tbody>
        </table>

        <div class="button-container">
            <button id="printButton" type="button" onclick="window.print();">Imprimir</button>
            <button id="downloadButton" type="button" onclick="downloadData();">Descargar</button>
        </div>
    </div>

    <script>
    // Función para descargar el PDF
    function downloadData() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        doc.setFontSize(18);
        doc.text("Historial De Ambiente", 20, 20);

        doc.setFontSize(12);
        const rows = [];
        const headers = ["Fecha", "Número de Documento", "Elemento", "Disponibilidad", "Cantidad", "Descripción"];

        document.querySelectorAll(".form-table tbody tr").forEach((row) => {
            const cols = Array.from(row.querySelectorAll("td")).map((cell) => cell.innerText);
            rows.push(cols);
        });

        doc.autoTable({
            head: [headers],
            body: rows,
            startY: 30,
            theme: 'grid'
        });

        doc.save("historial_ambiente.pdf");
    }
    </script>
</body>
</html>
