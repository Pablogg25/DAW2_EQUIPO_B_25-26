import React, { useEffect, useState } from "react";
import apiController from "../core/ApiController";
function InventaryPage() {
  const [inventario, setInventario] = useState([]);
  useEffect(() => {
    async function cargarInventario() {
      const lista = await apiController.obtenerInventario();
      console.log(lista);
      setInventario(lista);
    }
    cargarInventario();
  }, []);
  return (
    <div className="container mt-4">
      <h2 className="mb-3">Inventario</h2>

      {/* Botones superiores */}
      <div className="d-flex gap-2 mb-3">
        <button className="btn btn-secondary btn-sm">Volver</button>
        <button className="btn btn-primary btn-sm">Realizar pedido</button>
        <button className="btn btn-success btn-sm">Crear</button>
      </div>

      {/* Tabla */}
      <table className="table table-bordered table-sm">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Tipo</th>
            <th>Cantidad</th>
            <th>Medida</th>
            <th>Descripción</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Botón metálico</td>
            <td>Accesorio</td>
            <td>120</td>
            <td>Unidades</td>
            <td>Botón plateado</td>
            <td>
              <button className="btn btn-outline-secondary btn-sm me-1">
                Editar
              </button>
              <button className="btn btn-outline-danger btn-sm">
                Eliminar
              </button>
            </td>
          </tr>

          <tr>
            <td>Cremallera negra</td>
            <td>Cierre</td>
            <td>45</td>
            <td>Metros</td>
            <td>Cremallera resistente</td>
            <td>
              <button className="btn btn-outline-secondary btn-sm me-1">
                Editar
              </button>
              <button className="btn btn-outline-danger btn-sm">
                Eliminar
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  );
}

export default InventaryPage;
