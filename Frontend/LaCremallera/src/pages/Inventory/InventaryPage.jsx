import React, { useEffect, useState } from "react";
import $inventarioController from "../../core/InventoryController.js";
import { useNavigate } from "react-router-dom";

function InventaryPage() {
  const [inventario, setInventario] = useState([]);
  const navigate = useNavigate();

  // Cargar inventario al iniciar
  useEffect(() => {
    async function cargarInventario() {
      const lista = await $inventarioController.obtenerInventario();
      setInventario(lista);
    }

    cargarInventario();
  }, []);

  // Eliminar con confirm
  async function eliminarItem(id) {
    const seguro = window.confirm(
      "¿Seguro que quieres eliminar este elemento?",
    );

    if (!seguro) return;

    await $inventarioController.eliminarItemInventario(id);

    // Recargar inventario
    const lista = await $inventarioController.obtenerInventario();
    setInventario(lista);
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
