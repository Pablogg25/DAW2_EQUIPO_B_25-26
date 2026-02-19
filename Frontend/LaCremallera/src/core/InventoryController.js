import apiController from "./ApiController";

const $inventarioController = (function () {
  async function obtenerInventario() {
    const response = await fetch(apiController.getBaseUrl() + "/inventario");
    return await response.json(); // ← React espera esto
  }

  async function obtenerItemInventario(id) {
    const response = await fetch(
      apiController.getBaseUrl() + `/inventario/${id}`,
    );
    return await response.json();
  }

  async function eliminarItemInventario(id) {
    const response = await fetch(
      apiController.getBaseUrl() + `/inventario/${id}`,
      {
        method: "DELETE",
      },
    );
    return await response.json();
  }

  return {
    obtenerInventario,
    obtenerItemInventario,
    eliminarItemInventario,
  };
})();

export default $inventarioController;
