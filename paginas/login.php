<?php
$servidor = "localhost";
$usuario = "root";
$clave = "";
$bd = "ambicontrol";

// Establecer conexión a la base de datos
$coneccion = mysqli_connect($servidor, $usuario, $clave, $bd);

if (isset($_POST['Numero_Documento']) && isset($_POST['password'])) {
    $Numero_Documento = $_POST['Numero_Documento'];
    $password = $_POST['password'];

    $consulta = "SELECT * FROM usuarios WHERE Numero_documento = '$Numero_Documento' AND password = '$password'";
    $resultado = mysqli_query($coneccion, $consulta);

    if (mysqli_num_rows($resultado) > 0) {
        // El usuario está registrado, lo redirigimos a la página principal con el menú
        echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido Usuarios</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/mipag2.css"> 
</head>
<body>
    <div class="container">
        <div class="welcome-container">
            <img src="../img/logosena.png" alt="Logo SENA" class="logo">
            <h1 class="welcome-title">BIENVENIDO USUARIOS</h1>
            <a href="http://localhost/proyecto.cdae/paginas/ambientes.php" class="btn btn-custom">Ambientes de Formación</a>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>';
        exit;
    } else {
        // El usuario no está registrado, mostramos un mensaje
        echo '<script>alert("No está registrado, por favor regístrese");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AmbiControl - Inicio de Sesión</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../CSS//ingreso.css">
    <style>
        body{
            background-image: url(../img//SENA4.jpg);
        }
        /* Estilo personalizado para el botón de Solicitar nueva contraseña */
        .btn-request-password {
            background-color: #28a745; /* Color verde */
            color: white;
            padding: 8px 10px; /* Tamaño más pequeño */
            font-size: 0.9rem;
            margin-top: 20px; /* Separación adicional */
        }

        .btn-request-password:hover {
            background-color: gray; 
            color:black;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="login-box text-center p-4 shadow rounded">
            <img src="../img/logosena.png" alt="Logo SENA" class="img-fluid mb-3" style="max-width: 150px;">
            <h1 class="mb-4">AmbiControl</h1>
            <p class="mb-4">Bienvenido, por favor inicie sesión</p>
            <form id="loginForm" action="" method="POST">
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                    <input type="text" class="form-control" placeholder="Numero_Documento" name="Numero_Documento" required>
                </div>
                <div class="input-group mb-3 position-relative">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" id="password" placeholder="Contraseña" name="password" required>
                    <span class="input-group-text" id="togglePassword" style="cursor: pointer;"><i class="fas fa-eye"></i></span>
                </div>
                <button type="submit" class="btn btn-primary btn-block w-100 mb-3">Ingresar</button>
                <button type="button" class="btn btn-register w-100 mb-3" onclick="redirectTo('index.php')">Regístrese</button>
                <button type="button" class="btn btn-request-password w-100" onclick="redirectToWhatsApp()">Solicitar nueva contraseña</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function redirectTo(page) {
            window.location.href = page;
        }

        function redirectToWhatsApp() {
            const phoneNumber = "3223691517";
            const message = "Hola buen día, requiero restablecer mi contraseña de AmbiControl.";
            const url = `https://wa.me/57${phoneNumber}?text=${encodeURIComponent(message)}`;
            window.open(url, "_blank");
        }

        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function () {
            // Alternar el tipo de entrada entre 'password' y 'text'
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // Alternar el ícono entre 'fa-eye' y 'fa-eye-slash'
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>
