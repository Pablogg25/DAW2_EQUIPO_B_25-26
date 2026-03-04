import { useState, useEffect, useContext } from "react";
import { useNavigate } from "react-router-dom";

import $ordersController from "../../core/OrdersController";
// import $usersController from "../../core/UsersController";
// import $prendasController from "../../core/PrendasController";
import { AuthContext } from "../../context/AuthContext";

function OrdersPage() {
  const [orders, setOrders] = useState([]);

  const [usuariosData, setUsuarioData] = useState([]);
  const [prendasData, setPrendasData] = useState([]);

  const [busqueda, setBusqueda] = useState({
    estado: "",
    prendaId: -1
  });

  const navegar = useNavigate();
  const { usuario } = useContext(AuthContext);
  const rol = usuario?.rol; // admin | empleado | cliente

  const cargarDatos = async (filtro = {}) => {
    let datos;

    if (usuario.rol != "admin") {
      datos = await $ordersController.getOrders({
        empleadoId: usuario.usuarioId,
        ...filtro
      });
    } else {
      datos = await $ordersController.getOrders(filtro);
    }

    if (datos.success) {
      //console.log("DATOS RECIVIDOS");
      setOrders(datos.data);
    } else {
      //console.log("ERROR: un error inesperado surgió al cargar datos");
      alert("Ha surgido un error al cargar datos. Compruebe logs.");
    }
  };

  //manejar busqueda
  // -------------------------------------------------------
  // Buscar por id
  // -------------------------------------------------------
  function handleBuscarEstado(e) {
    const valor = e.target.value;
    let actualizar = { ...busqueda, ["estado"]: valor }
    setBusqueda(actualizar);
    cargarDatos(actualizar);
  }

  function handleBuscarPrenda(e) {
    const valor = e.target.value;
    let actualizar = { ...busqueda, ["prendaId"]: valor }
    setBusqueda(actualizar);
    cargarDatos(actualizar);
  }

  const onCreateOrder = () => {
    //console.log("on create order");
    navegar("/orders/0");
  };

  const onEditOrder = (orderId) => {
    //console.log("OnEditOrder id:" + orderId);
    if (orderId) {
      navegar("/orders/" + orderId);
    }
  };

  const onDeleteOrder = async (orderId) => {
    //console.log("OnDeleteOrder: " + orderId);

    if (rol !== "admin") {
      alert("No tienes permisos para eliminar.");
      return;
    }

    //añadir diálogo de confirmación antes de borrar
    if (orderId) {
      if (confirm("¿Desea borrar el trabajo?")) {
        //console.log("Eliminando trabajo");
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

  useEffect(() => {
    cargarDatos();
  }, []);

  return (
    <div className="container mt-4 page-fade">
      <h2 className="mb-3">Trabajos</h2>

      {/* Buscador */}
      <div>
        <div>
          Buscar por estado
        </div>

        <select className="form-control mb-3"
          value={busqueda["estado"]}
          onChange={handleBuscarEstado}>
          <option value={""}>n/a</option>
          <option value={"pendiente"}>Pendiente</option>
          <option value={"en_proceso"}>En proceso</option>
          <option value={"listo"}>Listo</option>
          <option value={"entregado"}>Entregado</option>

        </select>
      </div>

      {/* Buscador */}
      <div>
        <div>
          Buscar por prenda
        </div>

        <select className="form-control mb-3"
          value={busqueda["prendaId"]}
          onChange={handleBuscarPrenda}>
          <option value={-1}>n/a</option>
          {
            prendasData.map((elemento) => {
              return (
                <option value={elemento["prendaId"]}>{elemento["tipo"]} - {elemento["descripcion"]}</option>
              );
            })
          }
        </select>
      </div>

      {rol === "admin" && (
        <button className="btn btn-success mb-3" onClick={() => onCreateOrder()}>
          Crear trabajo
        </button>
      )}


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
            <div className="col">{elemento["prenda"]["descripcion"]}</div>
            <div className="col">{elemento["empleado"]["nombre"]}</div>
            <div className="col">{elemento["fecha_inicio"]}</div>
            <div className="col">{elemento["fecha_entrega"]}</div>
            <div className="col">{elemento["precio"]}</div>
            <div className="col">{elemento["estado"]}</div>

            <div className="col acciones">
              {(rol === "admin" || rol === "empleado") && (
                <button
                  className="btn-edit"
                  onClick={() => onEditOrder(elemento["trabajoId"])}
                >
                  Ver/Editar
                </button>
              )}

              {rol === "admin" && (
                <button
                  className="btn-delete"
                  onClick={() => onDeleteOrder(elemento["trabajoId"])}
                >
                  Eliminar
                </button>
              )}

            </div>
          </div>
        ))}
      </div>
    </div>
  );
}

export default OrdersPage;
