<?php
// inventario de 
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "ambicontrol");

// Verificar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar los datos del formulario
    $fecha = $_POST['fecha'] ?? '';
    $hora = $_POST['hora'] ?? '';

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

    foreach ($elementos as $key => $elemento) {
        $disponibilidad = $conexion->real_escape_string($_POST[$key . 'Disponibilidad'] ?? '');
        $cantidad = $conexion->real_escape_string($_POST[$key . 'Cantidad'] ?? 0);
        $descripcion = $conexion->real_escape_string($_POST[$key . 'Descripcion'] ?? '');

        // Insertar datos en la base de datos
        $sql = "INSERT INTO inventario (elemento, disponibilidad, cantidad, descripcion, fecha, hora) 
                VALUES ('$elemento', '$disponibilidad', '$cantidad', '$descripcion', '$fecha', '$hora')";

        if ($conexion->query($sql) === TRUE) {
            echo "Registro de $elemento guardado con éxito.<br>";
        } else {
            echo "Error: " . $sql . "<br>" . $conexion->error;
        }
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
    <link rel="stylesheet" href="../CSS/inver.css">
   
</head>
<body>
    <div class="container">
        <img src="../img/logosena.png" alt="Logo SENA" class="logo">
        <h1 class="title">Inventario De Ambiente</h1>

        <form id="inventoryForm" method="POST" action="">
            <input type="hidden" id="fecha" name="fecha">
            <input type="hidden" id="hora" name="hora">

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
                    <tr data-element="Monitores">
                        <td>Monitores</td>
                        <td class="options">
                            <label><input type="radio" name="monitoresDisponibilidad" value="sí"> Sí</label>
                            <label><input type="radio" name="monitoresDisponibilidad" value="no"> No</label>
                        </td>
                        <td><input type="number" name="monitoresCantidad" min="0"></td>
                        <td><input type="text" name="monitoresDescripcion"></td>
                    </tr>
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
                        <td>Modelo De Internet</td>
                        <td class="options">
                            <label><input type="radio" name="modeloDeInternetDisponibilidad" value="sí"> Sí</label>
                            <label><input type="radio" name="modeloDeInternetDisponibilidad" value="no"> No</label>
                        </td>
                        <td><input type="number" name="modeloDeInternetCantidad" min="0"></td>
                        <td><input type="text" name="modeloDeInternetDescripcion"></td>
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
                        <td>Conectores Eléctricos</td>
                        <td class="options">
                            <label><input type="radio" name="conectoresElectricosDisponibilidad" value="sí"> Sí</label>
                            <label><input type="radio" name="conectoresElectricosDisponibilidad" value="no"> No</label>
                        </td>
                        <td><input type="number" name="conectoresElectricosCantidad" min="0"></td>
                        <td><input type="text" name="conectoresElectricosDescripcion"></td>
                    </tr>
                    <tr>
                        <td>Extensión</td>
                        <td class="options">
                            <label><input type="radio" name="extensionDisponibilidad" value="sí"> Sí</label>
                            <label><input type="radio" name="extensionDisponibilidad" value="no"> No</label>
                        </td>
                        <td><input type="number" name="extensionCantidad" min="0"></td>
                        <td><input type="text" name="extensionDescripcion"></td>
                    </tr>
                    <tr>
                        <td>Marcadores</td>
                        <td class="options">
                            <label><input type="radio" name="marcadoresDisponibilidad" value="sí"> Sí</label>
                            <label><input type="radio" name="marcadoresDisponibilidad" value="no"> No</label>
                        </td>
                        <td><input type="number" name="marcadoresCantidad" min="0"></td>
                        <td><input type="text" name="marcadoresDescripcion"></td>
                    </tr>
                    <tr>
                        <td>Borrador De Tablero</td>
                        <td class="options">
                            <label><input type="radio" name="borradorDeTableroDisponibilidad" value="sí"> Sí</label>
                            <label><input type="radio" name="borradorDeTableroDisponibilidad" value="no"> No</label>
                        </td>
                        <td><input type="number" name="borradorDeTableroCantidad" min="0"></td>
                        <td><input type="text" name="borradorDeTableroDescripcion"></td>
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
            <div class="form-group">
                <button type="submit" class="submit-button">Enviar</button>
                <button type="button" class="history-button" onclick="verHistorial()">Historial</button>
            </div>
        </form>
    </div>

    <script>
        function updateDateTime() {
            const now = new Date();
            const fecha = now.toISOString().split('')[0];
            const hora = now.toTimeString().split(' ')[0];

            document.getElementById('fecha').value = fecha;
            document.getElementById('hora').value = hora;
        }

        document.getElementById('inventoryForm').addEventListener('submit', function(event) {
            event.preventDefault();
            updateDateTime(); // Actualizar la fecha y la hora antes de enviar el formulario

            const formData = new FormData(this);
            const entries = Array.from(formData.entries());

            // Obtener los datos de cada fila de la tabla
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const elementName = row.getAttribute('data-element');
                const disponibilidad = row.querySelector(`input[name="${elementName.toLowerCase().replace(/\s+/g, '')}Disponibilidad"]:checked`)?.value || '';
                const cantidad = row.querySelector(`input[name="${elementName.toLowerCase().replace(/\s+/g, '')}Cantidad"]`).value || '';
                const descripcion = row.querySelector(`input[name="${elementName.toLowerCase().replace(/\s+/g, '')}Descripcion"]`).value || '';

                // Agregar los datos del elemento al historial
                let historial = JSON.parse(localStorage.getItem('historial')) || [];
                historial.push({
                    elemento: elementName,
                    disponibilidad: disponibilidad,
                    cantidad: cantidad,
                    descripcion: descripcion,
                    fecha: formData.get('fecha'),
                    hora: formData.get('hora')
                });
                localStorage.setItem('historial', JSON.stringify(historial));
            });

            // Enviar los datos del formulario al servidor
            this.submit();
        });

        function verHistorial() {
            // Redirigir a la página de historial
            window.location.href = 'inverhistorial.html';
        }
    </script>
</body>
</html>
