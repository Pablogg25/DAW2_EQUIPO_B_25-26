import React, { useEffect, useState, useContext } from "react";
import { useNavigate, useParams } from "react-router-dom";
import $calendarioController from "../../core/CalendaryController";
import { AuthContext } from "../../context/AuthContext";

function CalendarFormPage() {
  const { id } = useParams();
  const navigate = useNavigate();
  const { usuario } = useContext(AuthContext);

  const rol = usuario?.rol;
  const usuarioId = usuario?.usuarioId;

  const [evento, setEvento] = useState({
    titulo: "",
    descripcion: "",
    fecha_inicio: "",
    fecha_fin: "",
    empleadoId: rol === "empleado" ? usuarioId : null,
    trabajoId: null,
  });

  useEffect(() => {
    async function cargar() {
      if (id === "new") return;

      const respuesta = await $calendarioController.getCalendario(id);

      if (!respuesta.success) {
        alert("Error al cargar evento");
        navigate("/calendar");
        return;
      }

      const data = respuesta.data;

      if (rol === "cliente") {
        alert("No tienes permisos para editar eventos.");
        navigate("/calendar");
        return;
      }

      if (rol === "empleado" && data.empleadoId !== usuarioId) {
        alert("Solo puedes editar tus propios eventos.");
        navigate("/calendar");
        return;
      }

      setEvento({
        titulo: data.titulo ?? "",
        descripcion: data.descripcion ?? "",
        fecha_inicio: data.fecha_inicio ?? "",
        fecha_fin: data.fecha_fin ?? "",
        empleadoId: data.empleadoId ?? null,
        trabajoId: data.trabajoId ?? null,
      });
    }

    cargar();
  }, [id, navigate, rol, usuarioId]);

  function handleChange(e) {
    const { name, value } = e.target;

    setEvento({
      ...evento,
      [name]: value === "" ? null : value,
    });
  }

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
    <div className="container mt-4 page-fade">
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

      {(rol === "admin" || rol === "empleado") && (
        <button className="btn btn-success" onClick={guardar}>
          Guardar
        </button>
      )}

      <button className="btn btn-secondary ms-2" onClick={() => navigate(-1)}>
        Volver
      </button>
    </div>
  );
}

export default CalendarFormPage;
