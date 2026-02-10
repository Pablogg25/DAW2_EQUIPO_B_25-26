const apiController = (function () {
  const api_base_url = "http://127.0.0.1:8000/api";

  let getBaseUrl = function () {
    return api_base_url;
  };

  async function obtenerInventario() {
    const response = await fetch(api_base_url + `/inventario`);
    if (!response.ok) {
      throw new Error(
        "Error al obtener los elementos del inventario " + response.status,
      );
    }
    return await response.json();
  }

  return {
    getBaseUrl,
    obtenerInventario,
  };
})();

export default apiController;
