import apiController from "./ApiController";

const $inventarioController = (function () {
  // -------------------------------------------------------
  // GET /inventario
  // -------------------------------------------------------
  async function obtenerInventario() {
    const requestUrl = apiController.getBaseUrl() + "/inventario";

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
  // GET /inventario/:id
  // -------------------------------------------------------
  async function obtenerItemInventario(id) {
    const requestUrl = apiController.getBaseUrl() + `/inventario/${id}`;

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
  // POST /inventario
  // -------------------------------------------------------
  async function crearItemInventario(item) {
    const requestUrl = apiController.getBaseUrl() + "/inventario";

    try {
      const request = await fetch(requestUrl, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(item),
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
  // PUT /inventario/:id
  // -------------------------------------------------------
  async function actualizarItemInventario(id, item) {
    const requestUrl = apiController.getBaseUrl() + `/inventario/${id}`;

    try {
      const request = await fetch(requestUrl, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(item),
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
  // DELETE /inventario/:id
  // -------------------------------------------------------
  async function eliminarItemInventario(id) {
    const requestUrl = apiController.getBaseUrl() + `/inventario/${id}`;

    try {
      const request = await fetch(requestUrl, {
        method: "DELETE",
      });

      const respuesta = await request.json();

      if (request.status === 200) {
        return { success: true, status: 200, data: respuesta.message };
      }

      // 409 → FOREIGN KEY (asociado a trabajos)
      if (request.status === 409) {
        return { success: false, status: 409, data: respuesta.message };
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
    obtenerInventario,
    obtenerItemInventario,
    crearItemInventario,
    actualizarItemInventario,
    eliminarItemInventario,
  };
})();

export default $inventarioController;
