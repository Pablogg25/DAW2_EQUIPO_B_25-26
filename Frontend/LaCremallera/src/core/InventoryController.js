import apiController from "./ApiController";

const $inventarioController = (function () {
  // -------------------------------------------------------
  // Obtener token automáticamente desde sessionStorage
  // -------------------------------------------------------
  function getToken() {
    const t = sessionStorage.getItem("authToken");
    return t && t !== "null" ? t : null;
  }

  // -------------------------------------------------------
  // Construir headers con token
  // -------------------------------------------------------
  function buildHeaders() {
    return {
      "Content-Type": "application/json; charset=UTF-8",
      Authorization: "Bearer " + getToken(),
    };
  }

  // -------------------------------------------------------
  // Procesar respuesta del backend
  // -------------------------------------------------------
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
  // GET /inventario
  // -------------------------------------------------------
  async function obtenerInventario(params = {}) {
    let url = apiController.getBaseUrl() + "/inventario";

    if (params.nombre?.trim()) {
      url += "?nombre=" + encodeURIComponent(params.nombre);
    }

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
  // GET /inventario/:id
  // -------------------------------------------------------
  async function obtenerItemInventario(id) {
    const url = apiController.getBaseUrl() + `/inventario/${id}`;

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
  // GET /inventario/bajo-stock
  // -------------------------------------------------------
  async function obtenerBajoStock() {
    const url = apiController.getBaseUrl() + "/inventario/bajo-stock";

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
  // POST /inventario
  // -------------------------------------------------------
  async function crearItemInventario(item) {
    const url = apiController.getBaseUrl() + "/inventario";

    try {
      const request = await fetch(url, {
        method: "POST",
        headers: buildHeaders(),
        body: JSON.stringify(item),
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
  // PUT /inventario/:id
  // -------------------------------------------------------
  async function actualizarItemInventario(id, item) {
    const url = apiController.getBaseUrl() + `/inventario/${id}`;

    try {
      const request = await fetch(url, {
        method: "PUT",
        headers: buildHeaders(),
        body: JSON.stringify(item),
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
  // DELETE /inventario/:id
  // -------------------------------------------------------
  async function eliminarItemInventario(id) {
    const url = apiController.getBaseUrl() + `/inventario/${id}`;

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
    obtenerInventario,
    obtenerItemInventario,
    obtenerBajoStock,
    crearItemInventario,
    actualizarItemInventario,
    eliminarItemInventario,
  };
})();

export default $inventarioController;
