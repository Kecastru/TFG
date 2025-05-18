<?php
// Inicia la sesión para usar variables de sesión
session_start();

// Verifica si el usuario ha iniciado sesión
if (isset($_SESSION['username_id'])) {
    // Guarda el ID del usuario en una variable para usarla en la página
    $user_id = $_SESSION['username_id'];
} else {
    // Si no hay sesión activa, redirigir a la página de inicio de sesión
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Enlace a la hoja de estilos CSS -->
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
<header>
    <div class="banner">
        <!-- Logo de la página -->
        <div class="logo">
            <img src="images/logo.png" alt="logo">
        </div>
        <br><br>
        <!-- Botones para navegar entre diferentes secciones -->
        <div class="btnmenu">
            <button id="mostrarColeccionesBtn">Tus colecciones</button>
            <button id="otrascolecciones">Otras Colecciones</button>
            <button id="listadeseos">Lista de deseos</button>
            <button id="logoutBtn">Cerrar sesión</button>
        </div>
    </div>
</header>

<!-- Contenedor principal donde se mostrarán las diferentes secciones -->
<div id="contenedor-principal">
    <h2>Hola</h2>
</div>

<!-- Pasa el ID del usuario a JavaScript para uso en scripts -->
<script>
    const userId = <?php echo json_encode($user_id); ?>;
</script>

<!-- Scripts -->
<script src="scripts/scriptbtn.js"></script>
<script src="scripts/scriptcollection.js"></script>
</body>
</html>
