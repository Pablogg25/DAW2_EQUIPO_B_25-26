import React, { useEffect, useState } from "react";
import $inventarioController from "../../core/InventoryController.js";
import { useNavigate } from "react-router-dom";

function InventaryPage() {
  const [inventario, setInventario] = useState([]);
  const navigate = useNavigate();

  async function cargarInventario() {
    const respuesta = await $inventarioController.obtenerInventario();

    if (respuesta.success) {
      setInventario(respuesta.data);
    } else {
      console.error("Error al cargar inventario:", respuesta);
      alert("Error al cargar inventario. Código: " + respuesta.status);
    }
  }

  useEffect(() => {
    cargarInventario();
  }, []);

  // Eliminar con confirm
  async function eliminarItem(id) {
    const seguro = window.confirm(
      "¿Seguro que quieres eliminar este elemento?",
    );
    if (!seguro) return;

    const respuesta = await $inventarioController.eliminarItemInventario(id);

    if (respuesta.success) {
      alert("Elemento eliminado correctamente");
      cargarInventario();
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

      <div className="d-flex gap-2 mb-3">
        <button className="btn btn-secondary btn-sm">Volver</button>
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

        {/* Filas dinámicas */}
        {inventario.map((item) => (
          <div className="fila" key={item.itemId}>
            <div className="col">{item.nombre}</div>
            <div className="col">{item.cantidad}</div>
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
        ))}
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

        @media (max-width: 768px) {
          .fila {
            grid-template-columns: 1fr;
            height: auto;
            padding: 12px;
          }

          .cabecera {
            display: none;
          }

          .col {
            white-space: normal;
            text-overflow: initial;
            overflow: visible;
            padding: 6px 0;
          }

          .fila .col::before {
            content: attr(data-label);
            font-weight: bold;
            display: block;
            margin-bottom: 2px;
            color: #555;
          }
        }
      `}</style>
    </div>
  );
}

export default InventaryPage;
