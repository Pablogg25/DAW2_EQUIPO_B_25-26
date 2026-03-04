import { useState, useEffect, useContext } from "react";
import { useNavigate } from "react-router-dom";
import { AuthContext } from "../../context/AuthContext";
import { useMessage } from "../../components/UseMessage";
import { useConfirm } from "../../components/useConfirm";


import $prendasController from "../../core/PrendasController";
import $usersController from "../../core/UsersController";

function PrendasPage() {
  const [prendas, setPrendas] = useState([]);
  const [usuarios, setUsuarios] = useState([]);
  const [busqueda, setBusqueda] = useState(-1);
  const { usuario } = useContext(AuthContext);
  const { showMessage } = useMessage();
  const { confirm } = useConfirm();

  const rol = usuario?.rol; // admin | empleado | cliente


  const navegar = useNavigate();

  const cargarDatos = async (userId = -1) => {
    // console.log("Cargando datos");

    //obtener datos de controlador

    //si datos de usuario no estan inicializados cargar y cachear:
    if (usuarios.length == 0) {
      // console.log("Cargando datos de usuarios");
      let datosUsuario = await $usersController.getUsers([]);
      setUsuarios(datosUsuario.data);
    }
    // console.log("Cargando datos de prendas");
    let datos = await $prendasController.getPrendas({ "usuarioId": userId });

    if (datos.success) {
      // console.log("Datos recividos");
      setPrendas(datos.data);
    } else {
      if (datos.status == 404) {
        showMessage("No se encontraron elementos con ese usuario.", "info");

        setPrendas([]);
      } else {
        // console.log("ERROR: un error inesperado surgió al cargar datos");
        showMessage("Ha surgido un error al cargar datos. " + datos.status, "error");

        setPrendas([]);
      }

    }

  };

  // -------------------------------------------------------
  // Buscar por id
  // -------------------------------------------------------
  function handleBuscar(e) {
    const valor = e.target.value;
    setBusqueda(valor);
    cargarDatos(valor);
  }

  const onCreatePrenda = () => {
    // console.log("On create Prenda");

    //navegar a formulario
    navegar("/prendas/0");
  };

  const onEditPrenda = (prendaId) => {
    // console.log("On edit prenda id: " + prendaId);

    //navegar al id
    navegar("/prendas/" + prendaId);
  };

  const onDeletePrenda = async (prendaId) => {
    // console.log("On delete prenda id: " + prendaId);

    if (rol !== "admin") {
      showMessage("No tienes permisos para eliminar.", "warning");

      return;
    }


    if (prendaId) {
      //hacer confirm para borrar el usuario y luego recargar datos

      let seguro = await confirm("¿Seguro que desea eliminar la prenda?");
      if (seguro) {
        let result = await $prendasController.deletePrenda(prendaId);

        if (result) {
          cargarDatos();
          navegar("/prendas");
        } else {
          if (result.estado == 409) {

            showMessage(
              "ERROR: 409: no se puede borrar la prenda porque depende de otro elemento de la base de datos",
              "error");

          } else {
            alert(
              "Error, ha surgido un error al procesar su petición.\nCodigo de error: " +
              result.estado,
            );
            showMessage(
              "Error, ha surgido un error al procesar su petición.\nCodigo de error: " + result.estado,
              "error");
          }
        }
      }
    }
  };

  useEffect(() => {
    cargarDatos();
  }, []);

  function getUsername(userId) {
    let index = usuarios.findIndex((u) => u.usuarioId == userId);
    if (index != -1) {
      return usuarios[index].nombre;
    }
    return "not found";
  }

  return (
    <div className="container mt-4 page-fade">
      <h2 className="mb-2">Prendas</h2>
      <p className="text-muted mb-3">Lista para realizar CRUD sobre prendas registradas en la bbdd</p>

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


      {rol === "admin" && (
        <button className="btn btn-success mb-3" onClick={() => onCreatePrenda()}>
          Crear prenda
        </button>
      )}


      <div className="tabla-div">
        {/* Cabecera */}
        <div className="fila cabecera cols-7">
          <div className="col">
            <strong>Id</strong>
          </div>
          <div className="col">
            <strong>Usuario</strong>
          </div>
          <div className="col">
            <strong>Tipo</strong>
          </div>
          <div className="col">
            <strong>Descripción</strong>
          </div>
          <div className="col">
            <strong>Color</strong>
          </div>
          <div className="col">
            <strong>Talla</strong>
          </div>
          <div className="col">
            <strong>Operaciones</strong>
          </div>
        </div>

        {/* Filas */}
        {prendas.map((elemento) => (
          <div key={elemento["prendaId"]} className="fila cols-7">
            <div className="col">{elemento["prendaId"]}</div>
            <div className="col">{getUsername(elemento["usuarioId"])}</div>
            <div className="col">{elemento["tipo"]}</div>
            <div className="col">{elemento["descripcion"]}</div>
            <div className="col">{elemento["color"]}</div>
            <div className="col">{elemento["talla"]}</div>


            <div className="col acciones">
              {(rol === "admin" || rol === "empleado") && (
                <button
                  className="btn-edit"
                  onClick={() => onEditPrenda(elemento["prendaId"])}
                >
                  Ver/editar
                </button>
              )}

              {rol === "admin" && (
                <button
                  className="btn-delete"
                  onClick={() => onDeletePrenda(elemento["prendaId"])}
                >
                  Eliminar
                </button>)}

            </div>
          </div>
        ))}
      </div>
    </div>
  );
}

export default PrendasPage;
