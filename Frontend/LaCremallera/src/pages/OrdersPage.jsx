import { useState, useEffect } from "react";
import ordersController from "../core/OrdersController";

function OrdersPage() {

  const [orders, setOrders] = useState([]);

  async function cargarDatos() {
    let datos = await ordersController.getOrders();

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
    <>
      <div>OrdersPage (trabajos)</div>

      <div>
        {/* lista */}
        {
          orders.map((elemento) => {
            return (
              <div key={elemento["id"]}>{elemento["descripcion"]} - {elemento["empleado"]}</div>
            );
          })
        }
      </div>
    </>

  );
}

export default OrdersPage;
