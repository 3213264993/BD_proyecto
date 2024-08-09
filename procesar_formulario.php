<?php

    $servidor = "localhost";
    $usuario = "root";
    $clave = "";
    $bd = "ambicontrol";

    $coneccion = mysqli_connect ($servidor, $usuario, $clave, $bd )

?>

<form  method="post">
            <input type="text" name="Nombre_completo" placeholder="Nombre_completo" required>
            <input type="varchar" name="Email" placeholder="Email" required>
            <input type="varchar" name="Password" placeholder="Password" required>
            <select name="Tipo_documento" required>
                <option value="" disabled selected>Tipo_documento</option>
                <option value="CC">Cédula de Ciudadanía</option>
                <option value="TI">Tarjeta de Identidad</option>
                <option value="PP">Pasaporte</option>
            </select>
            <input type="varchar" name="Numero_documento" placeholder="Número_documento" required>
            <button type="submit" class="btn" name="enviar">
        </form>
<?php

  if(isset($_POST['enviar'])){
      
     // Obtener los datos del formulario
             $Nombre_completo = $_POST['Nombre_completo'];
             $Email = $_POST['Email'];
             $password = $_POST['password'];
             $Tipo_documento = $_POST['Tipo_documento'];
             $Numero_documento = $_POST['Numero_documento'];
 
             $insertar = "INSERT INTO usuarios VALUES ('$Nombre_completo', '$Email', '$Password', '$Tipo_documento', '$Numero_documento','')";
      
      $coneccion = mysqli_query($coneccion,$insertar);
  }
?>

