import React, { useEffect, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import $calendarioController from "../../core/CalendaryController";

function CalendarFormPage() {
  const { id } = useParams();
  const navigate = useNavigate();

  const [evento, setEvento] = useState({
    titulo: "",
    descripcion: "",
    fecha_inicio: "",
    fecha_fin: "",
    usuarioId: 1,
    empleadoId: "",
    trabajoId: "",
  });

  // -------------------------------------------------------
  // Cargar evento si estamos editando
  // -------------------------------------------------------
  useEffect(() => {
    async function cargar() {
      if (id === "new") return;

      const respuesta = await $calendarioController.getCalendario(id);

      if (respuesta.success) {
        const data = respuesta.data;

        setEvento({
          titulo: data.titulo ?? "",
          descripcion: data.descripcion ?? "",
          fecha_inicio: data.fecha_inicio ?? "",
          fecha_fin: data.fecha_fin ?? "",
          usuarioId: data.usuarioId ?? 1,
          empleadoId: data.empleadoId ?? "",
          trabajoId: data.trabajoId ?? "",
        });
      } else {
        alert("Error al cargar evento");
        navigate("/calendar");
      }
    }

    cargar();
  }, [id, navigate]);

  // -------------------------------------------------------
  // Manejar cambios
  // -------------------------------------------------------
  function handleChange(e) {
    const { name, value } = e.target;
    setEvento({ ...evento, [name]: value });
  }

  // -------------------------------------------------------
  // Guardar
  // -------------------------------------------------------
  async function guardar() {
    if (!evento.titulo.trim()) {
      alert("El título es obligatorio");
      return;
    }

    let respuesta;

    if (id === "new") {
      respuesta = await $calendarioController.createCalendario(evento);
    } else {
      respuesta = await $calendarioController.updateCalendario(id, evento);
    }

    if (respuesta.success) {
      alert("Guardado correctamente");
      navigate("/calendar");
    } else {
      alert("Error al guardar. Código: " + respuesta.status);
    }
  }

  return (
    <div className="container mt-4">
      <h2>{id === "new" ? "Crear evento" : "Editar evento"}</h2>

      <label>Título</label>
      <input
        type="text"
        name="titulo"
        value={evento.titulo}
        onChange={handleChange}
        className="form-control mb-2"
      />

      <label>Fecha inicio</label>
      <input
        type="datetime-local"
        name="fecha_inicio"
        value={evento.fecha_inicio}
        onChange={handleChange}
        className="form-control mb-2"
      />

      <label>Fecha fin</label>
      <input
        type="datetime-local"
        name="fecha_fin"
        value={evento.fecha_fin}
        onChange={handleChange}
        className="form-control mb-2"
      />

      <label>Descripción</label>
      <textarea
        name="descripcion"
        value={evento.descripcion}
        onChange={handleChange}
        className="form-control mb-3"
      />

      <button className="btn btn-success" onClick={guardar}>
        Guardar
      </button>

      <button className="btn btn-secondary ms-2" onClick={() => navigate(-1)}>
        Volver
      </button>
    </div>
  );
}

export default CalendarFormPage;
