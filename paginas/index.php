<?php
$servidor = "localhost";
$usuario = "root";
$clave = "";
$bd = "ambicontrol";

// Establecer conexión a la base de datos
$coneccion = mysqli_connect($servidor, $usuario, $clave, $bd);

if (!$coneccion) {
    die("Error de conexión: " . mysqli_connect_error());
}

$mensaje = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $Nombre_completo = $_POST['Nombre_completo'];
    $Email = $_POST['Email'];
    $Password = $_POST['Password'];
    $Tipo_documento = $_POST['Tipo_documento'];
    $Numero_documento = $_POST['Numero_documento'];

    $insertar = "INSERT INTO usuarios(`Nombre_completo`, `Email`, `password`, `Tipo_documento`, `Numero_documento`) VALUES ('$Nombre_completo', '$Email', '$Password', '$Tipo_documento', '$Numero_documento')";

    if (mysqli_query($coneccion, $insertar)) {
        $mensaje = "<div class='alert alert-success' role='alert'>Nuevo registro creado exitosamente.</div>";
    } else {
        $mensaje = "<div class='alert alert-danger' role='alert'>Error: " . mysqli_error($coneccion) . "</div>";
    }

    mysqli_close($coneccion);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/registro.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
   
</head>
<body>
    <div class="container">
        <img src="../img/logosena.png" alt="Logo SENA" class="logo">
        <h2 class="text-center">Registro de Usuario</h2>

        <?php if ($mensaje != ""): ?>
            <div class="mt-3">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <form action="" method="post">
            <div class="mb-3">
                <input type="text" class="form-control" name="Nombre_completo" placeholder="Nombre_completo" required>
            </div>
            <div class="mb-3">
                <select class="form-select" name="Tipo_documento" required>
                    <option value="" disabled selected>Selecciona tipo_documento</option>
                    <option value="CC">Cédula de Ciudadanía</option>
                    <option value="TI">Tarjeta de Identidad</option>
                    <option value="PP">Pasaporte</option>
                </select>
            </div>
            <div class="mb-3">
                <input type="text" class="form-control" name="Numero_documento" placeholder="Número_documento" required>
            </div>
            <div class="mb-3">
                <input type="email" class="form-control" name="Email" placeholder="Email" required>
            </div>
            <div class="mb-3 input-container">
                <input type="password" class="form-control" id="Password" name="Password" placeholder="Password" required>
                <i class="bi bi-eye toggle-password" id="togglePassword"></i>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-success">Registrarse</button>
            </div>
        </form>
        <div class="text-center mt-3">
            <a href="http://localhost/proyecto.cdae/paginas/login.php" class="btn  btn-black">INICIO</a>
        </div>
    </div>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const togglePassword = document.getElementById('togglePassword');
            const passwordField = document.getElementById('Password');

            togglePassword.addEventListener('click', () => {
                // Toggle the type attribute
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);

                // Toggle the eye icon
                togglePassword.classList.toggle('bi-eye');
                togglePassword.classList.toggle('bi-eye-slash');
            });
        });
    </script>
</body>
</html>

