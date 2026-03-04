
import $notificacionesController from "../../core/NotificacionesController";
import $ordersController from "../../core/OrdersController";

import { useState, useEffect, useContext } from "react";
import { useNavigate } from "react-router-dom";

import { AuthContext } from "../../context/AuthContext";

function NotificacionesPage() {
  const [notificaciones, setNotificaciones] = useState([]);
  // const [usuarios, setUsuarios] = useState([]);
  const [trabajos, setTrabajos] = useState([]);
  //datos usuario
  //datos trabajo

  const [busqueda, setBusqueda] = useState(-1);

  const navegar = useNavigate();

  //useContext
  const { usuario } = useContext(AuthContext);
  const rol = usuario?.rol; // admin | empleado | cliente

  const cargarDatos = async (filtro = -1) => {
    //console.log("Cargando datos");

    let datos;

    if (usuario.rol != "admin") {
      //console.log("Cargando notificaciones de empleado");
      datos = await $notificacionesController.getNotificaciones({
        receptorId: usuario.usuarioId,
        remitenteId: usuario.usuarioId,
        trabajoId: filtro
      });
    } else {
      //console.log("cargando notificaciones admin");
      datos = await $notificacionesController.getNotificaciones({ trabajoId: filtro });
    }

    if (datos.success) {
      setNotificaciones(datos.data);
    } else {
      if (datos.status == 404) {
        setNotificaciones([]);
      } else {
        alert("Ha surgido un error al cargar los datos de notificaciones");
        setNotificaciones([]);
      }

    }

    if (trabajos.length == 0) {
      //console.log("Cargando datos de trabajos");
      let datosTrabajo = await $ordersController.getOrders();
      setTrabajos(datosTrabajo.data);
    }
  };

  //filtro
  // -------------------------------------------------------
  // Buscar por id
  // -------------------------------------------------------
  function handleBuscar(e) {
    const valor = e.target.value;
    setBusqueda(valor);
    cargarDatos(valor);
  }

  const onCreateNotificacion = () => {
    //console.log("On create notificacion");
    navegar("/notificaciones/0");
  };

  const onViewNotificacion = (notificacionId) => {
    //console.log("On view Notificacion id: " + notificacionId);
    navegar("/notificaciones/" + notificacionId);
  };

  const onDeleteNotificacion = async (notId) => {
    //console.log("On delete notificación id: " + notId);

    if (notId) {
      if (rol !== "admin" || rol !== "empleado") {
        alert("No tienes permisos para eliminar.");
        return;
      }
      if (confirm("¿Seguro que desea eliminar la notificación?")) {
        let result = await $notificacionesController.deleteNotificacion(notId);

        if (result.success) {
          cargarDatos();
          navegar("/notificaciones");
        } else {
          if (result.estado == 409) {
            alert(
              "Error 409: no se puede eliminar la notificación porque es dependiente de otro elemento en la base de datos",
            );
          } else {
            alert(
              "Error, ha surgido un error al procesar su petición.\nCodigo de error: " +
              result.estado,
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
      <h2 className="mb-2">Notificaciones</h2>
      <p className="text-muted mb-3">
        Lista para realizar CRUD sobre notificaciones
      </p>

      {/* Buscador */}
      <div>
        <div>
          Buscar por trabajo
        </div>

        <select className="form-control mb-3"
          value={busqueda}
          onChange={handleBuscar}>
          <option value={-1}>n/a</option>
          {
            trabajos.map((elemento) => {
              return (
                <option value={elemento["trabajoId"]}>{elemento["descripcion"]}</option>
              );
            })
          }
        </select>
      </div>

      {(rol === "admin" || rol === "empleado") && (
        <button
          className="btn btn-success mb-3"
          onClick={() => onCreateNotificacion()}
        >
          Crear Notificación
        </button>
      )}


      <div className="tabla-div">
        {/* CABECERA */}
        <div className="fila cabecera cols-9">
          <div className="col">
            <strong>Id</strong>
          </div>
          <div className="col">
            <strong>Receptor</strong>
          </div>
          <div className="col">
            <strong>Remitente</strong>
          </div>
          <div className="col">
            <strong>Trabajo</strong>
          </div>
          <div className="col">
            <strong>Tipo</strong>
          </div>
          <div className="col">
            <strong>Asunto</strong>
          </div>
          <div className="col">
            <strong>Mensaje</strong>
          </div>
          <div className="col">
            <strong>Fecha de envío</strong>
          </div>
          <div className="col">
            <strong>Operaciones</strong>
          </div>
        </div>

        {/* FILAS */}
        {notificaciones.map((elemento) => (
          <div key={elemento["notificacionId"]} className="fila cols-9">
            <div className="col">{elemento["notificacionId"]}</div>

            <div className="col">{elemento["receptor"]["nombre"]}</div>

            <div className="col">{elemento["remitente"]["nombre"]}</div>

            <div className="col">{elemento["trabajoId"]}</div>

            <div className="col">{elemento["tipo"]}</div>

            <div className="col">{elemento["asunto"]}</div>

            <div className="col">{elemento["mensaje"]}</div>

            <div className="col">{elemento["fecha_envio"]}</div>

            <div className="col acciones">
              <button
                className="btn-edit"
                onClick={() => onViewNotificacion(elemento["notificacionId"])}
              >
                Ver
              </button>
              {rol === "admin" && (
                <button
                  className="btn-delete"
                  onClick={() => onDeleteNotificacion(elemento["notificacionId"])}
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

export default NotificacionesPage;
