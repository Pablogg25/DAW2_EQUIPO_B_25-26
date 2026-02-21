import apiController from "./ApiController";

const $calendarioController = (function () {
  // -------------------------------------------------------
  // GET /eventos  (con filtros opcionales)
  // -------------------------------------------------------
  async function getCalendarios(filtros = {}) {
    let requestUrl = apiController.getBaseUrl() + "/eventos";

    const query = [];

    if (filtros.usuarioId)
      query.push("usuarioId=" + encodeURIComponent(filtros.usuarioId));

    if (filtros.empleadoId)
      query.push("empleadoId=" + encodeURIComponent(filtros.empleadoId));

    if (filtros.trabajoId)
      query.push("trabajoId=" + encodeURIComponent(filtros.trabajoId));

    if (query.length > 0) {
      requestUrl += "?" + query.join("&");
    }

    try {
      const request = await fetch(requestUrl);
      const contentType = request.headers.get("Content-Type") || "";

      let respuesta = {};
      if (contentType.includes("application/json")) {
        respuesta = await request.json();
      }

      if (request.ok) {
        return { success: true, status: request.status, data: respuesta.data };
      }

      // 404 → No hay eventos (caso normal)
      if (request.status === 404) {
        return {
          success: false,
          status: 404,
          message: respuesta.message || "No hay eventos",
        };
      }

      return {
        success: false,
        status: request.status,
        message: respuesta.message || "Error desconocido",
      };
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
    const requestUrl = apiController.getBaseUrl() + "/eventos/" + id;

    try {
      const request = await fetch(requestUrl);
      const respuesta = await request.json();

      if (request.ok) {
        return { success: true, status: 200, data: respuesta.data };
      }

      return {
        success: false,
        status: request.status,
        message: respuesta.message,
      };
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
    const requestUrl = apiController.getBaseUrl() + "/eventos";

    try {
      const request = await fetch(requestUrl, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(obj),
      });

      const respuesta = await request.json();

      if (request.status === 201) {
        return { success: true, status: 201, data: respuesta.data };
      }

      return {
        success: false,
        status: request.status,
        message: respuesta.message,
      };
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
    const requestUrl = apiController.getBaseUrl() + "/eventos/" + id;

    try {
      const request = await fetch(requestUrl, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(obj),
      });

      const respuesta = await request.json();

      if (request.ok) {
        return { success: true, status: 200, data: respuesta.data };
      }

      return {
        success: false,
        status: request.status,
        message: respuesta.message,
      };
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
    const requestUrl = apiController.getBaseUrl() + "/eventos/" + id;

    try {
      const request = await fetch(requestUrl, {
        method: "DELETE",
      });

      const respuesta = await request.json();

      if (request.ok) {
        return { success: true, status: 200, message: respuesta.message };
      }

      return {
        success: false,
        status: request.status,
        message: respuesta.message,
      };
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
