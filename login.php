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

$Numero_documento = $_POST['Numero_Documento'];
$Password = $_POST['Password'];

// Verificar las credenciales del usuario
$sql = "SELECT * FROM usuarios WHERE Numero_documento='$Numero_documento'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['Password'])) {
        echo "Inicio de sesión exitoso.";
        header("Location: pagina2.html"); // dirige a la página principal
        exit();
    } else {
        echo "Contraseña incorrecta.";
        header("Location: index.html"); // volver de nuevo al formulario de inicio de sesión
        exit();
    }
} else {
    echo "El usuario no está registrado.";
    header("Location: index.html"); //volver de nuevo al formulario de inicio de sesión
    exit();
}

$conn->close();
?>