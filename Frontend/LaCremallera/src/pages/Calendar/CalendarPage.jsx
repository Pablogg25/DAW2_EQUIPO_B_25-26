import React, { useEffect, useState, useContext } from "react";
import FullCalendar from "@fullcalendar/react";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import esLocale from "@fullcalendar/core/locales/es";
import $calendarioController from "../../core/CalendaryController";
import { useNavigate } from "react-router-dom";
import { AuthContext } from "../../context/AuthContext";

function CalendarPage() {
  const [eventos, setEventos] = useState([]);
  const [eventosDia, setEventosDia] = useState([]);
  const [fechaSeleccionada, setFechaSeleccionada] = useState(null);
  const navigate = useNavigate();
  const { usuario } = useContext(AuthContext);

  const rol = usuario?.rol;
  const usuarioId = usuario?.usuarioId;

  async function cargarEventos() {
    const respuesta = await $calendarioController.getCalendarios();

    if (!respuesta.success && respuesta.status === 404) {
      setEventos([]);
      return;
    }

    if (respuesta.success) {
      const eventosAdaptados = respuesta.data.map((ev) => ({
        id: ev.eventoId,
        title: ev.titulo,
        start: ev.fecha_inicio,
        end: ev.fecha_fin,
        extendedProps: {
          descripcion: ev.descripcion,
          usuarioId: ev.usuarioId,
          empleadoId: ev.empleadoId,
          trabajoId: ev.trabajoId,
        },
      }));

      setEventos(eventosAdaptados);
    } else {
      setEventos([]);
    }
  }

  useEffect(() => {
    cargarEventos();
  }, []);

  function handleDateClick(info) {
    const fecha = info.dateStr;
    setFechaSeleccionada(fecha);

    const filtrados = eventos.filter((ev) => {
      const soloFecha = ev.start.split("T")[0];
      return soloFecha === fecha;
    });

    setEventosDia(filtrados);
  }

  async function eliminarEvento(ev) {
    if (rol === "cliente") {
      alert("No tienes permisos para eliminar eventos.");
      return;
    }

    if (rol === "empleado" && ev.extendedProps.empleadoId !== usuarioId) {
      alert("Solo puedes eliminar tus propios eventos.");
      return;
    }

    const seguro = window.confirm("¿Seguro que quieres eliminar este evento?");
    if (!seguro) return;

    const respuesta = await $calendarioController.deleteCalendario(ev.id);

    if (respuesta.success) {
      alert("Evento eliminado");
      cargarEventos();
      setEventosDia(eventosDia.filter((e) => e.id !== ev.id));
    } else {
      alert("Error al eliminar. Código: " + respuesta.status);
    }
  }

  return (
    <div className="container mt-4 page-fade">
      <h2>Calendario</h2>

      <button
        className="btn btn-success mb-3"
        onClick={() => navigate("/calendar/new")}
      >
        Crear evento
      </button>

      <div className="d-flex gap-3">
        <div style={{ flex: 2 }}>
          <FullCalendar
            plugins={[dayGridPlugin, interactionPlugin]}
            initialView="dayGridMonth"
            locale={esLocale}
            events={eventos}
            dateClick={handleDateClick}
            height="80vh"
          />
        </div>

        <div
          style={{
            flex: 1,
            border: "1px solid #ccc",
            borderRadius: "6px",
            padding: "10px",
            height: "80vh",
            overflowY: "auto",
          }}
        >
          <h4>Eventos del día</h4>

          {!fechaSeleccionada && (
            <p className="text-muted">
              Haz clic en un día para ver sus eventos.
            </p>
          )}

          {fechaSeleccionada && eventosDia.length === 0 && (
            <p className="text-muted">No hay eventos para este día.</p>
          )}

          {eventosDia.map((ev) => (
            <div
              key={ev.id}
              className="border rounded p-2 mb-2"
              style={{ background: "#f9f9f9" }}
            >
              <h5 className="mb-1">{ev.title}</h5>
              <p className="mb-1">{ev.extendedProps.descripcion}</p>

              {(rol === "admin" ||
                (rol === "empleado" &&
                  ev.extendedProps.empleadoId === usuarioId)) && (
                <button
                  className="btn btn-sm btn-primary me-2"
                  onClick={() => navigate(`/calendar/${ev.id}`)}
                >
                  Editar
                </button>
              )}

              {(rol === "admin" ||
                (rol === "empleado" &&
                  ev.extendedProps.empleadoId === usuarioId)) && (
                <button
                  className="btn btn-sm btn-danger"
                  onClick={() => eliminarEvento(ev)}
                >
                  Eliminar
                </button>
              )}
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}

export default CalendarPage;
