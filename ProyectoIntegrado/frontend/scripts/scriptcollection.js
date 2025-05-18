document.addEventListener('DOMContentLoaded', function() {
    const mostrarColeccionesBtn = document.getElementById('mostrarColeccionesBtn');
    if (mostrarColeccionesBtn) {
        mostrarColeccionesBtn.addEventListener('click', cargarSeccionCrearColeccion);
    }

    cargarColeccionesExistentes();
});

const getElem = (id) => {
    const el = document.getElementById(id);
    if (!el) console.error(`No se encontró el elemento con ID "${id}".`);
    return el;
};

const limpiarContenedor = () => {
    const cont = getElem('contenedor-principal');
    if (cont) cont.innerHTML = '';
    return cont;
};

function cargarSeccionCrearColeccion() {
    const cont = limpiarContenedor();
    if (!cont) return;

    cont.appendChild(document.createElement('h2')).textContent = 'Your Funko Pops collections';

    const contenedorColecciones = document.createElement('div');
    contenedorColecciones.id = 'contenedor-botones-colecciones';
    cont.appendChild(contenedorColecciones);

    cargarColeccionesExistentes(() => {
        const btnCrear = document.createElement('button');
        btnCrear.id = 'crear-coleccion-btn';
        btnCrear.textContent = 'Crear nueva colección';
        btnCrear.onclick = mostrarFormularioNuevaColeccion;
        btnCrear.className = 'coleccion-boton crear-nueva-coleccion-boton';
        contenedorColecciones.appendChild(btnCrear);
    });
}

function mostrarFormularioNuevaColeccion() {
    const cont = getElem('contenedor-principal');
    if (!cont) return;

    cont.insertAdjacentHTML('beforeend', `
        <div id="form-nueva-coleccion">
            <h3>Añadir nueva colección</h3>
            <form id="nuevaColeccionForm">
                <label for="nombre_coleccion">Nombre de la colección:</label>
                <input type="text" id="nombre_coleccion" name="nombre_coleccion" required>
                <button type="submit">Añadir Colección</button>
            </form>
            <div id="mensaje-coleccion"></div>
        </div>
    `);

    document.getElementById('nuevaColeccionForm').onsubmit = (e) => {
        e.preventDefault();
        agregarNuevaColeccion();
    };
}

const manejarRespuestaJson = (response) => {
    if (!response.ok) throw new Error(`HTTP ${response.status}`);
    return response.json();
};

function cargarColeccionesExistentes(callback) {
    fetch('../backend/Tucollection.php')
        .then(manejarRespuestaJson)
        .then(data => {
            const contenedor = getElem('contenedor-botones-colecciones');
            if (!contenedor) return;
            contenedor.innerHTML = '';

            if (data.error) {
                contenedor.innerHTML = `<p>${data.error}</p>`;
            } else if (data.colecciones && data.colecciones.length > 0) {
                data.colecciones.forEach(c => {
                    const btn = document.createElement('button');
                    btn.className = 'coleccion-boton';
                    btn.textContent = c.nombre;
                    btn.onclick = () => {
                        window.location.href = `ver_collection.php?id=${c.idcolecciones}`;
                    };
                    contenedor.appendChild(btn);
                });
            }

            if (callback) callback();

            // Mostrar mensaje solo si no hay colecciones
            if (!data.colecciones || data.colecciones.length === 0) {
                const mensaje = document.createElement('p');
                mensaje.textContent = 'Aún no has creado ninguna colección.';
                mensaje.className = 'mensaje-sin-colecciones';
                contenedor.appendChild(mensaje); 
            }
        })
        .catch(err => {
            console.error('Error al cargar las colecciones:', err);
            const contenedor = getElem('contenedor-botones-colecciones');
            if (contenedor) contenedor.innerHTML = '<p>Error al cargar tus colecciones.</p>';
            if (callback) callback();
        });
}

function agregarNuevaColeccion() {
    const nombreInput = document.getElementById('nombre_coleccion');
    const mensajeDiv = document.getElementById('mensaje-coleccion');
    const nombre = nombreInput.value.trim();

    if (!nombre) {
        mensajeDiv.textContent = 'Por favor, introduce un nombre para la colección.';
        return;
    }

    fetch('../backend/Tucollection.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `nombre_coleccion=${encodeURIComponent(nombre)}`
    })
    .then(manejarRespuestaJson)
    .then(data => {
        if (data.success) {
            mensajeDiv.textContent = data.message;
            nombreInput.value = '';
            cargarColeccionesExistentes(() => {
                eliminarFormulario();
                crearBotonNuevaColeccion();
            });
        } else if (data.error) {
            mensajeDiv.textContent = data.error;
        }
    })
    .catch(() => {
        mensajeDiv.textContent = 'Error al guardar la colección.';
    });
}

function eliminarFormulario() {
    const formulario = document.getElementById('form-nueva-coleccion');
    if (formulario) formulario.remove();
}

function crearBotonNuevaColeccion() {
    const contenedor = document.getElementById('contenedor-botones-colecciones');
    if (contenedor && !document.getElementById('crear-coleccion-btn')) {
        const boton = document.createElement('button');
        boton.id = 'crear-coleccion-btn';
        boton.textContent = 'Crear nueva colección';
        boton.onclick = mostrarFormularioNuevaColeccion;
        boton.className = 'coleccion-boton crear-nueva-coleccion-boton';
        contenedor.appendChild(boton);
    }
}

