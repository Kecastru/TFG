```php
<?php
// Inicia la sesión para acceder a las variables de sesión
session_start();

// Conecta a la base de datos
include 'conexiondb.php';

// Configura la cabecera para devolver JSON
header('Content-Type: application/json');

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['username_id'])) {
    echo json_encode(['error' => 'No has iniciado sesión.']);
    exit;
}

// Obtiene el ID del usuario desde la sesión
$id_usuario_actual = $_SESSION['username_id'];

// Obtiene el ID de la colección desde los parámetros GET
$idcoleccion = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Valida que se haya proporcionado un ID válido
if (!$idcoleccion) {
    echo json_encode(['error' => 'Colección no válida.']);
    exit;
}

// Consulta para obtener detalles de la colección y del usuario dueño
$sql_coleccion = "SELECT c.nombre AS nombre_coleccion, c.id_usuario, u.username
                   FROM colecciones c
                   JOIN usuarios u ON c.id_usuario = u.id
                   WHERE c.idcolecciones = ?";

$stmt_coleccion = $conn->prepare($sql_coleccion);
$stmt_coleccion->bind_param("i", $idcoleccion);
$stmt_coleccion->execute();
$result_coleccion = $stmt_coleccion->get_result();

// Verifica si se encontró la colección
if ($result_coleccion->num_rows === 0) {
    echo json_encode(['error' => 'Colección no encontrada.']);
    exit;
}

// Obtiene datos de la colección
$coleccion = $result_coleccion->fetch_assoc();

// Determina si el usuario actual es el dueño de la colección
$es_dueno = ($coleccion['id_usuario'] == $id_usuario_actual);

// Consulta para obtener los funkos de la colección
$sql_funkos = "SELECT nombrefunko, numerofunko, imagen, tipo_imagen
              FROM funkopop
              WHERE idcoleccion = ?";

$stmt_funkos = $conn->prepare($sql_funkos);
$stmt_funkos->bind_param("i", $idcoleccion);
$stmt_funkos->execute();
$result_funkos = $stmt_funkos->get_result();

// Procesa los funkos y codifica la imagen en base64
$funkos = [];
while ($row = $result_funkos->fetch_assoc()) {
    $row['imagen'] = base64_encode($row['imagen']);
    $funkos[] = $row;
}

// Devuelve los datos en formato JSON
echo json_encode([
    'coleccion' => [
        'nombre' => $coleccion['nombre_coleccion'],
        'usuario' => $coleccion['username'],
        'idcoleccion' => $idcoleccion,
        'es_dueno' => $es_dueno
    ],
    'funkos' => $funkos
]);
?>
