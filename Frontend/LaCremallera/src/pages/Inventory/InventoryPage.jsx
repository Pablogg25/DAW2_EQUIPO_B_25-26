import React, { useEffect, useState } from "react";
import $inventarioController from "../../core/InventoryController.js";
import { useNavigate } from "react-router-dom";

function InventaryPage() {
  const [inventario, setInventario] = useState([]);
  const [busqueda, setBusqueda] = useState("");
  const navigate = useNavigate();

  // -------------------------------------------------------
  // Cargar inventario (con o sin búsqueda)
  // -------------------------------------------------------
  async function cargarInventario(nombre = "") {
    const respuesta = await $inventarioController.obtenerInventario({ nombre });

    if (respuesta.success) {
      setInventario(respuesta.data);
      return;
    }

    if (respuesta.status === 404) {
      setInventario([]);
      return;
    }

    console.error("Error al cargar inventario:", respuesta);
    alert("Error al cargar inventario. Código: " + respuesta.status);
  }

  useEffect(() => {
    cargarInventario();
  }, []);

  // -------------------------------------------------------
  // Buscar por nombre
  // -------------------------------------------------------
  function handleBuscar(e) {
    const valor = e.target.value;
    setBusqueda(valor);
    cargarInventario(valor);
  }

  // -------------------------------------------------------
  // Eliminar item
  // -------------------------------------------------------
  async function eliminarItem(id) {
    const seguro = window.confirm(
      "¿Seguro que quieres eliminar este elemento?",
    );
    if (!seguro) return;

    const respuesta = await $inventarioController.eliminarItemInventario(id);

    if (respuesta.success) {
      alert("Elemento eliminado correctamente");
      cargarInventario(busqueda);
    } else {
      if (respuesta.status === 409) {
        alert(
          "Error 409: No se puede eliminar porque está asociado a un trabajo.",
        );
      } else if (respuesta.status === 404) {
        alert("Error 404: El elemento no existe.");
      } else {
        alert("Error al eliminar. Código: " + respuesta.status);
      }
    }
  }

  return (
    <div className="container mt-4">
      <h2 className="mb-3">Inventario</h2>

      {/* Buscador */}
      <input
        type="text"
        className="form-control mb-3"
        placeholder="Buscar por nombre..."
        value={busqueda}
        onChange={handleBuscar}
      />

      <div className="d-flex gap-2 mb-3">
        <button
          className="btn btn-secondary btn-sm"
          onClick={() => navigate(-1)}
        >
          Volver
        </button>

        <button className="btn btn-primary btn-sm">Realizar pedido</button>

        <button
          className="btn btn-success btn-sm"
          onClick={() => navigate("/inventory/new")}
        >
          Crear
        </button>
      </div>

      <div className="tabla-div">
        {/* Cabecera */}
        <div className="fila cabecera">
          <div className="col">Nombre</div>
          <div className="col">Cantidad</div>
          <div className="col">Stock Mínimo</div>
          <div className="col descripcion-col">Descripción</div>
          <div className="col">Acciones</div>
        </div>

        {/* Si no hay resultados */}
        {inventario.length === 0 && (
          <div className="p-3 text-center text-muted">
            No se ha encontrado ningún elemento del inventario con ese nombre.
          </div>
        )}

        {/* Filas dinámicas */}
        {inventario.map((item) => {
          const bajoStock = item.cantidad <= item.stock_minimo;

          return (
            <div
              className={`fila ${bajoStock ? "bajo-stock" : ""}`}
              key={item.itemId}
            >
              <div className="col">{item.nombre}</div>

              <div className="col">
                {item.cantidad}
                {bajoStock && (
                  <span className="badge bg-danger ms-2">Bajo</span>
                )}
              </div>

              <div className="col">{item.stock_minimo}</div>

              <div className="col descripcion-col">
                <span className="descripcion-texto">{item.descripcion}</span>
              </div>

              <div className="col acciones">
                <button
                  onClick={() => navigate(`/inventory/${item.itemId}`)}
                  className="btn btn-outline-secondary btn-sm me-1"
                >
                  Ver
                </button>

                <button
                  className="btn btn-outline-danger btn-sm"
                  onClick={() => eliminarItem(item.itemId)}
                >
                  Eliminar
                </button>
              </div>
            </div>
          );
        })}
      </div>

      {/* Estilos */}
      <style>{`
        .tabla-div {
          display: flex;
          flex-direction: column;
          border: 1px solid #ccc;
          border-radius: 6px;
          overflow: hidden;
        }

        .fila {
          display: grid;
          grid-template-columns: 2fr 1fr 1fr 3fr 1fr;
          padding: 10px;
          border-bottom: 1px solid #ddd;
          align-items: center;
          height: 48px;
        }

        .cabecera {
          background: #f5f5f5;
          font-weight: bold;
        }

        .fila:last-child {
          border-bottom: none;
        }

        .bajo-stock {
          background-color: #ffe5e5;
        }

        .col {
          padding: 4px 8px;
          overflow: hidden;
          white-space: nowrap;
          text-overflow: ellipsis;
        }

        .descripcion-col {
          overflow: hidden;
        }

        .descripcion-texto {
          white-space: nowrap;
          overflow: hidden;
          text-overflow: ellipsis;
        }

        .acciones {
          display: flex;
          gap: 6px;
        }
      `}</style>
    </div>
  );
}

export default InventaryPage;
