import React, { useEffect, useState, useContext } from "react";
import FullCalendar from "@fullcalendar/react";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import esLocale from "@fullcalendar/core/locales/es";
import $calendarioController from "../../core/CalendaryController";
import { useNavigate } from "react-router-dom";
import { AuthContext } from "../../context/AuthContext";

import { useMessage } from "../../components/UseMessage";
import { useConfirm } from "../../components/useConfirm";

function CalendarPage() {
  const [eventos, setEventos] = useState([]);
  const [eventosDia, setEventosDia] = useState([]);
  const [fechaSeleccionada, setFechaSeleccionada] = useState(null);
  const [mesVisible, setMesVisible] = useState(null);

  const navigate = useNavigate();
  const { usuario } = useContext(AuthContext);

  const { showMessage } = useMessage();
  const { confirm } = useConfirm();

  const rol = usuario?.rol;
  const usuarioId = usuario?.usuarioId;

  // -------------------------------------------------------
  // Cargar eventos
  // -------------------------------------------------------
  async function cargarEventos() {
    try {
      const respuesta = await $calendarioController.getCalendarios();

      if (!respuesta.success) {
        showMessage(respuesta.message || "Error al cargar eventos.", "error");
        setEventos([]);
        return;
      }

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
    } catch (error) {
      showMessage("No se pudo conectar con el servidor.", "error");
      setEventos([]);
    }
  }

  useEffect(() => {
    cargarEventos();
  }, []);

  // -------------------------------------------------------
  // Click en un día
  // -------------------------------------------------------
  function handleDateClick(info) {
    const fecha = new Date(info.dateStr);
    setFechaSeleccionada(info.dateStr);

    const filtrados = eventos.filter((ev) => {
      const fechaEvento = new Date(ev.start);
      return (
        fechaEvento.getFullYear() === fecha.getFullYear() &&
        fechaEvento.getMonth() === fecha.getMonth() &&
        fechaEvento.getDate() === fecha.getDate()
      );
    });

    setEventosDia(filtrados);
  }

  // -------------------------------------------------------
  // Eliminar evento
  // -------------------------------------------------------
  async function eliminarEvento(ev) {
    if (rol !== "admin") {
      showMessage("Solo el administrador puede eliminar eventos.", "warning");
      return;
    }

    const seguro = await confirm("¿Seguro que quieres eliminar este evento?");
    if (!seguro) return;

    try {
      const respuesta = await $calendarioController.deleteCalendario(ev.id);

      if (respuesta.success) {
        showMessage("Evento eliminado correctamente", "success");
        cargarEventos();
        setEventosDia(eventosDia.filter((e) => e.id !== ev.id));
      } else {
        showMessage(
          respuesta.message || "Error al eliminar. Código: " + respuesta.status,
          "error",
        );
      }
    } catch (error) {
      showMessage("No se pudo conectar con el servidor.", "error");
    }
  }

  // -------------------------------------------------------
  // Eventos del mes visible
  // -------------------------------------------------------
  const eventosMes = eventos.filter((ev) => {
    if (!mesVisible) return false;
    const fecha = new Date(ev.start);
    return (
      fecha.getFullYear() === mesVisible.year &&
      fecha.getMonth() + 1 === mesVisible.month
    );
  });

  return (
    <div className="container mt-4 page-fade">
      <h2>Calendario</h2>

      {(rol === "admin" || rol === "empleado") && (
        <button
          className="btn btn-success mb-3"
          onClick={() => navigate("/calendar/new")}
        >
          Crear evento
        </button>
      )}

      <div className="d-flex gap-3">
        {/* CALENDARIO */}
        <div style={{ flex: 2 }}>
          <FullCalendar
            plugins={[dayGridPlugin, interactionPlugin]}
            initialView="dayGridMonth"
            locale={esLocale}
            events={eventos}
            dateClick={handleDateClick}
            datesSet={(info) => {
              const fecha = info.view.currentStart;
              setMesVisible({
                year: fecha.getFullYear(),
                month: fecha.getMonth() + 1,
              });
            }}
            height="80vh"
          />
        </div>

        {/* LISTA DE EVENTOS */}
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
          <h4>Eventos</h4>

          {/* SIN FECHA SELECCIONADA → MOSTRAR MES */}
          {!fechaSeleccionada && (
            <>
              <p className="text-muted">Eventos del mes actual:</p>

              {eventosMes.length === 0 && (
                <p className="text-muted">No hay eventos este mes.</p>
              )}

              {eventosMes.map((ev) => (
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

                  {rol === "admin" && (
                    <button
                      className="btn btn-sm btn-danger"
                      onClick={() => eliminarEvento(ev)}
                    >
                      Eliminar
                    </button>
                  )}
                </div>
              ))}
            </>
          )}

          {/* CON FECHA SELECCIONADA → MOSTRAR DÍA */}
          {fechaSeleccionada && (
            <>
              <p className="text-muted">Eventos del día {fechaSeleccionada}:</p>

              {eventosDia.length === 0 && (
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

                  {rol === "admin" && (
                    <button
                      className="btn btn-sm btn-danger"
                      onClick={() => eliminarEvento(ev)}
                    >
                      Eliminar
                    </button>
                  )}
                </div>
              ))}
            </>
          )}
        </div>
      </div>
    </div>
  );
}

export default CalendarPage;
