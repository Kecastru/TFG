<?php
session_start();
include 'conexiondb.php';

// Verifica que el usuario esté autenticado
if (!isset($_SESSION['username_id'])) {
    $_SESSION['mensaje'] = "Debe iniciar sesión.";
    header("Location: ../frontend/login.php");
    exit;
}

$id_usuario = $_SESSION['username_id'];

// Solo permite recibir solicitudes vía POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['mensaje'] = "Método no permitido.";
    header("Location: ../frontend/listadeseos.php");
    exit;
}

// Recoge y limpia los datos del formulario
$nombrefunko = trim($_POST['nombrefunko'] ?? '');
$numerofunko = trim($_POST['numerofunko'] ?? '');

// Valida que los campos obligatorios no estén vacíos
if (empty($nombrefunko) || empty($numerofunko)) {
    $_SESSION['mensaje'] = "Nombre y número del Funko son obligatorios.";
    header("Location: ../frontend/listadeseos.php");
    exit;
}

// Verifica que se haya subido una imagen correctamente
if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['mensaje'] = "Error al subir la imagen.";
    header("Location: ../frontend/listadeseos.php");
    exit;
}

// Obtiene datos de la imagen
$imagen_temp = $_FILES['imagen']['tmp_name'];
$tipo_imagen = $_FILES['imagen']['type'];
$imagen_datos = file_get_contents($imagen_temp);

// Valida el tipo MIME de la imagen
$tipos_validos = ['image/jpeg', 'image/png', 'image/gif'];
if (!in_array($tipo_imagen, $tipos_validos)) {
    $_SESSION['mensaje'] = "Tipo de imagen no válido. Solo se aceptan JPEG, PNG o GIF.";
    header("Location: ../frontend/listadeseos.php");
    exit;
}

// Prepara la inserción en la base de datos usando prepared statements
$stmt = $conn->prepare("INSERT INTO lista_deseos (nombrefunko, numerofunko, imagen, tipo_imagen, id_usuario) VALUES (?, ?, ?, ?, ?)");

if (!$stmt) {
    $_SESSION['mensaje'] = "Error al preparar la consulta: " . $conn->error;
    header("Location: ../frontend/listadeseos.php");
    exit;
}

// Vincula los parámetros y ejecuta la consulta
$stmt->bind_param("ssssi", $nombrefunko, $numerofunko, $imagen_datos, $tipo_imagen, $id_usuario);

if ($stmt->execute()) {
    $_SESSION['mensaje'] = "Funko añadido a la lista de deseos correctamente.";
} else {
    $_SESSION['mensaje'] = "Error al insertar en la base de datos: " . $stmt->error;
}

// Cierra la sentencia y la conexión
$stmt->close();
$conn->close();

// Redirige a la lista de deseos
header("Location: ../frontend/listadeseos.php");
exit;
?>







