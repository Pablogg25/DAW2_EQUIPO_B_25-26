import { useState, useEffect } from "react";
import $ordersController from "../../core/TestController/TestOrdersController";
import "./OrdersPage.css";

function OrdersPage() {

  const [orders, setOrders] = useState([]);

  async function cargarDatos() {
    let datos = await $ordersController.getOrders();

    if (datos) {
      console.log("DATOS RECIVIDOS");
      setOrders(datos);

    } else {
      console.log("ERROR: un error inesperado surgió al cargar datos");
    }
  };

  useEffect(() => {
    cargarDatos();
  }, []);

  return (
    <div>
      <div>OrdersPage (trabajos)</div>

      <div>
        {/* lista */}
        <div className="tableRow">
          {/* headers */}
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
        </div>
        {
          orders.map((elemento) => {
            return (
              <div key={elemento["trabajoId"]} className="tableRow">
                <div>{elemento["descripcion"]}</div>
                <div>{elemento["prenda"]}</div>
                <div>{elemento["empleado"]}</div>
                <div>{elemento["fecha_inicio"]}</div>
                <div>{elemento["fecha_entrega"]}</div>
                <div>{elemento["precio"]}</div>
                <div>{elemento["estado"]}</div>
              </div>
            );
          })
        }
      </div>
    </div>
      

  );
}

export default OrdersPage;
