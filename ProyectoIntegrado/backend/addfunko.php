<?php
session_start(); // Inicia la sesión para gestionar variables de sesión

// Incluye la conexión a la base de datos
include 'conexiondb.php';

// Verifica si el usuario está autenticado, si no, redirige a la página de inicio
if (!isset($_SESSION['username_id'])) {
    header('Location: index.php');
    exit;
}

// Procesa la solicitud solo si es método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtiene el ID de la colección desde el formulario y lo convierte a entero para mayor seguridad
    $idColeccion = intval($_POST['idcoleccion']);
    
    // Obtiene los datos enviados por el formulario
    $nombre = $_POST['nombrefunko'];
    $numero = $_POST['numerofunko'];

    // Verifica si se ha subido una imagen correctamente
    if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
        // Si hay error en la carga de la imagen, guarda un mensaje en sesión y redirige
        $_SESSION['registro_mensaje'] = "Error al subir la imagen.";
        header("Location: ../frontend/registrofunko.php?id=" . $idColeccion);
        exit;
    }

    // Lee el contenido binario de la imagen subida
    $imagen = file_get_contents($_FILES['imagen']['tmp_name']);
    // Obtiene el tipo MIME de la imagen
    $tipoImagen = $_FILES['imagen']['type'];

    // Prepara la sentencia SQL para insertar los datos en la tabla funkopop
    $stmt = $conn->prepare("INSERT INTO funkopop (nombrefunko, numerofunko, tipo_imagen, imagen, idcoleccion) VALUES (?, ?, ?, ?, ?)");
    // Vincula los parámetros con los tipos adecuados
    $stmt->bind_param("sissi", $nombre, $numero, $tipoImagen, $imagen, $idColeccion);

    // Ejecuta la consulta y verifica si fue exitosa
    if ($stmt->execute()) {
        // Si se inserta correctamente, redirige a la vista de colección
        header("Location: ../frontend/ver_collection.php?id=" . $idColeccion);
        exit;
    } else {
        // Si hay error en la inserción, guarda un mensaje y redirige al formulario
        $_SESSION['registro_mensaje'] = "Error al añadir Funko.";
        header("Location: ../frontend/registrofunko.php?id=" . $idColeccion);
        exit;
    }
} else {
    // Si la solicitud no es POST, redirige a la página de registro con el ID de colección
    header('Location: ../frontend/registrofunko.php?id=' . intval($_POST['idcoleccion']));
    exit;
}
?>



