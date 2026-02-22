import { useState, useEffect, useContext } from "react";
import { useNavigate } from "react-router-dom";

import $ordersController from "../../core/OrdersController";
import $usersController from "../../core/UsersController";
import $prendasController from "../../core/PrendasController";
// import { AuthContext } from "../../context/AuthProvider";
import { AuthContext } from "../../context/AuthContext";

function OrdersPage() {
  const [orders, setOrders] = useState([]);

  const [usuariosData, setUsuarioData] = useState([]);
  const [prendasData, setPrendasData] = useState([]);

  const navegar = useNavigate();
  const { usuario } = useContext(AuthContext);

  const cargarDatos = async () => {
    let datos;

    if (usuario.rol != "admin") {
      datos = await $ordersController.getOrders({
        empleadoId: usuario.usuarioId,
      });
    } else {
      datos = await $ordersController.getOrders();
    }

    if (datos.success) {
      console.log("DATOS RECIVIDOS");
      setOrders(datos.data);

      //TODO: cargar datos de usuarios y prendas

      if (usuariosData.length == 0) {
        console.log("Cargando datos de usuario");
        let datosUsuario = await $usersController.getUsers([]);
        setUsuarioData(datosUsuario.data);
      }
      if (prendasData.length == 0) {
        console.log("Cargando datos de prendas");
        let prendas = await $prendasController.getPrendas([]);
        setPrendasData(prendas.data);
      }
    } else {
      console.log("ERROR: un error inesperado surgió al cargar datos");
      alert("Ha surgido un error al cargar datos. Compruebe logs.");
    }
  };

  const onCreateOrder = () => {
    console.log("on create order");
    navegar("/orders/0");
  };

  const onEditOrder = (orderId) => {
    console.log("OnEditOrder id:" + orderId);
    if (orderId) {
      navegar("/orders/" + orderId);
    }
  };

  const onDeleteOrder = async (orderId) => {
    console.log("OnDeleteOrder: " + orderId);
    //añadir diálogo de confirmación antes de borrar
    if (orderId) {
      if (confirm("¿Desea borrar el trabajo?")) {
        console.log("Eliminando trabajo");
        let response = await $ordersController.deleteOrder(orderId);
        if (response.success) {
          await cargarDatos();
        } else {
          if (response.estado == 409) {
            alert(
              "Error 409: No se puede eliminar el trabajo debido a que depende de otro elemento",
            );
          } else {
            alert(
              "Error, ha surgido un error al procesar su petición.\nCodigo de error: " +
                response.estado,
            );
          }
        }
      }
    }
  };

  function getEmpleadoName(empleadoId) {
    let index = usuariosData.findIndex((p) => p.usuarioId == empleadoId);

    if (index !== -1) {
      return usuariosData[index].nombre;
    }
    return "n/a";
  }

  function getPrendaName(prendaId) {
    let index = prendasData.findIndex((p) => p.prendaId == prendaId);

    if (index !== -1) {
      return prendasData[index].tipo;
    }
    return "n/a";
  }

  useEffect(() => {
    cargarDatos();
  }, []);

  return (
    <div className="container mt-4 page-fade">
      <h2 className="mb-3">Trabajos</h2>

      <button className="btn btn-success mb-3" onClick={() => onCreateOrder()}>
        Crear trabajo
      </button>

      <div className="tabla-div">
        {/* CABECERA */}
        <div className="fila cabecera cols-9">
          <div className="col">
            <strong>Id</strong>
          </div>
          <div className="col">
            <strong>Descripción</strong>
          </div>
          <div className="col">
            <strong>Prenda</strong>
          </div>
          <div className="col">
            <strong>Empleado</strong>
          </div>
          <div className="col">
            <strong>Fecha inicio</strong>
          </div>
          <div className="col">
            <strong>Fecha entrega</strong>
          </div>
          <div className="col">
            <strong>Precio</strong>
          </div>
          <div className="col">
            <strong>Estado</strong>
          </div>
          <div className="col">
            <strong>Operaciones</strong>
          </div>
        </div>

        {/* FILAS */}
        {orders.map((elemento) => (
          <div key={elemento["trabajoId"]} className="fila cols-9">
            <div className="col">{elemento["trabajoId"]}</div>
            <div className="col">{elemento["descripcion"]}</div>
            <div className="col">{getPrendaName(elemento["prendaId"])}</div>
            <div className="col">{getEmpleadoName(elemento["empleadoId"])}</div>
            <div className="col">{elemento["fecha_inicio"]}</div>
            <div className="col">{elemento["fecha_entrega"]}</div>
            <div className="col">{elemento["precio"]}</div>
            <div className="col">{elemento["estado"]}</div>

            <div className="col acciones">
              <button
                className="btn-edit"
                onClick={() => onEditOrder(elemento["trabajoId"])}
              >
                Ver/Editar
              </button>

              <button
                className="btn-delete"
                onClick={() => onDeleteOrder(elemento["trabajoId"])}
              >
                Eliminar
              </button>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}

export default OrdersPage;
