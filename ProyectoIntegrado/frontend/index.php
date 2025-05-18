<?php
// Inicia la sesión para gestionar datos del usuario
session_start();

// Incluye la conexión a la base de datos
include '../backend/conexiondb.php';

$mensaje = "";

// Procesa el  formulario solo si se envía vía POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoge datos del formulario
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepara la consulta para evitar inyección SQL
    $stmt = $conn->prepare("SELECT id, password FROM usuarios WHERE username = ?");
    if ($stmt) {
        // Vincula el parámetro y lo ejecuta
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($password, $user['password'])) {
            // Contraseña correcta, iniciar sesión
            $_SESSION['username_id'] = $user['id'];
            $_SESSION['username'] = $username;
            header("Location: menu.php");
            exit;
        } else {
            // Usuario no encontrado o contraseña incorrecta
            $mensaje = "Usuario o contraseña incorrectos";
        }
    } else {
        // Error al preparar la consulta
        error_log("Error al preparar la consulta de inicio de sesión: " . $conn->error);
        $mensaje = "Error interno del servidor.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Inicio de sesión</title>
    <!-- Enlace a la hoja de estilos CSS -->
    <link rel="stylesheet" href="styles/styles.css" />
</head>
<body>
    <!-- Encabezado con banner y logo -->
    <header>
        <div class="banner">
            <div class="logo">
                <img src="images/logo.png" alt="logo" />
            </div>
        </div>
    </header>

    <!-- Formulario de inicio de sesión -->
    <form method="post" class="formConnect">
        <!-- Campo para ingresar usuario -->
        <label for="username">Usuario</label>
        <input type="text" id="username" name="username" required />

        
        <br><br>

        <!-- Campo para ingresar contraseña -->
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" required />

        <br><br>

        <!-- Botón para enviar formulario -->
        <button type="submit">Iniciar sesión</button>

        <!-- Botón para ir a registro -->
        <button type="button" id="btnRegistrarse">Registrarse</button>
    </form>

    <!-- Muestra un mensaje en alerta si la variable $mensaje contiene algo -->
    <?php if ($mensaje): ?>
        <script>
            alert("<?php echo $mensaje; ?>");
        </script>
    <?php endif; ?>

    <!-- Script  -->
    <script src="scripts/scriptbtn.js"></script>
</body>
</html>
