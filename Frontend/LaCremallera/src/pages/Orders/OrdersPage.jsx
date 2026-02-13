import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
// import $ordersController from "../../core/TestController/TestOrdersController";
import $ordersController from "../../core/OrdersController";
import "./OrdersPage.css";

import $usuariosController from "../../core/TestController/TestUsersController";
import $prendasController from "../../core/TestController/TestPrendasController";

function OrdersPage() {
  const [orders, setOrders] = useState([]);

  const [usuariosData, setUsuarioData] = useState([]);
  const [prendasData, setPrendasData] = useState([]);

  const navegar = useNavigate();

  const cargarDatos = async () => {
    let datos = await $ordersController.getOrders();

    if (datos.success) {
      console.log("DATOS RECIVIDOS");
      setOrders(datos.data);

      //TODO: cargar datos de usuarios y prendas

      setUsuarioData(await $usuariosController.getUsuarios());
      setPrendasData(await $prendasController.getPrendas());

    } else {
      console.log("ERROR: un error inesperado surgió al cargar datos");
      alert("Ha surgido un error al cargar datos. Compruebe logs.");
    }
  };

  const onCreateOrder = () => {
    console.log("on create order");
    navegar("/orders/0");
  }

  const onEditOrder = (orderId) => {
    console.log("OnEditOrder id:" + orderId);
    if (orderId) {
      navegar("/orders/" + orderId);

    }
  }

  const onDeleteOrder = async (orderId) => {
    console.log("OnDeleteOrder: " + orderId);
    //añadir diálogo de confirmación antes de borrar
    if (orderId) {
      if (confirm("¿Desea borrar el trabajo?")) {
        console.log("Eliminando trabajo")
        let response = await $ordersController.deleteOrder(orderId);
        if (response.success) {
          await cargarDatos();

        } else {
          alert("Error, ha surgido un error a la hora de cargar datos");
        }

      }
    }

  }

  function getEmpleadoName(empleadoId) {
    let index = usuariosData.findIndex(p => p.usuarioId == empleadoId);

    if (index !== -1) {
      return usuariosData[index].nombre;
    }
    return "n/a";
  }

  function getPrendaName(prendaId) {
    let index = prendasData.findIndex(p => p.prendaId == prendaId);

    if (index !== -1) {
      return prendasData[index].tipo;
    }
    return "n/a";
  }

  useEffect(() => {
    cargarDatos();
  }, []);

  return (
    <div>
      <div>OrdersPage (trabajos)</div>

      <div>
        <button onClick={() => { onCreateOrder() }}>Crear order</button>
      </div>

      <div>
        {/* lista */}
        <div className="tableRow">
          {/* headers */}
          <div>
            <strong>Id</strong>
          </div>
          <div>
            <strong>Descripción</strong>
          </div>
          <div>
            <strong>Prenda</strong>
          </div>
          <div>
            <strong>Empleado</strong>
          </div>
          <div>
            <strong>Fecha de inicio</strong>
          </div>
          <div>
            <strong>Fecha de entrega</strong>
          </div>
          <div>
            <strong>Precio</strong>
          </div>
          <div>
            <strong>estado</strong>
          </div>
          <div>
            <strong>operaciones</strong>
          </div>
        </div>

        <div>
          {
            orders.map((elemento) => {
              return (
                <div key={elemento["trabajoId"]} className="tableRow">
                  <div>{elemento["trabajoId"]}</div>
                  <div>{elemento["descripcion"]}</div>
                  <div>{getPrendaName(elemento["prendaId"])}</div>
                  <div>{getEmpleadoName(elemento["empleadoId"])}</div>
                  <div>{elemento["fecha_inicio"]}</div>
                  <div>{elemento["fecha_entrega"]}</div>
                  <div>{elemento["precio"]}</div>
                  <div>{elemento["estado"]}</div>
                  <div>
                    <button onClick={() => { onEditOrder(elemento["trabajoId"]) }}>Ver/Editar</button>
                    <button onClick={() => { onDeleteOrder(elemento["trabajoId"]) }}>Eliminar</button>
                  </div>
                </div>
              );
            })
          }
        </div>

      </div>
    </div>


  );
}

export default OrdersPage;
