<?php
// Inicia sesión y verifica si el usuario está autenticado
session_start();

// Incluye la conexión a la base de datos
include '../backend/conexiondb.php';

// Si el usuario no está autenticado, redirigir a la página de inicio de sesión
if (!isset($_SESSION['username_id'])) {
    header('Location: index.php');
    exit;
}

// Obtiene el ID del usuario desde la sesión
$user_id = $_SESSION['username_id'];

// Obtiene el ID de la colección desde los parámetros GET
$idColeccion = $_GET['id'] ?? null;

if (!$idColeccion) {
    die('ID de colección no especificado.');
}

// Prepara y ejecuta consulta para obtener datos de la colección
$stmtCol = $conn->prepare("SELECT nombre, id_usuario FROM colecciones WHERE idcolecciones = ?");
$stmtCol->bind_param("i", $idColeccion);
$stmtCol->execute();
$resultCol = $stmtCol->get_result();

// Verifica si la colección existe
if ($resultCol->num_rows === 0) {
    die('Colección no encontrada.');
}

// Obtiene datos de la colección
$coleccion = $resultCol->fetch_assoc();

// Prepara y ejecuta consulta para obtener los Funkos de la colección
$stmtFunkos = $conn->prepare("SELECT idfunkopop, nombrefunko, numerofunko, tipo_imagen, imagen FROM funkopop WHERE idcoleccion = ?");
$stmtFunkos->bind_param("i", $idColeccion);
$stmtFunkos->execute();
$resultFunkos = $stmtFunkos->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Lista de Deseos</title>
    <!-- Enlaces a archivos CSS para estilos -->
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/styles_img.css">
    <link rel="stylesheet" href="styles/styles_tabla.css">
</head>
<body>
<header>
    <div class="banner">
        <div class="logo">
            <img src="images/logo.png" alt="logo">
        </div>
        <!-- Botones de navegación -->
        <div class="btnmenu">
            <button id="volvermenu">Volver al menú</button>
            <button id="otrascolecciones">Otras Colecciones</button>
            <button id="listadeseos">Lista de deseos</button>
            <button id="logoutBtn">Cerrar sesión</button>
        </div>
    </div>
</header>

<!-- Título de la colección, usando htmlspecialchars para evitar ataques XSS -->
<h2>Colección: <?= htmlspecialchars($coleccion['nombre']) ?></h2>

<!-- Contenedor principal para los botones de acción -->
<div id="contenedor-principal">
    <!-- Muestra el botón "Añadir Funkopop" solo si el usuario actual es el dueño de la colección -->
    <?php if ($user_id == $coleccion['id_usuario']): ?>
        <button class="ir_registro" onclick="window.location.href='registrofunko.php?id=<?= $idColeccion ?>'">
            Añadir Funkopop
        </button>
    <?php endif; ?>
</div>

<!-- Mensaje oculto que puede usarse para mostrar notificaciones o confirmaciones -->
<div id="mensaje-eliminado" style="display:none;" class="alerta-mensaje"></div>

<!-- Si no hay funkos en la colección, muestra mensaje -->
<?php if ($resultFunkos->num_rows === 0): ?>
    <p>No hay Funkos en esta colección.</p>
<?php else: ?>
    <!-- Tabla para listar los funkos -->
    <div class="tabla-funko">
        <table border="1">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Número</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Itera sobre cada funko en el resultado -->
                <?php while ($funko = $resultFunkos->fetch_assoc()): 
                    // Convierte la imagen a base64 para mostrar en línea
                    $imgSrc = "data:{$funko['tipo_imagen']};base64," . base64_encode($funko['imagen']);
                ?>
                <tr data-funko="<?= $funko['idfunkopop'] ?>">
                    <td><?= htmlspecialchars($funko['nombrefunko']) ?></td>
                    <td><?= htmlspecialchars($funko['numerofunko']) ?></td>
                    <td><img src="<?= $imgSrc ?>" alt="Funko" width="50"></td>
                    <td>
                        <!-- Muestra el botón eliminar solo si el usuario es el dueño -->
                        <?php if ($user_id == $coleccion['id_usuario']): ?>
                            <button class="btn-eliminar-funko"
                                    data-id="<?= $funko['idfunkopop'] ?>"
                                    data-coleccion="<?= $idColeccion ?>">
                                Eliminar
                            </button>
                        <?php else: ?>
                            <em>Sin permisos</em>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<!-- Modal para ampliar la imagen del Funko -->
<div id="imagenModal">
    <span id="cerrarModal">&times;</span>
    <img id="imagenAmpliada" src="" alt="Funko ampliado">
</div>
<!--scripts -->
<script src="scripts/scriptbtn.js"></script>
<script src="scripts/scriptcollection.js"></script>
<script src="scripts/eliminar_funko.js"></script> 
</body>
</html>
