<?php
// Inicia la sesión para gestionar la autenticación
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['username_id'])) {
    // Si no está autenticado, establecer mensaje y redirigir a login
    $_SESSION['mensaje'] = "Debes iniciar sesión para ver colecciones.";
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Ver Colecciones</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Vincula estilos CSS -->
    <link rel="stylesheet" href="styles/styles.css" />
</head>
<body>
<header>
    <!-- Encabezado con logo y botones de navegación -->
    <div class="banner">
        <div class="logo">
            <img src="images/logo.png" alt="logo" />
        </div>
        <div class="btnmenu">
            <button id="volvermenu">Volver al menú</button>
            <button id="otrascolecciones">Otras Colecciones</button>
            <button id="listadeseos">Lista de deseos</button>
            <button id="logoutBtn">Cerrar sesión</button>
        </div>
    </div>
</header>

<!-- Contenedor principal de la página -->
<div id="contenedor-principal">
    <h2>Ver colecciones de otros usuarios</h2>
    <!-- Contenedor donde se cargarán los botones de colecciones -->
    <div id="contenedor-botones-colecciones"></div>
</div>

<!-- Script para cargar dinámicamente las colecciones de otros usuarios -->
<script>
/**
 * Función para cargar las colecciones de otros usuarios desde el backend
 */
function cargarColeccionesDeOtros() {
    fetch('../backend/verotrascolecciones.php', {
        credentials: 'include' // Envia credenciales de sesión
    })
    .then(res => res.json()) // Parsea la respuesta como JSON
    .then(data => {
        const contenedor = document.getElementById('contenedor-botones-colecciones');
        contenedor.innerHTML = ''; // Limpia el contenido previo

        if (data.error) {
            // Muestra un mensaje de error si existe
            contenedor.innerHTML = `<p>${data.error}</p>`;
        } else if (data.colecciones && data.colecciones.length > 0) {
            // Crea un botón por cada colección
            data.colecciones.forEach(c => {
                const btn = document.createElement('button');
                btn.className = 'coleccion-boton';
                btn.textContent = `${c.nombre_usuario} - ${c.nombre_coleccion}`;
                // Al hacer clic, redirige a la vista de esa colección
                btn.onclick = () => {
                    window.location.href = `ver_collection.php?id=${c.idcolecciones}`;
                };
                contenedor.appendChild(btn);
            });
        } else {
            // Muestra mensaje si no hay colecciones
            contenedor.innerHTML = '<p>No hay colecciones de otros usuarios para mostrar.</p>';
        }
    })
    .catch(err => {
        console.error('Error al cargar las colecciones:', err);
        const contenedor = document.getElementById('contenedor-botones-colecciones');
        contenedor.innerHTML = '<p>Error al obtener las colecciones.</p>';
    });
}

// Ejecuta la carga cuando la página termine de cargar
window.onload = cargarColeccionesDeOtros;
</script>

<!-- Scripts -->
<script src="scripts/scriptbtn.js"></script>
<script src="scripts/scriptcollection.js"></script>
</body>
</html>
