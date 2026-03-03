import apiController from "./ApiController";

const $inventarioController = (function () {
  // -------------------------------------------------------
  // GET /inventario  (con filtro opcional por nombre)
  // -------------------------------------------------------
  async function obtenerInventario(authToken, params = {}) {
    let requestUrl = apiController.getBaseUrl() + "/inventario";

    if (params.nombre && params.nombre.trim() !== "") {
      requestUrl += "?nombre=" + encodeURIComponent(params.nombre);
    }

    try {
      const requestBody = {
        method:"GET",
        headers: {
          "Content-type": "application/json; charset=UTF-8",
          "Authorization": `Bearer ${authToken}`,
        },
      };
      const request = await fetch(requestUrl, requestBody);
      const contentType = request.headers.get("Content-Type") || "";

      let respuesta = {};
      if (contentType.includes("application/json")) {
        respuesta = await request.json();
      }

      if (request.ok) {
        return { success: true, status: request.status, data: respuesta.data };
      }

      if (request.status === 404) {
        return {
          success: false,
          status: 404,
          message: respuesta.message || "No se encontraron resultados",
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
  // GET /inventario/:id
  // -------------------------------------------------------
  async function obtenerItemInventario(authToken,id) {
    const requestUrl = apiController.getBaseUrl() + `/inventario/${id}`;

    try {
      const requestBody = {
        headers: {
          "Content-type": "application/json; charset=UTF-8",
          "Authorization": "Bearer " + authToken,
        },
      };
      const request = await fetch(requestUrl,requestBody);
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
  // GET /inventario/bajo-stock
  // -------------------------------------------------------
  async function obtenerBajoStock(authToken) {
    const requestUrl = apiController.getBaseUrl() + "/inventario/bajo-stock";

    try {
      const requestBody = {
        headers: {
          "Content-type": "application/json; charset=UTF-8",
          "Authorization": "Bearer " + authToken,
        },
      };
      const request = await fetch(requestUrl,requestBody);
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
  // POST /inventario
  // -------------------------------------------------------
  async function crearItemInventario(authToken,item) {
    const requestUrl = apiController.getBaseUrl() + "/inventario";

    try {
      const request = await fetch(requestUrl, {
        method: "POST",
        headers: { "Content-Type": "application/json","Authorization": "Bearer " + authToken, },
        body: JSON.stringify(item),
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
  // PUT /inventario/:id
  // -------------------------------------------------------
  async function actualizarItemInventario(authToken,id, item) {
    const requestUrl = apiController.getBaseUrl() + `/inventario/${id}`;

    try {
      const request = await fetch(requestUrl, {
        method: "PUT",
        headers: { "Content-Type": "application/json","Authorization": "Bearer " + authToken, },
        body: JSON.stringify(item),
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
  // DELETE /inventario/:id
  // -------------------------------------------------------
  async function eliminarItemInventario(authToken,id) {
    const requestUrl = apiController.getBaseUrl() + `/inventario/${id}`;

    try {
      const request = await fetch(requestUrl, {
        method: "DELETE",
        headers: { "Content-Type": "application/json","Authorization": "Bearer " + authToken, },
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
    obtenerInventario,
    obtenerItemInventario,
    obtenerBajoStock,
    crearItemInventario,
    actualizarItemInventario,
    eliminarItemInventario,
  };
})();

export default $inventarioController;
