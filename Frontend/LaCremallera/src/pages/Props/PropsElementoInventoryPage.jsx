import React, { useEffect, useState } from "react";
import { useParams, useNavigate } from "react-router-dom";
import $inventarioController from "../../core/InventoryController.js";

function PropsElementoInventoryPage() {
  const { id } = useParams();
  const navigate = useNavigate();

  const [item, setItem] = useState({
    nombre: "",
    cantidad: 0,
    stock_minimo: 0,
    descripcion: "",
  });

  // Cargar datos si NO es creación
  useEffect(() => {
    async function cargarItem() {
      if (id === "new") return; // modo creación → no cargar nada

      const datos = await $inventarioController.obtenerItemInventario(id);
      setItem(datos);
    }

    cargarItem();
  }, [id]);

  // Manejar cambios en inputs
  function handleChange(e) {
    const { name, value } = e.target;
    setItem({ ...item, [name]: value });
  }

  // Guardar cambios (crear o actualizar)
  async function guardar() {
    if (id === "new") {
      await $inventarioController.crearItemInventario(item);
    } else {
      await $inventarioController.actualizarItemInventario(id, item);
    }

    navigate("/inventory"); // volver al inventario
  }

  return (
    <div className="container mt-4">
      <h2>{id === "new" ? "Crear Item" : "Editar Item"}</h2>

      <label>Nombre</label>
      <input
        type="text"
        name="nombre"
        value={item.nombre}
        onChange={handleChange}
        className="form-control mb-2"
      />

      <label>Cantidad</label>
      <input
        type="number"
        name="cantidad"
        value={item.cantidad}
        onChange={handleChange}
        className="form-control mb-2"
      />

      <label>Stock mínimo</label>
      <input
        type="number"
        name="stock_minimo"
        value={item.stock_minimo}
        onChange={handleChange}
        className="form-control mb-2"
      />

      <label>Descripción</label>
      <textarea
        name="descripcion"
        value={item.descripcion}
        onChange={handleChange}
        className="form-control mb-3"
      />

      <button className="btn btn-success" onClick={guardar}>
        Guardar
      </button>

      <button className="btn btn-secondary ms-2" onClick={() => navigate(-1)}>
        Volver
      </button>
    </div>
  );
}

export default PropsElementoInventoryPage;
