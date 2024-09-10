<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "ambicontrol");

// Verificar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Inicializar el mensaje
$mensaje = "";

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener la fecha y hora actual
    $fecha = date('Y-m-d');
    $hora = date('H:i:s');

    // Capturar el número de documento
    $Numero_Documento = $conexion->real_escape_string($_POST['documento'] ?? '');

    // Validar que el campo Número de Documento no esté vacío
    if (!empty($Numero_Documento)) {
        $datosGuardados = false; // Variable para verificar si al menos un dato ha sido guardado

        // Guardar cada elemento en la base de datos
        $elementos = [
            'monitores' => 'Monitores',
            'torres' => 'Torres',
            'teclados' => 'Teclados',
            'mouses' => 'Mouses',
            'sillas' => 'Sillas',
            'mesas' => 'Mesas',
            'tablero' => 'Tablero',
            'aireAcondicionado' => 'Aire Acondicionado',
            'televisor' => 'Televisor',
            'proyector' => 'Proyector',
            'modeloDeInternet' => 'Modelo De Internet',
            'cortinas' => 'Cortinas',
            'conectoresElectricos' => 'Conectores Eléctricos',
            'extension' => 'Extensión',
            'marcadores' => 'Marcadores',
            'borradorDeTablero' => 'Borrador De Tablero',
            'muebles' => 'Muebles'
        ];

        // Preparar la consulta
        $stmt = $conexion->prepare("INSERT INTO inventario (Numero_Documento, elemento, disponibilidad, cantidad, descripcion, fecha, hora) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?)");

        $registroHecho = false; // Variable para verificar si se ha hecho al menos un registro

        foreach ($elementos as $key => $elemento) {
            $disponibilidad = $conexion->real_escape_string($_POST[$key . 'Disponibilidad'] ?? '');
            $cantidad = $conexion->real_escape_string($_POST[$key . 'Cantidad'] ?? 0);
            $descripcion = $conexion->real_escape_string($_POST[$key . 'Descripcion'] ?? '');

            // Validar que la cantidad sea un número entero y mayor o igual a 0
            $cantidad = is_numeric($cantidad) ? intval($cantidad) : 0;

            if (!empty($disponibilidad) || $cantidad > 0 || !empty($descripcion)) {
                // Bind de parámetros y ejecución
                $stmt->bind_param("sssssss", $Numero_Documento, $elemento, $disponibilidad, $cantidad, $descripcion, $fecha, $hora);
                
                if ($stmt->execute()) {
                    $registroHecho = true; // Marcar que al menos un registro se hizo
                }
            }
        }
        $stmt->close();

        if ($registroHecho) {
            $mensaje = "Datos guardados exitosamente.";
        } else {
            $mensaje = "No se guardaron datos. Asegúrese de que al menos un campo tenga información.";
        }
    } else {
        $mensaje = "Por favor, ingrese un Número de Documento válido.";
    }
}

// Cerrar la conexión
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario de Ambiente</title>
    <link rel="stylesheet" href="../CSS//inver.css">
    <style>
        body {
            background-image: url("../img/SENA4.jpg");
        }

        .container {
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.7); /* Más transparente */
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 1200px;
            margin: 0 auto;
        }

        .logo {
            display: block;
            margin: 0 auto;
            width: 100px; /* Tamaño más pequeño */
        }

        .title {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            gap: 20px; /* Espacio entre las tablas */
        }

        .table-container {
            flex: 1;
        }

        .form-table {
            width: 100%;
            border-collapse: collapse;
        }

        .form-table th, .form-table td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        .search-container {
            display: flex;
            margin-bottom: 20px;
            align-items: center;
        }

        #searchInput {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .search-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #007BFF;
            color: #fff;
            cursor: pointer;
            margin-left: 10px;
        }

        .form-buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }

        .button {
            padding: 10px 50px;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none; /* Asegura que los enlaces no tengan subrayado */
            text-align: center; /* Centra el texto dentro del botón */
        }

        .start-btn {
            background-color: green;
        }

        .save-btn {
            background-color: black;
            color: #fff;
        }

        .historial-btn {
            background-color: green;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="../img/logosena.png" alt="Logo SENA" class="logo">
        <h1 class="title">Inventario De Ambiente</h1>

        <?php if (isset($mensaje) && !empty($mensaje)): ?>
            <div class="alert">
                <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php endif; ?>
        <form id="inventoryForm" method="POST" action="">
            <div class="form-group">
                <label for="documento">Numero_Documento:</label>
                <input type="text" id="documento" name="documento">
            </div>

            <div class="form-container">
               <!-- Primera tabla -->
               <div class="table-container">
                    <table class="form-table">
                        <thead>
                            <tr>
                                <th>Elemento</th>
                                <th>Disponibilidad</th>
                                <th>Cantidad</th>
                                <th>Descripción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Monitores</td>
                                <td class="options">
                                    <label><input type="radio" name="monitoresDisponibilidad" value="sí"> Sí</label>
                                    <label><input type="radio" name="monitoresDisponibilidad" value="no"> No</label>
                                </td>
                                <td><input type="number" name="monitoresCantidad" min="0"></td>
                                <td><input type="text" name="monitoresDescripcion"></td>
                            </tr>
                            <tr>
                                <td>Torres</td>
                                <td class="options">
                                    <label><input type="radio" name="torresDisponibilidad" value="sí"> Sí</label>
                                    <label><input type="radio" name="torresDisponibilidad" value="no"> No</label>
                                </td>
                                <td><input type="number" name="torresCantidad" min="0"></td>
                                <td><input type="text" name="torresDescripcion"></td>
                            </tr>
                            <tr>
                            <td>Teclados</td>
                                <td class="options">
                                    <label><input type="radio" name="tecladosDisponibilidad" value="sí"> Sí</label>
                                    <label><input type="radio" name="tecladosDisponibilidad" value="no"> No</label>
                                </td>
                                <td><input type="number" name="tecladosCantidad" min="0"></td>
                                <td><input type="text" name="tecladosDescripcion"></td>
                            </tr>
                            <tr>
                                <td>Mouses</td>
                                <td class="options">
                                    <label><input type="radio" name="mousesDisponibilidad" value="sí"> Sí</label>
                                    <label><input type="radio" name="mousesDisponibilidad" value="no"> No</label>
                                </td>
                                <td><input type="number" name="mousesCantidad" min="0"></td>
                                <td><input type="text" name="mousesDescripcion"></td>
                            </tr>
                            <tr>
                                <td>Sillas</td>
                                <td class="options">
                                    <label><input type="radio" name="sillasDisponibilidad" value="sí"> Sí</label>
                                    <label><input type="radio" name="sillasDisponibilidad" value="no"> No</label>
                                </td>
                                <td><input type="number" name="sillasCantidad" min="0"></td>
                                <td><input type="text" name="sillasDescripcion"></td>
                            </tr>
                            <tr>
                                <td>Mesas</td>
                                <td class="options">
                                    <label><input type="radio" name="mesasDisponibilidad" value="sí"> Sí</label>
                                    <label><input type="radio" name="mesasDisponibilidad" value="no"> No</label>
                                </td>
                                <td><input type="number" name="mesasCantidad" min="0"></td>
                                <td><input type="text" name="mesasDescripcion"></td>
                            </tr>
                            <tr>
                                <td>Tablero</td>
                                <td class="options">
                                    <label><input type="radio" name="tableroDisponibilidad" value="sí"> Sí</label>
                                    <label><input type="radio" name="tableroDisponibilidad" value="no"> No</label>
                                </td>
                                <td><input type="number" name="tableroCantidad" min="0"></td>
                                <td><input type="text" name="tableroDescripcion"></td>
                            </tr>
                            <tr>
                                <td>Muebles</td>
                                <td class="options">
                                    <label><input type="radio" name="mueblesDisponibilidad" value="sí"> Sí</label>
                                    <label><input type="radio" name="mueblesDisponibilidad" value="no"> No</label>
                                </td>
                                <td><input type="number" name="mueblesCantidad" min="0"></td>
                                <td><input type="text" name="mueblesDescripcion"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Segunda tabla -->
                <div class="table-container">
                    <table class="form-table">
                        <thead>
                            <tr>
                                <th>Elemento</th>
                                <th>Disponibilidad</th>
                                <th>Cantidad</th>
                                <th>Descripción</th>
                            </tr>
                        </thead>
                        <tbody>    
                            <tr>
                                <td>Aire Acondicionado</td>
                                <td class="options">
                                    <label><input type="radio" name="aireAcondicionadoDisponibilidad" value="sí"> Sí</label>
                                    <label><input type="radio" name="aireAcondicionadoDisponibilidad" value="no"> No</label>
                                </td>
                                <td><input type="number" name="aireAcondicionadoCantidad" min="0"></td>
                                <td><input type="text" name="aireAcondicionadoDescripcion"></td>
                            </tr>
                            <tr>
                                <td>Televisor</td>
                                <td class="options">
                                    <label><input type="radio" name="televisorDisponibilidad" value="sí"> Sí</label>
                                    <label><input type="radio" name="televisorDisponibilidad" value="no"> No</label>
                                </td>
                                <td><input type="number" name="televisorCantidad" min="0"></td>
                                <td><input type="text" name="televisorDescripcion"></td>
                            </tr>
                            <tr>
                                <td>Proyector</td>
                                <td class="options">
                                    <label><input type="radio" name="proyectorDisponibilidad" value="sí"> Sí</label>
                                    <label><input type="radio" name="proyectorDisponibilidad" value="no"> No</label>
                                </td>
                                <td><input type="number" name="proyectorCantidad" min="0"></td>
                                <td><input type="text" name="proyectorDescripcion"></td>
                            </tr>
                            <tr>
                                <td>Model de Internet</td>
                                <td class="options">
                                    <label><input type="radio" name="Model de InternetDisponibilidad" value="sí"> Sí</label>
                                    <label><input type="radio" name="Model de InternetDisponibilidad" value="no"> No</label>
                                </td>
                                <td><input type="number" name="Model de InternetCantidad" min="0"></td>
                                <td><input type="text" name="Model de InternetDescripcion"></td>
                            </tr>
                            <tr>
                                <td>Cortinas</td>
                                <td class="options">
                                    <label><input type="radio" name="cortinasDisponibilidad" value="sí"> Sí</label>
                                    <label><input type="radio" name="cortinasDisponibilidad" value="no"> No</label>
                                </td>
                                <td><input type="number" name="cortinasCantidad" min="0"></td>
                                <td><input type="text" name="cortinasDescripcion"></td>
                            </tr>
                            <tr>
                                <td>conectores Electricos</td>
                                <td class="options">
                                    <label><input type="radio" name="conectores ElectricosDisponibilidad" value="sí"> Sí</label>
                                    <label><input type="radio" name="conectores ElectricosDisponibilidad" value="no"> No</label>
                                </td>
                                <td><input type="number" name="conectores ElectricosCantidad" min="0"></td>
                                <td><input type="text" name="conectores ElectricosDescripcion"></td>
                            </tr>
                            <tr>
                                <td>Marcadores</td>
                                <td class="options">
                                    <label><input type="radio" name="marcadoresDisponibilidad" value="sí"> Sí</label>
                                    <label><input type="radio" name="marcadoresDisponibilidad" value="no"> No</label>
                                </td>
                                <td><input type="number" name="marcadoresCantidad" min="0"></td>
                                <td><input type="text" name="marcadoressDescripcion"></td>
                            </tr>
                            <tr>
                                <td>borrador De Tablero</td>
                                <td class="options">
                                    <label><input type="radio" name="borrador De TableroDisponibilidad" value="sí"> Sí</label>
                                    <label><input type="radio" name="borrador De TableroDisponibilidad" value="no"> No</label>
                                </td>
                                <td><input type="number" name="borrador De TableroCantidad" min="0"></td>
                                <td><input type="text" name="borrador De TableroDescripcion"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
        </div>

            <div class="form-buttons">
                <a href="login.php" class="button start-btn">Inicio</a>
                <button type="submit" class="button save-btn">Guardar</button>
                <a href="historialinver.php" class="button historial-btn">Historial</a>
            </div>
        </form>
    </div>

    <script>
        function filterTable() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementsByTagName("table");
            tr = table[0].getElementsByTagName("tr");

            for (i = 1; i < tr.length; i++) {
                tr[i].style.display = "none";
                td = tr[i].getElementsByTagName("td");
                for (j = 0; j < td.length; j++) {
                    if (td[j]) {
                        txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                            break;
                        }
                    }
                }
            }
        }
    </script>
</body>
</html>
