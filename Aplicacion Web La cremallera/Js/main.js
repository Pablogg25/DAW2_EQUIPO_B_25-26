
window.addEventListener("load", function () {

    const panel = document.getElementById("panelInfo");
    const botones = document.querySelectorAll(".menu button");

    botones.forEach(btn => {
        btn.addEventListener("click", () => {
            const seccion = btn.dataset.seccion;
            mostrarInfo(seccion, panel);
        });
    });

    const logo = document.querySelector(".logo-cremallera img");
    logo.src = LaCremallera.logo;
    logo.alt = LaCremallera.nombreEmpresa;

    const btnRegistrarse = document.getElementById("registrarse");

    btnRegistrarse.addEventListener("click", function () {
        window.location.href = "/Paginas/Registrar.html";
    });

});

function mostrarInfo(seccion, panel) {

    let contenido = "";

    switch (seccion) {

        case "empresa":
            contenido = `
            <h2>${LaCremallera.nombreEmpresa}</h2>
            <p>${LaCremallera.detalle}</p>
            <p>${LaCremallera.categoria}</p>
            <p>${LaCremallera.descripcion}</p>
        `;
            break;
        case "horarios":
            contenido = `
            <p><strong>Horarios:</strong></p>
            <p><strong>Lunes:</strong> ${LaCremallera.horarios.lunes}</p>
            <p><strong>Martes:</strong> ${LaCremallera.horarios.martes}</p>
            <p><strong>Miercoles:</strong> ${LaCremallera.horarios.miercoles}</p>
            <p><strong>Jueves:</strong> ${LaCremallera.horarios.jueves}</p>
            <p><strong>Viernes:</strong> ${LaCremallera.horarios.viernes}</p>
            <p><strong>Sabado:</strong> ${LaCremallera.horarios.sabado}</p>
            <p><strong>Domingo:</strong> ${LaCremallera.horarios.domingo}</p>
            
        `;
            break;
        case "contacto":
            contenido = `
            <p><strong>Dirección:</strong> ${LaCremallera.direccion.calle}, ${LaCremallera.direccion.numero}, ${LaCremallera.direccion.municipio}, ${LaCremallera.direccion.provincia} - ${LaCremallera.direccion.pais} </p>
            <p><strong>Teléfono:</strong> ${LaCremallera.telefono}</p>
            <p><strong>Email:</strong> ${LaCremallera.correoElectronico}</p>
        `;
            break;
        default:
            contenido = `<p>Sección no encontrada</p>`;
    }

    panel.innerHTML = contenido;
    panel.classList.add("activo");

}