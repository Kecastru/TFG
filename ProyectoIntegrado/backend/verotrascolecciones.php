<?php
session_start(); // Inicia sesión para manejar sesiones de usuario
include 'conexiondb.php'; // Incluye la conexión a la base de datos

// Verifica si el usuario está autenticado
if (!isset($_SESSION['username_id'])) {
    echo json_encode(['error' => 'Debes iniciar sesion para continuar']);
    exit;
}

// Asegura que la petición sea de método GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

// Obtiene el ID del usuario actual de la sesión
$usuarioActual = intval($_SESSION['username_id']);

// Consulta para obtener colecciones de otros usuarios
$sql = "SELECT 
            c.idcolecciones, 
            c.nombre AS nombre_coleccion, 
            u.username AS nombre_usuario
        FROM colecciones c
        INNER JOIN usuarios u ON c.id_usuario = u.id
        WHERE u.id != ?";  // Muestra colecciones que no sean del usuario actual

// Prepara la consulta para evitar inyección SQL
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $usuarioActual);

// Ejecuta la consulta y verifica los errores
if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al ejecutar la consulta: ' . $stmt->error]);
    exit;
}

// Obtiene resultados
$result = $stmt->get_result();
$colecciones = [];

// Recorre los resultados y los almacena en un array
while ($row = $result->fetch_assoc()) {
    $colecciones[] = $row;
}

// Define que la respuesta sea en formato JSON
header('Content-Type: application/json');
// Envia las colecciones en formato JSON
echo json_encode(['colecciones' => $colecciones]);

// Cierra la declaración y la conexión
$stmt->close();
$conn->close();
?>
