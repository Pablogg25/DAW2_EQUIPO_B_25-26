import { useState, useEffect, useContext } from "react";
import { useNavigate } from "react-router-dom";
import { AuthContext } from "../../context/AuthContext";

import $usersController from "../../core/UsersController";

function UsersPage() {
  const [users, setUsers] = useState([]);
  const [busqueda, setBusqueda] = useState("");
  const { usuario } = useContext(AuthContext);

  const navegar = useNavigate();

  const rol = usuario?.rol; // admin | empleado | cliente

  const cargarDatos = async (nombre = "") => {
    // console.log("Cargando datos");

    let datos = await $usersController.getUsers({ 'username': nombre });

    if (datos.success) {
      // console.log("DATOS RECIVIDOS");
      setUsers(datos.data);
    } else {
      if (datos.status != 404) {
        // console.log("ERROR: un error inesperado surgió al cargar datos");
        alert("Ha surgido un error al cargar datos. " + datos.data);
      }

    }
  };

  // -------------------------------------------------------
  // Buscar por nombre
  // -------------------------------------------------------
  function handleBuscar(e) {
    const valor = e.target.value;
    setBusqueda(valor);
    // cargarDatos(valor);
  }

  function startBusqueda() {
    cargarDatos(busqueda);
  }

  const onCreateUser = () => {
    // console.log("On create user");
    //TODO: crear formulario de propiedades
    navegar("/users/0");
  };

  const onEditUser = (userId) => {
    // console.log("On edit user id: " + userId);
    if (userId) {
      //navegar al formulario
      navegar("/users/" + userId);
    }
  };
  //eliminar (solo para admin)
  const onDeleteUser = async (userId) => {
    // console.log("on delete user: " + userId);

    if (rol !== "admin") {
      alert("No tienes permisos para eliminar.");
      return;
    }
    if (userId) {
      if (confirm("¿Está seguro que desea borrar este usuario?")) {
        // console.log("Eliminando usuario");
        //realizar petición de borrado

        let result = await $usersController.deleteUser(userId);

        //if success
        if (!result.success) {
          alert("ERROR, no se ha podido procesar su petición");
          alert("ERROR: " + result.data);
        } else {
          cargarDatos();
        }
      }
    }
  };

  useEffect(() => {
    cargarDatos();
  }, []);

  return (
    <div className="container mt-4 page-fade">
      <h2 className="mb-2">Usuarios</h2>
      <p className="text-muted mb-3">Lista para realizar CRUD sobre usuarios</p>

      {/* Buscador */}
      <input
        type="text"
        className="form-control mb-3"
        placeholder="Buscar usuario por nombre..."
        value={busqueda}
        onChange={handleBuscar}
      />
      <button className="btn btn-success mb-3" onClick={() => { startBusqueda(); }}>
        Aplicar filtro
      </button>
      {rol === "admin" && (
        <button className="btn btn-success mb-3" onClick={() => onCreateUser()}>
          Crear Usuario
        </button>
      )}


      <div className="tabla-div">
        {/* Cabecera */}
        <div className="fila cabecera cols-9">
          <div className="col">
            <strong>Id</strong>
          </div>
          <div className="col">
            <strong>Nombre</strong>
          </div>
          <div className="col">
            <strong>Teléfono</strong>
          </div>
          <div className="col">
            <strong>Email</strong>
          </div>
          <div className="col">
            <strong>Dirección</strong>
          </div>
          <div className="col">
            <strong>Username</strong>
          </div>
          <div className="col">
            <strong>Rol</strong>
          </div>
          <div className="col">
            <strong>Fecha registro</strong>
          </div>
          <div className="col">
            <strong>Operaciones</strong>
          </div>
        </div>

        {/* Filas */}
        {users.map((elemento) => (
          <div key={elemento["usuarioId"]} className="fila cols-9">
            <div className="col">{elemento["usuarioId"]}</div>
            <div className="col">{elemento["nombre"]}</div>
            <div className="col">{elemento["telefono"]}</div>
            <div className="col">{elemento["email"]}</div>
            <div className="col">{elemento["direccion"]}</div>
            <div className="col">{elemento["username"]}</div>
            <div className="col">{elemento["rol"]}</div>
            <div className="col">{elemento["fecha_registro"]}</div>

            <div className="col acciones">
              <button
                className="btn-edit"
                onClick={() => onEditUser(elemento["usuarioId"])}
              >
                Ver/editar
              </button>

              {rol === "admin" && (
                <button
                  className="btn-delete"
                  onClick={() => onDeleteUser(elemento["usuarioId"])}
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

export default UsersPage;
