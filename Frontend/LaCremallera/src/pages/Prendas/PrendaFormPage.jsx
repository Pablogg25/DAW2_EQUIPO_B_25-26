import { useState, useEffect, useContext } from "react";
import { useNavigate, useParams } from "react-router-dom";
import { AuthContext } from "../../context/AuthContext";

import $prendasController from "../../core/PrendasController";
import $usersController from "../../core/UsersController";

function PrendaFormPage() {
  const [prendaData, setPrendaData] = useState({
    prendaId: 0,
    usuarioId: 0,
    tipo: "",
    descripcion: "",
    color: "",
    talla: "",
  });

  const [usuariosData, setUsuariosData] = useState([]);

  const navegar = useNavigate();

  const { id } = useParams();
  const { usuario } = useContext(AuthContext);

  const rol = usuario?.rol; // admin | empleado | cliente

  const cargarDatos = async () => {
    // console.log("Cargando Datos");

    let datosUsuario = await $usersController.getUsers([]);

    setUsuariosData(datosUsuario.data);

    if (id != 0) {
      // console.log("modo update");

      let datos = await $prendasController.getPrenda(id);

      //comprobar success
      if (datos.success) {
        setPrendaData(datos.data);
      } else {
        alert("Ha surgido un error inesperado al procesar la petición");
        navegar("/prendas");
      }
    }
  };

  const handleOnSubmit = (evento) => {
    evento.preventDefault();
    // console.log("PrendaFormPage: onSubmit");

    enviarDatos();
  };

  const enviarDatos = async () => {
    // console.log("Enviar datos");
    // console.log(prendaData);

    let success;
    let result;

    if (id != 0) {
      // console.log("actualizar");
      let setearPrenda = { ...prendaData, ["prendaId"]: id };
      result = await $prendasController.updatePrenda(setearPrenda);
      success = result.success;
    } else {
      // console.log("crear");
      result = await $prendasController.createPrenda(prendaData);
      success = result.success;
    }

    //todo: implementar operaciones
    // y manejo de errores

    if (success) {
      alert("Datos enviados con éxito");
      navegar("/prendas");
    } else {
      if (result.estado == 400) {
        alert(
          "Error de validación, compruebe que los campos están correctamente formateados",
        );
      } else {
        alert(
          "Error, ha surgido un error al procesar su petición.\nCodigo de error: " +
          result.estado,
        );
      }
    }
  };

  const handleOnCancel = (evento) => {
    evento.preventDefault();
    navegar("/prendas");
  };

  const handleOnChange = (evento) => {
    const { name, value } = evento.target;
    let actualizar = { ...prendaData, [name]: value };
    setPrendaData(actualizar);
  };

  useEffect(() => {
    cargarDatos(id);
  }, [id]);

  return (
    <div className="container mt-4 page-fade">
      <h2 className="mb-4">Prenda</h2>

      <form onSubmit={handleOnSubmit} className="card p-4">
        {/* Usuario */}
        <div className="mb-3">
          <label className="form-label">Usuario</label>
          <select
            name="usuarioId"
            id="usuarioId"
            value={prendaData.usuarioId}
            onChange={handleOnChange}
            className="form-select"
          >
            {usuariosData.map((elemento) => (
              <option key={elemento.usuarioId} value={elemento.usuarioId}>
                {elemento.nombre}
              </option>
            ))}
          </select>
        </div>

        {/* Tipo */}
        <div className="mb-3">
          <label className="form-label">Tipo</label>
          <input
            type="text"
            name="tipo"
            id="tipo"
            value={prendaData.tipo}
            onChange={handleOnChange}
            className="form-control"
          />
        </div>

        {/* Descripción */}
        <div className="mb-3">
          <label className="form-label">Descripción</label>
          <input
            type="text"
            name="descripcion"
            id="descripcion"
            value={prendaData.descripcion}
            onChange={handleOnChange}
            className="form-control"
          />
        </div>

        {/* Color */}
        <div className="mb-3">
          <label className="form-label">Color</label>
          <input
            type="text"
            name="color"
            id="color"
            value={prendaData.color}
            onChange={handleOnChange}
            className="form-control"
          />
        </div>

        {/* Talla */}
        <div className="mb-4">
          <label className="form-label">Talla</label>
          <input
            type="text"
            name="talla"
            id="talla"
            value={prendaData.talla}
            onChange={handleOnChange}
            className="form-control"
          />
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
            Cancelar
          </button>
        </div>
      </form>
    </div>
  );
}

export default PrendaFormPage;
