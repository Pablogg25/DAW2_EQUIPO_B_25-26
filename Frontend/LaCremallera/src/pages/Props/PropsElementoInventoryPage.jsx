import React, { useEffect, useState } from "react";
import { useParams, useNavigate } from "react-router-dom";
import apiController from "../../core/ApiController.js";

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
      try {
        const datos = await apiController.obtenerItemInventario(id);
        console.log(datos);
        setItem(datos);
      } catch (e) {
        console.error(e);
      }
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
    try {
      if (id === "new") {
        await apiController.crearItemInventario(item);
      } else {
        await apiController.actualizarItemInventario(id, item);
      }
      navigate("/"); // vuelve al inicio o donde quieras
    } catch (e) {
      console.error(e);
    }
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
      />

      <label>Cantidad</label>
      <input
        type="number"
        name="cantidad"
        value={item.cantidad}
        onChange={handleChange}
      />

      <label>Stock mínimo</label>
      <input
        type="number"
        name="stock_minimo"
        value={item.stock_minimo}
        onChange={handleChange}
      />

      <label>Descripción</label>
      <textarea
        name="descripcion"
        value={item.descripcion}
        onChange={handleChange}
      />

      <br />

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
