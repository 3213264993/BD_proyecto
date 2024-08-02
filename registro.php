<?php
// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ambicontrol";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Usuarios
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Tipo_formulario']) && $_POST['Tipo_formulario'] == "usuarios") {
    $Nombre_completo = $_POST['Nombre_completo'];
    $Email = $_POST['Email'];
    $Password = Password_hash($_POST['Password'], PASSWORD_DEFAULT); // Encriptar la contraseña
    $Tipo_documento = $_POST['Tipo_documento'];
    $Numero_documento = $_POST['Numero_documento'];

    // Verificar si el usuario ya está registrado
    $sql_check = $conn->prepare("SELECT * FROM usuarios WHERE Numero_documento = ? OR Email = ?");
    $sql_check->bind_param("ss", $Numero_documento, $Email);
    $sql_check->execute();
    $result_check = $sql_check->get_result();

    if ($result_check->num_rows > 0) {
        echo "El usuario ya está registrado.";
        // Redirigir de nuevo al formulario de registro después de 3 segundos
        header("refresh:3;url=registro.php");
        exit();
    } else {
        // Insertar el nuevo usuario en la base de datos
        $sql = $conn->prepare("INSERT INTO usuarios (Nombre_completo, Email, Password, Tipo_documento, Numero_documento) VALUES (?, ?, ?, ?, ?)");
        $sql->bind_param("sssss", $Nombre_completo, $Email, $Password, $Tipo_documento, $Numero_documento);

        if ($sql->execute() === TRUE) {
            echo "Registro exitoso.";
            // Redirigir al inicio de sesión después de 3 segundos
            header("refresh:3;url=../index.php");
            exit();
        } else {
            echo "Error: " . $sql->error;
        }
    }
    $sql_check->close();
    $sql->close();
}

// Registro de ambientes
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Tipo_formulario']) && $_POST['Tipo_formulario'] == "asignaciones") {
    $Numero_documento = $_POST['Numero_documento'];
    $Tipo_asignacion = $_POST['Tipo_asignacion'];
    $Numero_ambiente = $_POST['Numero_ambiente'];

    $sql = $conn->prepare("INSERT INTO asignaciones (Numero_documento, Tipo_asignacion, Numero_ambiente) VALUES (?, ?, ?)");
    $sql->bind_param("sss", $Numero_documento, $Tipo_asignacion, $Numero_ambiente);

    if ($sql->execute() === TRUE) {
        echo "Asignación exitosa.";
        // Redirigir después de 3 segundos
        header("refresh:3;url=asignar_ambiente.php");
        exit();
    } else {
        echo "Error: " . $sql->error;
    }
    $sql->close();
}

// Observaciones
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Tipo_formulario']) && $_POST['Tipo_formulario'] == "observaciones") {
    $Numero_documento = $_POST['Numero_documento'];
    $Observacion = $_POST['Observacion'];

    $sql = $conn->prepare("INSERT INTO observaciones (Numero_documento, Observacion) VALUES (?, ?)");
    $sql->bind_param("ss", $Numero_documento, $Observacion);

    if ($sql->execute() === TRUE) {
        echo "Observación registrada exitosamente.";
        // Redirigir después de 3 segundos
        header("refresh:3;url=registrar_observacion.php");
        exit();
    } else {
        echo "Error: " . $sql->error;
    }
    $sql->close();
}

// Inventario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Tipo_formulario']) && $_POST['Tipo_formulario'] == "inventario") {
    $Fecha = $_POST['Fecha'];
    $Hora = $_POST['Hora'];
    $Elemento = $_POST['Elemento'];
    $Disponibilidad = $_POST['Disponibilidad'];
    $Cantidad = $_POST['Cantidad'];
    $Descripcion = $_POST['Descripcion'];

    $sql = $conn->prepare("INSERT INTO inventario (Fecha, Hora, Elemento, Disponibilidad, Cantidad, Descripcion) VALUES (?, ?, ?, ?, ?, ?)");
    $sql->bind_param("ssssss", $Fecha, $Hora, $Elemento, $Disponibilidad, $Cantidad, $Descripcion);

    if ($sql->execute() === TRUE) {
        echo "Inventario registrado exitosamente.";
        // Redirigir después de 3 segundos
        header("refresh:3;url=registrar_inventario.php");
        exit();
    } else {
        echo "Error: " . $sql->error;
    }
    $sql->close();
}

$conn->close();
?>