import React, { useEffect, useState, useContext } from "react";
import { useNavigate, useParams } from "react-router-dom";
import $calendarioController from "../../core/CalendaryController";
import { AuthContext } from "../../context/AuthContext";

import { useMessage } from "../../components/useMessage";
import { useConfirm } from "../../components/useConfirm";

function CalendarFormPage() {
  const { id } = useParams();
  const navigate = useNavigate();
  const { usuario } = useContext(AuthContext);

  const { showMessage } = useMessage();
  const { confirm } = useConfirm();

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

  // -------------------------------------------------------
  // Cargar datos si NO es creación
  // -------------------------------------------------------
  useEffect(() => {
    async function cargar() {
      if (id === "new") return;

      try {
        const respuesta = await $calendarioController.getCalendario(id);

        if (!respuesta.success) {
          showMessage(respuesta.message || "Error al cargar evento.", "error");
          navigate("/calendar");
          return;
        }

        const data = respuesta.data;

        if (rol === "cliente") {
          showMessage("No tienes permisos para editar eventos.", "warning");
          navigate("/calendar");
          return;
        }

        if (rol === "empleado" && data.empleadoId !== usuarioId) {
          showMessage("Solo puedes editar tus propios eventos.", "warning");
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
      } catch (error) {
        showMessage("No se pudo conectar con el servidor.", "error");
        navigate("/calendar");
      }
    }

    cargar();
  }, [id, navigate, rol, usuarioId, showMessage]);

  // -------------------------------------------------------
  // Manejar cambios
  // -------------------------------------------------------
  function handleChange(e) {
    const { name, value } = e.target;

    setEvento({
      ...evento,
      [name]: value === "" ? null : value,
    });
  }

  // -------------------------------------------------------
  // Guardar (crear o actualizar) con VALIDACIONES
  // -------------------------------------------------------
  async function guardar() {
    // VALIDACIONES DE CAMPOS
    if (!evento.titulo.trim()) {
      showMessage("El título es obligatorio.", "warning");
      return;
    }

    if (!evento.fecha_inicio) {
      showMessage("Debes indicar la fecha y hora de inicio.", "warning");
      return;
    }

    if (!evento.fecha_fin) {
      showMessage("Debes indicar la fecha y hora de fin.", "warning");
      return;
    }

    const inicio = new Date(evento.fecha_inicio);
    const fin = new Date(evento.fecha_fin);

    if (isNaN(inicio.getTime())) {
      showMessage("La fecha de inicio no es válida.", "warning");
      return;
    }

    if (isNaN(fin.getTime())) {
      showMessage("La fecha de fin no es válida.", "warning");
      return;
    }

    if (fin < inicio) {
      showMessage(
        "La fecha de fin no puede ser anterior a la de inicio.",
        "warning",
      );
      return;
    }

    // PETICIÓN A LA API
    let respuesta;

    try {
      if (id === "new") {
        if (rol === "cliente") {
          showMessage("No tienes permisos para crear eventos.", "warning");
          return;
        }

        respuesta = await $calendarioController.createCalendario(evento);
      } else {
        if (rol === "cliente") {
          showMessage("No tienes permisos para editar eventos.", "warning");
          return;
        }

        if (rol === "empleado" && evento.empleadoId !== usuarioId) {
          showMessage("Solo puedes editar tus propios eventos.", "warning");
          return;
        }

        respuesta = await $calendarioController.updateCalendario(id, evento);
      }

      if (respuesta.success) {
        showMessage("Guardado correctamente", "success");
        navigate("/calendar");
      } else {
        showMessage(
          respuesta.message || "Error al guardar. Código: " + respuesta.status,
          "error",
        );
      }
    } catch (error) {
      showMessage("No se pudo conectar con el servidor.", "error");
    }
  }

  return (
    <div className="container mt-4 page-fade">
      <h2>{id === "new" ? "Crear evento" : "Editar evento"}</h2>

      <label>Título *</label>
      <input
        type="text"
        name="titulo"
        value={evento.titulo}
        onChange={handleChange}
        className="form-control mb-2"
      />

      <label>Fecha inicio *</label>
      <input
        type="datetime-local"
        name="fecha_inicio"
        value={evento.fecha_inicio}
        onChange={handleChange}
        className="form-control mb-2"
      />

      <label>Fecha fin *</label>
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
