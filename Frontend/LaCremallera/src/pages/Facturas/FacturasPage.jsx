import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";

import $facturasController from "../../core/TestController/TestFacturasController";
import $usuariosController from "../../core/TestController/TestUsersController";

function FacturasPage() {
  const [facturas, setFacturas] = useState([]);
  const [usuarios, setUsuarios] = useState([]);

  const navegar = useNavigate();

  const cargarDatos = async () => {
    console.log("Cargando datos");

    let datos = await $facturasController.getFacturas();

    setFacturas(datos);

    let datosUsuario = await $usuariosController.getUsuarios();
    setUsuarios(datosUsuario);
  };

  const onCreateFactura = () => {
    console.log("On create factura");

    //navegar
    navegar("/facturas/0");
  };

  const onEditFactura = (facturaId) => {
    console.log("On edit factura id: " + facturaId);

    //navegar
    navegar("/facturas/" + facturaId);
  };

  const onDeleteFactura = (facturaId) => {
    console.log("On delete factura id: " + facturaId);

    //ejecutar petición
  };

  useEffect(() => {
    cargarDatos();
  }, []);

  function getUsuarioName(usuarioId) {
    if (usuarios) {
      let index = usuarios.findIndex((p) => p.usuarioId == usuarioId);

      if (index !== -1) {
        return usuarios[index].nombre;
      }
    }
  }

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

            <div className="col">{getUsuarioName(elemento["usuarioId"])}</div>

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

              <button
                className="btn-delete"
                onClick={() => onDeleteFactura(elemento["facturaId"])}
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

export default FacturasPage;
