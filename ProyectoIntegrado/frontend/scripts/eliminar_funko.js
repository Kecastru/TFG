document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".btn-eliminar-funko").forEach(btn => {
        btn.addEventListener("click", function () {
            if (!confirm("¿Seguro que quieres eliminar este Funko?")) return;

            const id = this.dataset.id;
            const idcoleccion = this.dataset.coleccion;

            fetch("../backend/eliminar_funko.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `idfunkopop=${id}&idcoleccion=${idcoleccion}`
            })
            .then(res => res.json())
            .then(data => {
                const mensaje = document.getElementById("mensaje-eliminado");
                mensaje.textContent = data.mensaje;
                mensaje.style.display = "block";
                setTimeout(() => mensaje.style.display = "none", 3000);

                if (data.success) {
                    document.querySelector(`tr[data-funko="${id}"]`).remove();
                }
            })
            .catch(() => alert("Error al intentar eliminar el Funko."));
        });
    });
});















