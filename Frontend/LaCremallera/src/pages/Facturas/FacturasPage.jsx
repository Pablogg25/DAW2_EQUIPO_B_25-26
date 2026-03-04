import { useState, useEffect, useContext } from "react";
import { useNavigate } from "react-router-dom";
import { AuthContext } from "../../context/AuthContext";

import $facturasController from "../../core/FacturasController";
import $usersController from "../../core/UsersController";

function FacturasPage() {
  const [facturas, setFacturas] = useState([]);
  const [usuarios, setUsuarios] = useState([]);

  const [busqueda, setBusqueda] = useState(-1);

  const navegar = useNavigate();
  const { usuario } = useContext(AuthContext);

  const rol = usuario?.rol; // admin | empleado | cliente

  const cargarDatos = async (filtro = -1) => {
    //console.log("Cargando datos");

    let datos = await $facturasController.getFacturas({ usuarioId: filtro });

    if (datos.success) {
      setFacturas(datos.data);
    } else {
      if (datos.status != 404) {
        alert("Ha surgido un error al procesar su petición, " + datos.status);
      }
      setFacturas([]);

    }


    let datosUsuario = await $usersController.getUsers();
    if (datosUsuario.success) {
      setUsuarios(datosUsuario.data);
    } else {
      alert("Ha surgido un error al procesar su petición");
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

  const onCreateFactura = () => {
    //console.log("On create factura");

    //navegar
    navegar("/facturas/0");
  };

  const onEditFactura = (facturaId) => {
    //console.log("On edit factura id: " + facturaId);

    //navegar
    navegar("/facturas/" + facturaId);
  };

  const onDeleteFactura = async (facturaId) => {
    //console.log("On delete factura id: " + facturaId);

    //ejecutar petición

    if (rol !== "admin") {
      alert("No tienes permisos para eliminar.");
      return;
    }


    if (facturaId) {
      //hacer confirm para borrar el usuario y luego recargar datos

      if (confirm("¿Seguro que desea eliminar la factura?")) {
        let result = await $facturasController.deleteFactura(facturaId);

        if (result) {
          cargarDatos();
          // navegar("/facturas");
        } else {
          if (result.estado == 409) {
            alert(
              "ERROR: 409: no se puede borrar la factura porque depende de otro elemento de la base de datos",
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

  function getTotalFactura(factura) {
    if (factura["total_calculado"]) {
      return factura["total_calculado"];
    }
    //else
    let calc = 0;
    for (let t of factura["trabajos"]) {
      calc += parseInt(t["precio"]);
    }
    return calc;
  }

  return (
    <div className="container mt-4 page-fade">
      <h2 className="mb-3">Facturas</h2>

      {/* Buscador */}
      <div>
        <div>
          Buscar por usuarios
        </div>

        <select className="form-control mb-3"
          value={busqueda}
          onChange={handleBuscar}>
          <option value={-1}>n/a</option>
          {
            usuarios.map((elemento) => {
              return (
                <option value={elemento["usuarioId"]}>{elemento["nombre"]}</option>
              );
            })
          }
        </select>
      </div>

      <button
        className="btn btn-success mb-3"
        onClick={() => onCreateFactura()}
      >
        Crear factura
      </button>

      <div className="tabla-div">
        {/* CABECERA */}
        <div className="fila cabecera cols-7">
          <div className="col">
            <strong>Id</strong>
          </div>
          <div className="col">
            <strong>Usuario</strong>
          </div>
          <div className="col">
            <strong>Fecha</strong>
          </div>
          <div className="col">
            <strong>Pago</strong>
          </div>
          <div className="col">
            <strong>Total factura</strong>
          </div>
          <div className="col">
            <strong>Número de trabajos</strong>
          </div>
          <div className="col">
            <strong>Operaciones</strong>
          </div>
        </div>

        {/* FILAS */}
        {facturas.map((elemento) => (
          <div key={elemento["facturaId"]} className="fila cols-7">
            <div className="col">{elemento["facturaId"]}</div>

            <div className="col">{elemento["usuario"]["nombre"]}</div>

            <div className="col">{elemento["fecha"]}</div>

            <div className="col">
              {elemento["pagado"] == 1 ? "Pagado" : "Pendiente"}
            </div>

            <div className="col">{getTotalFactura(elemento)} €</div>

            <div className="col">{elemento["trabajos"].length}</div>

            <div className="col acciones">
              <button
                className="btn-edit"
                onClick={() => onEditFactura(elemento["facturaId"])}
              >
                Ver/editar
              </button>

              {rol === "admin" && (
                <button
                  className="btn-delete"
                  onClick={() => onDeleteFactura(elemento["facturaId"])}
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

export default FacturasPage;
