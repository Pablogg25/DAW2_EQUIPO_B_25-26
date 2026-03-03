import apiController from "./ApiController";

const $calendarioController = (function () {
  function getToken() {
    const t = sessionStorage.getItem("authToken");
    return t && t !== "null" ? t : null;
  }

  function buildHeaders() {
    return {
      "Content-Type": "application/json; charset=UTF-8",
      Authorization: "Bearer " + getToken(),
    };
  }

  async function procesarRespuesta(request) {
    let respuesta = {};

    try {
      respuesta = await request.json();
    } catch {
      respuesta = { message: "Respuesta no válida del servidor" };
    }

    if (request.ok) {
      return {
        success: true,
        status: request.status,
        data: respuesta.data ?? null,
        message: respuesta.message ?? null,
      };
    }

    return {
      success: false,
      status: request.status,
      message: respuesta.message ?? "Error desconocido",
      detalle: respuesta.detalle ?? null,
    };
  }

  // -------------------------------------------------------
  // GET /eventos
  // -------------------------------------------------------
  async function getCalendarios() {
    const url = apiController.getBaseUrl() + "/eventos";

    try {
      const request = await fetch(url, {
        method: "GET",
        headers: buildHeaders(),
      });

      return procesarRespuesta(request);
    } catch (e) {
      return {
        success: false,
        status: 500,
        message: "Error de red",
        detalle: e,
      };
    }
  }

  // -------------------------------------------------------
  // GET /eventos/:id
  // -------------------------------------------------------
  async function getCalendario(id) {
    const url = apiController.getBaseUrl() + "/eventos/" + id;

    try {
      const request = await fetch(url, {
        method: "GET",
        headers: buildHeaders(),
      });

      return procesarRespuesta(request);
    } catch (e) {
      return {
        success: false,
        status: 500,
        message: "Error de red",
        detalle: e,
      };
    }
  }

  // -------------------------------------------------------
  // POST /eventos
  // -------------------------------------------------------
  async function createCalendario(obj) {
    const url = apiController.getBaseUrl() + "/eventos";

    // El backend solo acepta estos campos:
    const payload = {
      titulo: obj.titulo,
      descripcion: obj.descripcion || null,
      fecha_inicio: obj.fecha_inicio,
      fecha_fin: obj.fecha_fin,
      empleadoId: obj.empleadoId || null,
      trabajoId: obj.trabajoId || null,
    };

    try {
      const request = await fetch(url, {
        method: "POST",
        headers: buildHeaders(),
        body: JSON.stringify(payload),
      });

      return procesarRespuesta(request);
    } catch (e) {
      return {
        success: false,
        status: 500,
        message: "Error de red",
        detalle: e,
      };
    }
  }

  // -------------------------------------------------------
  // PUT /eventos/:id
  // -------------------------------------------------------
  async function updateCalendario(id, obj) {
    const url = apiController.getBaseUrl() + "/eventos/" + id;

    const payload = {
      titulo: obj.titulo,
      descripcion: obj.descripcion || null,
      fecha_inicio: obj.fecha_inicio,
      fecha_fin: obj.fecha_fin,
      empleadoId: obj.empleadoId || null,
      trabajoId: obj.trabajoId || null,
    };

    try {
      const request = await fetch(url, {
        method: "PUT",
        headers: buildHeaders(),
        body: JSON.stringify(payload),
      });

      return procesarRespuesta(request);
    } catch (e) {
      return {
        success: false,
        status: 500,
        message: "Error de red",
        detalle: e,
      };
    }
  }

  // -------------------------------------------------------
  // DELETE /eventos/:id
  // -------------------------------------------------------
  async function deleteCalendario(id) {
    const url = apiController.getBaseUrl() + "/eventos/" + id;

    try {
      const request = await fetch(url, {
        method: "DELETE",
        headers: buildHeaders(),
      });

      return procesarRespuesta(request);
    } catch (e) {
      return {
        success: false,
        status: 500,
        message: "Error de red",
        detalle: e,
      };
    }
  }

  return {
    getCalendarios,
    getCalendario,
    createCalendario,
    updateCalendario,
    deleteCalendario,
  };
})();

export default $calendarioController;
