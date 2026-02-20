import apiController from "./ApiController";

const $calendarioController = (function () {
  // -------------------------------------------------------
  // GET /calendario  (con filtros opcionales)
  // filtros = { usuarioId: 1, empleadoId: 2, trabajoId: 3 }
  // -------------------------------------------------------
  async function getCalendarios(filtros = {}) {
    let requestUrl = apiController.getBaseUrl() + "/calendario";

    // Construcción manual del querystring (básico)
    let query = [];
    if (filtros.usuarioId) query.push("usuarioId=" + filtros.usuarioId);
    if (filtros.empleadoId) query.push("empleadoId=" + filtros.empleadoId);
    if (filtros.trabajoId) query.push("trabajoId=" + filtros.trabajoId);

    if (query.length > 0) {
      requestUrl += "?" + query.join("&");
    }

    try {
      const request = await fetch(requestUrl);
      const respuesta = await request.json();

      if (request.status === 200) {
        return { success: true, status: 200, data: respuesta.data };
      }

      return {
        success: false,
        status: request.status,
        data: respuesta.message,
      };
    } catch (e) {
      return { success: false, status: 500, data: e };
    }
  }

  // -------------------------------------------------------
  // GET /calendario/:id
  // -------------------------------------------------------
  async function getCalendario(id) {
    const requestUrl = apiController.getBaseUrl() + "/calendario/" + id;

    try {
      const request = await fetch(requestUrl);
      const respuesta = await request.json();

      if (request.status === 200) {
        return { success: true, status: 200, data: respuesta.data };
      }

      return {
        success: false,
        status: request.status,
        data: respuesta.message,
      };
    } catch (e) {
      return { success: false, status: 500, data: e };
    }
  }

  // -------------------------------------------------------
  // POST /calendario
  // -------------------------------------------------------
  async function createCalendario(obj) {
    const requestUrl = apiController.getBaseUrl() + "/calendario";

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
        data: respuesta.message,
      };
    } catch (e) {
      return { success: false, status: 500, data: e };
    }
  }

  // -------------------------------------------------------
  // PUT /calendario/:id
  // -------------------------------------------------------
  async function updateCalendario(id, obj) {
    const requestUrl = apiController.getBaseUrl() + "/calendario/" + id;

    try {
      const request = await fetch(requestUrl, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(obj),
      });

      const respuesta = await request.json();

      if (request.status === 200) {
        return { success: true, status: 200, data: respuesta.data };
      }

      return {
        success: false,
        status: request.status,
        data: respuesta.message,
      };
    } catch (e) {
      return { success: false, status: 500, data: e };
    }
  }

  // -------------------------------------------------------
  // DELETE /calendario/:id
  // -------------------------------------------------------
  async function deleteCalendario(id) {
    const requestUrl = apiController.getBaseUrl() + "/calendario/" + id;

    try {
      const request = await fetch(requestUrl, {
        method: "DELETE",
      });

      const respuesta = await request.json();

      if (request.status === 200) {
        return { success: true, status: 200, data: respuesta.message };
      }

      return {
        success: false,
        status: request.status,
        data: respuesta.message,
      };
    } catch (e) {
      return { success: false, status: 500, data: e };
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
