import React, { useEffect, useState } from "react";
import FullCalendar from "@fullcalendar/react";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import esLocale from "@fullcalendar/core/locales/es";
import $calendarioController from "../../core/CalendaryController";
import { useNavigate } from "react-router-dom";

function CalendarPage() {
  const [eventos, setEventos] = useState([]);
  const [eventosDia, setEventosDia] = useState([]);
  const [fechaSeleccionada, setFechaSeleccionada] = useState(null);
  const navigate = useNavigate();

  // -------------------------------------------------------
  // Cargar eventos del backend
  // -------------------------------------------------------
  async function cargarEventos() {
    const respuesta = await $calendarioController.getCalendarios();

    if (respuesta.success) {
      const eventosAdaptados = respuesta.data.map((ev) => ({
        id: ev.eventoId, // ← CORREGIDO
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
      console.warn("No hay eventos o error:", respuesta.message);
      setEventos([]);
    }
  }

  useEffect(() => {
    cargarEventos();
  }, []);

  // -------------------------------------------------------
  // Cuando se hace clic en un día
  // -------------------------------------------------------
  function handleDateClick(info) {
    const fecha = info.dateStr;
    setFechaSeleccionada(fecha);

    const filtrados = eventos.filter((ev) => {
      const soloFecha = ev.start.split(" ")[0];
      return soloFecha === fecha;
    });

    setEventosDia(filtrados);
  }

  // -------------------------------------------------------
  // Eliminar evento
  // -------------------------------------------------------
  async function eliminarEvento(id) {
    const seguro = window.confirm("¿Seguro que quieres eliminar este evento?");
    if (!seguro) return;

    const respuesta = await $calendarioController.deleteCalendario(id);

    if (respuesta.success) {
      alert("Evento eliminado");
      cargarEventos();
      setEventosDia(eventosDia.filter((ev) => ev.id !== id));
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
        {/* Calendario */}
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

        {/* Panel lateral */}
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
              key={ev.id} // ← CORREGIDO
              className="border rounded p-2 mb-2"
              style={{ background: "#f9f9f9" }}
            >
              <h5 className="mb-1">{ev.title}</h5>
              <p className="mb-1">{ev.extendedProps.descripcion}</p>

              <button
                className="btn btn-sm btn-primary me-2"
                onClick={() => navigate(`/calendar/${ev.id}`)} // ← CORREGIDO
              >
                Editar
              </button>

              <button
                className="btn btn-sm btn-danger"
                onClick={() => eliminarEvento(ev.id)}
              >
                Eliminar
              </button>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}

export default CalendarPage;
