document.addEventListener('DOMContentLoaded', function() {
    // Asignar evento al botón de cerrar sesión
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', () => {
            window.location.href = '../backend/logout.php';
        });
    }

   
    const logoutBtn2 = document.getElementById('logoutBtn2');
    if (logoutBtn2) {
        logoutBtn2.addEventListener('click', () => {
            window.location.href = 'logout.php';
        });
    }


    // Asignar evento al botón de registrarse en index.php
    const registrarseBtn = document.getElementById('btnRegistrarse');
    if (registrarseBtn) {
        registrarseBtn.addEventListener('click', () => {
            window.location.href = '../backend/registro.php';
        });
    }

    //Vuelve a index.php desde el registro de usuario
    const backIndex = document.getElementById('backindex');
    if (backIndex) {
        backIndex.addEventListener('click', () => {
            console.log('Botón Volver a Index clickeado');
            window.location.href = '../frontend/index.php';
        });
    };

    const iraddfunko = document.getElementById('buttoniradd');
    if (iraddfunko){
        iraddfunko.addEventListener('click', () => {
            console.log('Botón ir añadir funkopop');
            window.location.href = '../backend/añadirfunko.php'; 
        })
    }
});

// Redirige a menu.php
    const volvermenuBtn = document.getElementById('volvermenu');
    if (volvermenuBtn) {
        volvermenuBtn.addEventListener('click', () => {
            console.log('Botón Volver a Menú clickeado');
            window.location.href = 'menu.php';
        });
    }

// Redirige a menu.php
    const volvermenuBtn2 = document.getElementById('volvermenu2');
    if (volvermenuBtn2) {
        volvermenuBtn2.addEventListener('click', () => {
            console.log('Botón Volver a Menú clickeado');
            window.location.href = 'frontend/menu.php';
        });
    }
    const otrascoleccionesir = document.getElementById('otrascolecciones');
    if (otrascoleccionesir) {
        otrascoleccionesir.addEventListener('click', () => {
            console.log('Botón Volver a Menú clickeado');
            window.location.href = 'colecciones.php';
        });
    }
    const irlistadeseos = document.getElementById('listadeseos');
    if (irlistadeseos) {
        irlistadeseos.addEventListener('click', () => {
            console.log('Botón Volver a Menú clickeado');
            window.location.href = 'listadeseos.php';
        });
    }

    const otrascoleccionesir2 = document.getElementById('otrascolecciones2');
    if (otrascoleccionesir2) {
        otrascoleccionesir2.addEventListener('click', () => {
            console.log('Botón Volver a Menú clickeado');
            window.location.href = 'frontend/colecciones.php';
        });
    }
    const irlistadeseos2 = document.getElementById('listadeseos2');
    if (irlistadeseos2) {
        irlistadeseos2.addEventListener('click', () => {
            console.log('Botón Volver a Menú clickeado');
            window.location.href = 'frontend/listadeseos.php';
        });
    }


    document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("imagenModal");
    const imgAmpliada = document.getElementById("imagenAmpliada");
    const cerrarBtn = document.getElementById("cerrarModal");

    // Abrir el modal al hacer clic en una imagen
    document.querySelectorAll(".tabla-funko img").forEach(img => {
        img.addEventListener("click", function () {
            imgAmpliada.src = this.src;
            imgAmpliada.alt = this.alt;
            modal.style.display = "block";
        });
    });

    // Cerrar modal al hacer clic en la X
    cerrarBtn.addEventListener("click", function () {
        modal.style.display = "none";
        imgAmpliada.src = ""; // limpiar imagen
    });

    // Cerrar modal al hacer clic fuera de la imagen
    window.addEventListener("click", function (e) {
        if (e.target === modal) {
            modal.style.display = "none";
            imgAmpliada.src = "";
        }
    });
});

