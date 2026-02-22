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
      if (id === "new") return;

      const respuesta = await $inventarioController.obtenerItemInventario(id);

      if (respuesta.success) {
        setItem(respuesta.data);
      } else {
        alert("Error al cargar el item. Código: " + respuesta.status);
        navigate("/inventory");
      }
    }

    cargarItem();
  }, [id, navigate]);

  // Manejar cambios en inputs
  function handleChange(e) {
    const { name, value } = e.target;
    setItem({ ...item, [name]: value });
  }

  // Guardar cambios (crear o actualizar)
  async function guardar() {
    if (!item.nombre.trim()) {
      alert("El nombre es obligatorio");
      return;
    }

    const datos = {
      ...item,
      cantidad: Number(item.cantidad),
      stock_minimo: Number(item.stock_minimo),
    };

    let respuesta;

    if (id === "new") {
      respuesta = await $inventarioController.crearItemInventario(datos);
    } else {
      respuesta = await $inventarioController.actualizarItemInventario(
        id,
        datos,
      );
    }

    if (respuesta.success) {
      alert("Guardado correctamente");
      navigate("/inventory");
    } else {
      alert("Error al guardar. Código: " + respuesta.status);
    }
  }

  return (
    <div className="container mt-4 page-fade">
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
