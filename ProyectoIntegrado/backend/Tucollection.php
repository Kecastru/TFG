<?php
// Inicia la sesión para acceder a variables de sesión
session_start();

// Incluye la conexión a la base de datos
include 'conexiondb.php';

// Configura el encabezado para devolver JSON
header('Content-Type: application/json');

// Verifica si el usuario está autenticado
if (!isset($_SESSION['username_id'])) {
    http_response_code(403); // Forbidden
    echo json_encode(['error' => 'Usuario no autenticado']);
    exit;
}

// Obtiene el ID del usuario desde la sesión
$user_id = $_SESSION['username_id'];

// Función para devolver respuestas en formato JSON y terminar ejecución
function respond($data, $status_code = 200) {
    http_response_code($status_code);
    echo json_encode($data);
    exit;
}

// Maneja la creación de una nueva colección si se recibe un nombre por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty(trim($_POST['nombre_coleccion'] ?? ''))) {
    $nombre_coleccion = trim($_POST['nombre_coleccion']);

    // Prepara la consulta para insertar la nueva colección
    $stmt = $conn->prepare("INSERT INTO colecciones (nombre, id_usuario) VALUES (?, ?)");
    if ($stmt) {
        $stmt->bind_param("si", $nombre_coleccion, $user_id);
        if ($stmt->execute()) {
            respond(['success' => true, 'message' => "Colección '" . htmlspecialchars($nombre_coleccion) . "' añadida correctamente."]);
        } else {
            respond(['success' => false, 'error' => "Error al añadir la colección: " . $stmt->error]);
        }
        $stmt->close();
    } else {
        respond(['success' => false, 'error' => "Error al preparar la consulta: " . $conn->error], 500);
    }
}



// Prepara la consulta para obtener las colecciones del usuario
$stmt_colecciones = $conn->prepare("SELECT idcolecciones, nombre FROM colecciones WHERE id_usuario = ?");
if ($stmt_colecciones) {
    $stmt_colecciones->bind_param("i", $user_id);
    $stmt_colecciones->execute();
    $result = $stmt_colecciones->get_result();

    $colecciones = [];
    while ($row = $result->fetch_assoc()) {
        $colecciones[] = $row;
    }

    $stmt_colecciones->close();

    // Devuelve las colecciones en formato JSON
    respond(['colecciones' => $colecciones]);
} else {
    respond(['error' => 'Error al obtener las colecciones: ' . $conn->error], 500);
}

// Cierra la conexión a la base de datos
$conn->close();
?>
