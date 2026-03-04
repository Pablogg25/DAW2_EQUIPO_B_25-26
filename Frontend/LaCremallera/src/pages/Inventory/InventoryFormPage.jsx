import React, { useEffect, useState, useContext } from "react";
import { useParams, useNavigate } from "react-router-dom";
import $inventarioController from "../../core/InventoryController.js";
import { AuthContext } from "../../context/AuthContext.jsx";

import { useMessage } from "../../components/UseMessage.jsx";
import { useConfirm } from "../../components/useConfirm.jsx";

function PropsElementoInventoryPage() {
  const { id } = useParams();
  const navigate = useNavigate();
  const { usuario } = useContext(AuthContext);

  const { showMessage } = useMessage();
  const { confirm } = useConfirm();

  const rol = usuario?.rol;

  const [item, setItem] = useState({
    nombre: "",
    cantidad: 0,
    stock_minimo: 0,
    descripcion: "",
  });

  // -------------------------------------------------------
  // Cargar datos si NO es creación
  // -------------------------------------------------------
  useEffect(() => {
    async function cargarItem() {
      if (id === "new") {
        if (rol !== "admin") {
          showMessage("No tienes permisos para crear inventario.", "warning");
          navigate("/inventory");
        }
        return;
      }

      try {
        const respuesta = await $inventarioController.obtenerItemInventario(id);

        if (respuesta.success) {
          setItem(respuesta.data);
        } else {
          showMessage(
            respuesta.message ||
              "Error al cargar el item. Código: " + respuesta.status,
            "error",
          );
          navigate("/inventory");
        }
      } catch (error) {
        showMessage("No se pudo conectar con el servidor.", "error");
        navigate("/inventory");
      }
    }

    cargarItem();
  }, [id, navigate, rol, showMessage]);

  // -------------------------------------------------------
  // Manejar cambios
  // -------------------------------------------------------
  function handleChange(e) {
    const { name, value } = e.target;
    setItem({ ...item, [name]: value });
  }

  // -------------------------------------------------------
  // Guardar (crear o actualizar)
  // -------------------------------------------------------
  async function guardar() {
    if (!item.nombre.trim()) {
      showMessage("El nombre es obligatorio", "warning");
      return;
    }

    const datos = {
      ...item,
      cantidad: Number(item.cantidad),
      stock_minimo: Number(item.stock_minimo),
    };

    let respuesta;

    try {
      if (id === "new") {
        if (rol !== "admin") {
          showMessage("No tienes permisos para crear.", "warning");
          return;
        }

        respuesta = await $inventarioController.crearItemInventario(datos);
      } else {
        if (rol !== "admin" && rol !== "empleado") {
          showMessage("No tienes permisos para editar.", "warning");
          return;
        }

        respuesta = await $inventarioController.actualizarItemInventario(
          id,
          datos,
        );
      }

      if (respuesta.success) {
        showMessage("Guardado correctamente", "success");
        navigate("/inventory");
      } else {
        showMessage(
          respuesta.message || "Error al guardar. Código: " + respuesta.status,
          "error",
        );
      }
    } catch (error) {
      showMessage("No se pudo conectar con el servidor.", "error");
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

      {/* Guardar → admin y empleado (crear solo admin) */}
      {(rol === "admin" || rol === "empleado") && (
        <button className="btn btn-success" onClick={guardar}>
          Guardar
        </button>
      )}

      <button className="btn btn-secondary ms-2" onClick={() => navigate(-1)}>
        Volver
      </button>
    </div>
  );
}

export default PropsElementoInventoryPage;
