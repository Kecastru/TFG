<?php
// Inicia sesión para poder acceder a variables de sesión
session_start();

// Valida que se haya recibido un parámetro 'id' en la URL y que sea numérico
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Error: ID de colección no válido.');
}

// Convierte el parámetro 'id' a entero para mayor seguridad
$idColeccion = intval($_GET['id']);

// Obtiene mensaje de éxito o error almacenado en la sesión, si existe
$mensaje = $_SESSION['registro_mensaje'] ?? null;

// Elimina el mensaje de la sesión para que no se repita
unset($_SESSION['registro_mensaje']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Añadir Funko POP</title>
    <!-- Vincula hoja de estilos CSS -->
    <link rel="stylesheet" href="styles/styles.css" />
</head>
<body>
<header>
    <div class="banner">
        <div class="logo">
            <img src="images/logo.png" alt="logo" />
        </div>
        <br><br>
        <!-- Botones de navegación -->
        <div class="btnmenu">
            <button id="volvermenu">Volver al menú</button>
            <button id="otrascolecciones">Otras Colecciones</button>
            <button id="listadeseos">Lista de deseos</button>
            <button id="logoutBtn">Cerrar sesión</button>
        </div>
    </div>
</header>

<!-- Contenedor principal con formulario para añadir un Funko POP -->
<div id="contenedor-principal">
    <form 
        method="POST" 
        class="addfunko" 
        action="../backend/addfunko.php" 
        enctype="multipart/form-data"
    >
        <!-- Envia el ID de la colección ocultamente -->
        <input type="hidden" name="idcoleccion" value="<?php echo htmlspecialchars($idColeccion); ?>" />

        <!-- Campo para el nombre del Funko -->
        <label for="nombrefunko">Nombre de Funko POP</label>
        <input type="text" name="nombrefunko" required />
        <br><br>

        <!-- Campo para el número del Funko -->
        <label for="numerofunko">Número de Funko POP</label>
        <input type="number" name="numerofunko" required />
        <br><br>

        <!-- Subida de imagen del Funko -->
        <label for="imagenfunko">Imagen del Funko POP</label>
        <input type="file" name="imagen" required />
        <br><br>

        <!-- Botón para enviar el formulario -->
        <button type="submit">Añadir Funko POP</button>
        <br><br>

        <!-- Botón para volver a la vista de la colección -->
        <button 
            type="button" 
            onclick="window.location.href='ver_collection.php?id=<?php echo htmlspecialchars($idColeccion); ?>'"
        >
            Volver a la Colección
        </button>
    </form>
</div>

<!-- Scripts -->
<script src="scripts/scriptbtn.js"></script>

<!-- Muestra un mensaje emergente si existe uno en la sesión -->
<?php if ($mensaje): ?>
<script>
    alert("<?php echo htmlspecialchars($mensaje); ?>");
</script>
<?php endif; ?>
</body>
</html>
