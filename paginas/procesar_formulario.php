<?php

    $servidor = "localhost";
    $usuario = "root";
    $clave = "";
    $bd = "ambicontrol";

    $coneccion = mysqli_connect ($servidor, $usuario, $clave, $bd )

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="../CSS/registro.css">
</head>
<body>
    <div class="container">
        <img src="../img/logosena.png" alt="Logo SENA" class="logo">
        <h2>Registro de Usuario</h2>
        <form action="" method="post">
            <input type="text" name="Nombre_completo" placeholder="Nombre_completo" required>
            <input type="Email" name="Email" placeholder="Email" required>
            <input type="Password" name="Password" placeholder="Password" required>
            <select name="Tipo_documento" required>
                <option value="" disabled selected>Tipo_documento</option>
                <option value="CC">Cédula de Ciudadanía</option>
                <option value="TI">Tarjeta de Identidad</option>
                <option value="PP">Pasaporte</option>
            </select>
            <input type="varchar" name="Numero_documento" placeholder="Número_documento" required>
            <input type="submit" class="btn" name = "enviar">
        </form>
        <br>
        <a href="file:///C:/xampp/htdocs/proyecto.cdae/index.html" class="link-button">INICIO</a>
    </div>
</body>
</html>

<?php

if(isset($_POST['enviar'])){
      
    // Obtener los datos del formulario
            $Nombre_completo = $_POST['Nombre_completo'];
            $Email = $_POST['Email'];
            $Password = $_POST['Password'];
            $Tipo_documento = $_POST['Tipo_documento'];
            $Numero_documento = $_POST['Numero_documento'];

            $insertar = "INSERT INTO usuarios(`Nombre_completo`, `Email`, `password`, `Tipo_documento`, `Numero_documento`) VALUES ('$Nombre_completo', '$Email', '$Password', '$Tipo_documento', '$Numero_documento')";
     
     $coneccion = mysqli_query($coneccion,$insertar);
 }
?>