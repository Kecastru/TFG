<?php
// Inicia la sesión para gestionar mensajes y datos del usuario
session_start();

// Incluye conexión a la base de datos
include 'conexiondb.php';

// Procesa el formulario si es una solicitud POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoge los datos del formulario
    $username = $_POST['username'];
    $password_plain = $_POST['password'];
    $email = $_POST['email'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];

    // Encripta la contraseña usando password_hash
    $password = password_hash($password_plain, PASSWORD_DEFAULT);

    // Prepara la consulta SQL para insertar el nuevo usuario
    $sql = "INSERT INTO usuarios (username, password, email, nombre, apellidos, fecha_nacimiento) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Vincula los parámetros a la consulta preparada
        $stmt->bind_param("ssssss", $username, $password, $email, $nombre, $apellidos, $fecha_nacimiento);

        // Ejecuta la consulta y verifica el resultado
        if ($stmt->execute()) {
            $_SESSION['registro_mensaje'] = "Registro exitoso.";
        } else {
            $_SESSION['registro_mensaje'] = "Fallo en el registro: " . $stmt->error;
        }

        // Cierra la sentencia
        $stmt->close();
    } else {
        // Error en la preparación de la consulta
        $_SESSION['registro_mensaje'] = "Error en la preparación de la consulta: " . $conn->error;
    }

    // Redirige a la página de registro para mostrar el mensaje
    header("Location: registro.php");
    exit();
}

// Obtiene y limpia el mensaje de registro si existe
$mensaje = $_SESSION['registro_mensaje'] ?? null;
unset($_SESSION['registro_mensaje']);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Registro de usuario</title>
    <link rel="stylesheet" href="../frontend/styles/styles.css" />
</head>
<body>
<header>
    <div class="banner">
        <div class="logo">
            <img src="../frontend/images/logo.png" alt="logo" />
        </div>
        <br /><br />
    </div>
</header>

<!-- Formulario de registro -->
<form method="post" class="formConnect">
    <label for="username">Nombre de Usuario</label>
    <input type="text" name="username" required />
    <br /><br />

    <label for="password">Contraseña</label>
    <input
        type="password"
        name="password"
        required
        pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}"
        title="La contraseña debe tener al menos 6 caracteres, incluyendo una letra mayúscula, una minúscula y un número"
    />
    <br /><br />

    <label for="email">Email</label>
    <input
        type="email"
        name="email"
        required
        pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
        title="Introduce un correo electrónico válido"
    />
    <br /><br />

    <label for="nombre">Nombre</label>
    <input
        type="text"
        name="nombre"
        required
        pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
        title="Solo letras permitidas"
    />
    <br /><br />

    <label for="apellidos">Apellidos</label>
    <input
        type="text"
        name="apellidos"
        required
        pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
        title="Solo letras permitidas"
    />
    <br /><br />

    <label for="fecha_nacimiento">Fecha de nacimiento</label>
    <input type="date" name="fecha_nacimiento" required />
    <br /><br />

    <button type="submit">Registrarse</button>
    <button type="button" id="backindex">Volver a Inicio de Sesión</button>
</form>

<!-- Scripts -->
<script src="../frontend/scripts/scriptbtn.js"></script>
<script>
    // Mostrar mensaje de registro si existe
    <?php if ($mensaje): ?>
        alert("<?php echo $mensaje; ?>");
    <?php endif; ?>
</script>
</body>
</html>


    