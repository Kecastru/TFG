
<?php
/* imagen.php: Obtiene y muestra una imagen almacenada en la base de datos */

// Incluye la conexión a la base de datos
include 'conexiondb.php';

// Verifica que se haya enviado un parámetro 'idfunko' válido
if (isset($_GET['idfunko']) && is_numeric($_GET['idfunko'])) {
    $idfunko = $_GET['idfunko'];

    // Prepara la consulta para obtener la imagen y su tipo
    $stmt = $conn->prepare("SELECT imagen, tipo_imagen FROM funkopop WHERE idfunkopop = ?");
    $stmt->bind_param("i", $idfunko);
    $stmt->execute();

    // Vincula las variables para recibir los resultados
    $stmt->bind_result($imageData, $imageType);

    // Si se encuentra la imagen, la muestra
    if ($stmt->fetch()) {
        header("Content-Type: " . $imageType);
        echo $imageData;
    } else {
        // Si no se encuentra la imagen, devuelve un 404
        header("HTTP/1.0 404 Not Found");
        echo "Imagen no encontrada.";
    }

    // Cierra la declaración
    $stmt->close();

} else {
    // Si el parámetro no es válido, devuelve un error 400
    header("HTTP/1.0 400 Bad Request");
    echo "ID de Funko no válido.";
}

// Cierra la conexión a la base de datos
$conn->close();
?>

