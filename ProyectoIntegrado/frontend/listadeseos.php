<?php
// Inicia la sesión para acceder a las variables de sesión
session_start();

// Incluye la conexión a la base de datos
include '../backend/conexiondb.php';

// Verifica si el usuario está autenticado
if (!isset($_SESSION['username_id'])) {
    // Si no está autenticado, guarda un mensaje y redirige a login.php
    $_SESSION['mensaje'] = "Inicia sesión para ver la lista de deseos";
    header("Location: login.php");
    exit;
}

// Obtiene el ID del usuario desde la sesión
$id_usuario = $_SESSION['username_id'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Lista de Deseos</title>
    <!-- Incluye estilos CSS para la página -->
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/styles_img.css">
    <link rel="stylesheet" href="styles/styles_tabla.css">
</head>
<body>
<header>
    <div class="banner">
        <!-- Logo de la página -->
        <div class="logo">
            <img src="images/logo.png" alt="logo">
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

<h2>Agregar Funko a la Lista de Deseos</h2>
<div id="contenedor-principal">
    <!-- Formulario para agregar un nuevo Funko a la lista de deseos -->
    <form class="addfunko" action="../backend/addwish.php" method="POST" enctype="multipart/form-data">
        <label for="nombrefunko">Nombre del Funko:</label>
        <input type="text" name="nombrefunko" id="nombrefunko" required><br><br>

        <label for="numerofunko">Número del Funko:</label>
        <input type="number" name="numerofunko" id="numerofunko" required><br><br>

        <label for="imagen">Imagen:</label>
        <input type="file" name="imagen" id="imagen" accept="image/*" required><br><br>

        <button type="submit">Añadir a Lista de Deseos</button>
    </form>
</div>

<h2>Lista de Deseos</h2>
<div class="tabla-funko">
    <!-- Tabla que muestra los Funko en la lista de deseos -->
    <table>
        <tr>
            <th>Nombre</th>
            <th>Número</th>
            <th>Imagen</th>
        </tr>
        <?php
        // Preparar la consulta para obtener los Funko del usuario
        $stmt = $conn->prepare("SELECT nombrefunko, numerofunko, imagen, tipo_imagen FROM lista_deseos WHERE id_usuario = ? ORDER BY idlista_deseos DESC");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        // Itera sobre los resultados y lo muestrar en la tabla
        while ($row = $result->fetch_assoc()):
            // Codifica la imagen en base64 para mostrarla en la página
            $imgData = base64_encode($row['imagen']);
            $src = "data:{$row['tipo_imagen']};base64,{$imgData}";
        ?>
            <tr>
                <td><?= htmlspecialchars($row['nombrefunko']) ?></td>
                <td><?= htmlspecialchars($row['numerofunko']) ?></td>
                <td><img src="<?= $src ?>" width="100" /></td>
            </tr>
        <?php endwhile;
        // Cierra la consulta y la conexión
        $stmt->close();
        $conn->close();
        ?>
    </table>
</div>

<!--scripts -->
<script src="scripts/scriptbtn.js"></script>

<!-- Modal para ampliar la imagen del Funko -->
<div id="imagenModal">
    <span id="cerrarModal">&times;</span>
    <img id="imagenAmpliada" src="" alt="Funko ampliado">
</div>
</body>
</html>
