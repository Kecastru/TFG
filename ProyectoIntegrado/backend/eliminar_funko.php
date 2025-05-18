<?php
session_start(); // Inicia la sesión para acceder a variables de sesión
include 'conexiondb.php'; // Incluye la conexión a la base de datos

// Verifica si el usuario está autenticado
if (!isset($_SESSION['username_id'])) {
    // Si no está autenticado, responde con código 401 y un mensaje
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'No autenticado']);
    exit;
}

// Verifica que la solicitud sea por método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Si no es POST, responde con código 405 y un mensaje
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Obtiene el ID del usuario desde la sesión
$user_id = $_SESSION['username_id'];

// Obtiene los IDs del Funko y de la colección desde POST, asegurándose de que sean enteros
$idFunko = intval($_POST['idfunkopop'] ?? 0);
$idColeccion = intval($_POST['idcoleccion'] ?? 0);

// Verifica que el Funko pertenezca a la colección del usuario
$stmt = $conn->prepare("
    SELECT f.idfunkopop FROM funkopop f
    INNER JOIN colecciones c ON f.idcoleccion = c.idcolecciones
    WHERE f.idfunkopop = ? AND c.idcolecciones = ? AND c.id_usuario = ?
");
$stmt->bind_param("iii", $idFunko, $idColeccion, $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Si no existe el Funko en esa colección del usuario, no permite eliminarlo
if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'No tienes permiso para eliminar este Funko']);
    exit;
}

// Prepara y ejecuta la sentencia para eliminar el Funko
$stmtDel = $conn->prepare("DELETE FROM funkopop WHERE idfunkopop = ?");
$stmtDel->bind_param("i", $idFunko);
$stmtDel->execute();

// Confirma la eliminación
echo json_encode(['success' => true, 'message' => 'Funko eliminado correctamente']);
?>













