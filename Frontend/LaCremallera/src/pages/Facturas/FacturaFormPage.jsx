import { useState, useEffect, useContext } from "react";
import { useNavigate, useParams } from "react-router-dom";
import { AuthContext } from "../../context/AuthContext";

import $facturasController from "../../core/FacturasController";
import $usersController from "../../core/UsersController";
import $ordersController from "../../core/OrdersController";

function FacturaFormPage() {
  const [facturaDatos, setFacturaDatos] = useState({
    facturaId: 0,
    usuarioId: 1,
    fecha: "",
    pagado: 0,
    total_calculado: null,
    trabajos: []

  });
  const [usuariosData, setUsuariosData] = useState([]);
  const [trabajosData, setTrabajosData] = useState([]);

  //array trabajos a añadir
  //array trabajos a eliminar
  // contienen los ids
  const [selectedAddTrabajo, setSelectTrabajo] = useState(0);
  const [trabajosAdd, setTrabajosAdd] = useState([]);
  const [trabajosRemove, setTrabajosRemove] = useState([]);

  const navegar = useNavigate();

  const { id } = useParams();
  const { usuario } = useContext(AuthContext);

  const rol = usuario?.rol; // admin | empleado | cliente

  const cargarDatos = async () => {
    //console.log("Cargando datos");

    if (usuariosData.length == 0) {
      // let datosUsuario = await $usuariosController.getUsuarios();
      let datosUsuario = await $usersController.getUsers();

      if (datosUsuario.success) {
        setUsuariosData(datosUsuario.data);
        // setUsuariosData(datosUsuario.data);
      } else {
        alert("Ha surgido un error al cargar datos");
        navegar("/notificaciones");
      }
    }

    if (trabajosData.length == 0) {
      let datosTrabajo = await $ordersController.getOrders();

      if (datosTrabajo.success) {
        setTrabajosData(datosTrabajo.data);
      } else {
        alert("Ha surgido un error al cargar datos");
        navegar("/notificaciones");
      }
    }

    let datosFactura = await $facturasController.getFactura(id);

    if (datosFactura.success) {
      setFacturaDatos(datosFactura.data);
    } else {
      alert("Ha surgido un error al cargar datos");
      navegar("/notificaciones");
    }
  };

  const handeOnSubmit = (evento) => {
    evento.preventDefault();
    //console.log("FacturaFormPage onSubmit");
    enviarDatos();
  };

  const enviarDatos = async () => {
    //console.log("Enviar datos");

    //console.log(facturaDatos);

    let result;

    if (id != 0) {
      //console.log("Modo update");

      let datos = { ...facturaDatos, ["facturaId"]: id };
      result = await $facturasController.updateFactura(datos);

      if (result.success) {
        //se realiza creación correctamente
        for (let add of trabajosAdd) {
          //console.log("asociando trabajo id: " + add);
          let resultAdd = await $facturasController.asociarTrabajo(id, add);

          if (!resultAdd.success) {
            //console.log("error en asociación");
            alert("Ha surgido un error al asociar trabajos");
            navegar("/facturas");
            break;
          }
          //console.log("añadido correctamente");
        }
        for (let rem of trabajosRemove) {
          //console.log("desasociando trabajo id: " + rem);
          let resultAdd = await $facturasController.desasociarTrabajo(id, rem);

          if (!resultAdd.success) {
            //console.log("error en desasociado");
            alert("Ha surgido un error al desasociar trabajos");
            navegar("/facturas");
            break;
          }
          //console.log("quitado correctamente");
          navegar("/facturas");
        }
      } else {
        alert("Ha surgido un error al enviar datos");
      }
    } else {
      //console.log("Modo create");
      result = await $facturasController.createFactura(facturaDatos);

      if (result.success) {
        for (let add of trabajosAdd) {
          //console.log("asociando trabajo id: " + add);
          let resultAdd = await $facturasController.asociarTrabajo(id, add);

          if (!resultAdd.success) {
            //console.log("error en asociación");
            alert("Ha surgido un error al asociar trabajos");
            navegar("/facturas");
            break;
          }
          //console.log("añadido correctamente");
        }
      }
    }
    //por cada operación de añadir o eliminar realizr operaciónd el controler

    // if (result.success) {
    //     alert("Datos enviados correctamente");
    //     navegar("/facturas");
    // } else {
    //     alert("Ha surgido un error al enviar datos");
    // }
  };

  const handleOnCancel = (evento) => {
    evento.preventDefault();
    navegar("/facturas");
  };

  const handleOnChange = (evento) => {
    const { name, value } = evento.target;
    let actualizar = { ...facturaDatos, [name]: value };
    setFacturaDatos(actualizar);
  };

  const handleOnAddSelect = (evento) => {
    // console.log("Selected trabajo para añadir");
    const { name, value } = evento.target;
    setSelectTrabajo(value);
  };

  const handleOnAddItem = (evento) => {
    evento.preventDefault();
    let targetId = parseInt(selectedAddTrabajo);
    // console.log("handleOnAddItem target id: "+targetId);
    if (targetId == 0) {
      // console.log("añadiendo default");
      targetId = parseInt(getTrabajoOptions()[0].trabajoId);
    }
    // console.log("handleOnAddItem Añadiendo item id: "+targetId);

    //si ya esta añadido no hacer nada

    if (trabajosRemove.indexOf(targetId) != -1) {
      // console.log("Quitando de lista de quitar trabajos");
      //si al lista de trabajos a quitar contiene el id a quitar entonces revertimos esa operación
      let updateremove = [...trabajosRemove];
      updateremove.splice(updateremove.indexOf(targetId), 1);
      setTrabajosRemove(updateremove);
      return;
    }
    //asumimos que items ya presentes en la lista de trabajos original o la de añadir no se pueden seleccionar
    if (trabajosAdd.indexOf(targetId) == -1) {
      //si la lista de trabajos a añadir no contiene el trabajo seleccionado
      // console.log("Añadiendo a lista de añadir trabajos");
      //se añade
      let update = [...trabajosAdd];
      update.push(targetId);
      setTrabajosAdd(update);
      return;
    }

    //actualizar la variable
    setSelectTrabajo(parseInt(getTrabajoOptions()[0].trabajoId));
  };

  const handleOnRemoveItem = (trabajoId) => {
    if (trabajosRemove.indexOf(trabajoId) == -1) {
      //si la lista de trabajos a quitar no contiene el trabajo seleccionado
      //se añade
      let update = [...trabajosRemove];
      update.push(trabajoId);
      setTrabajosRemove(update);
      return;
    }

    if (trabajosAdd.indexOf(trabajoId) != -1) {
      //si al lista de trabajos a añadir contiene el id a añadir entonces revertimos esa operación
      let update = [...trabajosAdd];
      update.splice(update.indexOf(trabajoId), 1);
      setTrabajosAdd(update);
      return;
    }
  };

  function getFullItemList() {
    //console.log("Obtener datos de añadir");

    let listaAnadir = [];

    for (let elementoAdd of trabajosAdd) {
      let index = trabajosData.map((el) => el.trabajoId).indexOf(elementoAdd);
      // console.log("Buscando id: "+id+" resultado index= "+index);
      if (index != -1) {
        // console.log("añadiendo trabajo");
        // console.log(trabajosData[index]);
        listaAnadir.push(trabajosData[index]);
      }
    }

    //console.log(listaAnadir);
    //console.log("Obtener lista sin los que se quitan");

    let listaSinQuitados = [];

    for (let t of facturaDatos.trabajos) {
      let indexDeleteado = trabajosRemove.indexOf(t.trabajoId);
      if (indexDeleteado == -1) {
        //si no está entre los deleteados
        listaSinQuitados.push(t);
      }
    }

    //console.log(listaSinQuitados);

    // let fullList = [...listaQuitados, ...listaAnadir];
    let fullList = [...listaSinQuitados, ...listaAnadir];
    //console.log("Get full list item list total:");
    //console.log(fullList);

    return fullList;
  }

  function getTrabajoOptions() {
    let listaOptions = [];
    let idsEnLista = getFullItemList().map((u) => u.trabajoId);

    //por cada trabajo en datos, si no se encuentra en la lista total no se añade
    for (let t of trabajosData) {
      if (idsEnLista.includes(t.trabajoId) == false) {
        listaOptions.push(t);
      }
    }

    // setSelectTrabajo(listaOptions[0].trabajoId);

    return listaOptions;
  }

  useEffect(() => {
    cargarDatos();
  }, []);

  function formatDateToInput(date) {
    if (!date) {
      return "";
    }
    //2026-02-18 17:24:20" does not conform to the required format, "yyyy-MM-dd"
    // console.log("Parsing date: "+date);

    let dateObj = new Date(date);
    let newFormat = dateObj.toISOString().split("T")[0];
    // console.log("Parsing "+date+" to "+newFormat);

    return newFormat;
  }

  // function getTotalFactura(factura) {
  //     if (factura["total_calculado"]) {
  //         return factura["total_calculado"];
  //     }
  //     //else está vacío
  //     if (!factura["trabajos"]) {
  //         return 0;
  //     }
  //     //else calcular
  //     let calc = 0;
  //     for (let t of factura["trabajos"]) {
  //         calc += parseInt(t["precio"]);
  //     }
  //     return calc;
  // }

  function calcularTotalFactura() {
    let factura = facturaDatos;
    if (factura["total_calculado"]) {
      return factura["total_calculado"];
    }
    //else está vacío
    if (!factura["trabajos"]) {
      return 0;
    }
    //else calcular
    let calc = 0;
    for (let t of getFullItemList()) {
      calc += parseFloat(t["precio"]);
    }
    return calc;
  }

  // console.log("Lista de trabajos original");
  // console.log(facturaDatos.trabajos);
  // console.log("Trabajos a añadir");
  // console.log(trabajosAdd);
  // console.log("trabajos a quitar");
  // console.log(trabajosRemove);

  return (
    <div className="container mt-4 page-fade">
      <h2 className="mb-4">Factura</h2>

      <form onSubmit={handeOnSubmit} className="card p-4">
        {/* Usuario */}
        <div className="mb-3">
          <label className="form-label">Usuario</label>
          <select
            name="usuarioId"
            id="usuarioId"
            onChange={handleOnChange}
            value={facturaDatos.usuarioId}
            className="form-select"
          >
            {usuariosData.map((elemento) => (
              <option key={elemento["usuarioId"]} value={elemento["usuarioId"]}>
                {elemento["nombre"]}
              </option>
            ))}
          </select>
        </div>

        {/* Fecha */}
        <div className="mb-3">
          <label className="form-label">Fecha</label>
          <input
            type="date"
            name="fecha"
            id="fecha"
            value={facturaDatos.fecha}
            onChange={handleOnChange}
            className="form-control"
          />
        </div>

        {/* Pagado */}
        <div className="mb-3 d-flex align-items-center gap-2">
          <label className="form-label m-0">Pagado</label>
          <input
            type="checkbox"
            name="pagado"
            id="pagado"
            checked={facturaDatos.pagado == 1}
            onChange={handleOnChange}
          />
        </div>

        {/* Trabajos */}
        <div className="mb-4">
          <h5 className="mb-2">Trabajos</h5>

          <label className="form-label">Seleccionar trabajo</label>
          <select
            name="selectTrabajos"
            id="selectTrabajos"
            onChange={handleOnAddSelect}
            className="form-select mb-2"
          >
            {getTrabajoOptions().map((elemento) => (
              <option key={elemento["trabajoId"]} value={elemento["trabajoId"]}>
                {elemento["trabajoId"]} - {elemento["descripcion"]} -{" "}
                {elemento["precio"]} €
              </option>
            ))}
          </select>

          {(rol === "admin" || rol === "empleado") && (
            <button className="btn btn-primary mb-3" onClick={handleOnAddItem}>
              Añadir item
            </button>
          )}


          <div className="tabla-div">
            <div className="fila cabecera cols-3">
              <div className="col">ID</div>
              <div className="col">Descripción</div>
              {(rol === "admin" || rol === "empleado") && (
                <div className="col">Acciones</div>
              )}

            </div>

            {getFullItemList().map((elemento) => (
              <div key={elemento["trabajoId"]} className="fila cols-3">
                <div className="col">{elemento["trabajoId"]}</div>
                <div className="col">{elemento["descripcion"]}</div>
                {(rol === "admin" || rol === "empleado") && (
                  <div className="col acciones">
                    <button
                      className="btn-delete"
                      onClick={(evento) => {
                        evento.preventDefault();
                        handleOnRemoveItem(elemento["trabajoId"]);
                      }}
                    >
                      Eliminar
                    </button>
                  </div>
                )}

              </div>
            ))}
          </div>

          <div className="mt-3">
            <strong>Total (€):</strong>
            <input
              type="number"
              step={0.01}
              value={calcularTotalFactura()}
              disabled
              className="form-control mt-1"
            />
          </div>
        </div>

        {/* Botones */}
        <div className="d-flex gap-3">
          {(rol === "admin" || rol === "empleado") && (
            <button type="submit" className="btn btn-success">
              Enviar datos
            </button>
          )}


          <button
            type="button"
            className="btn btn-secondary"
            onClick={handleOnCancel}
          >
            Volver
          </button>
        </div>
      </form>
    </div>
  );
}

export default FacturaFormPage;
