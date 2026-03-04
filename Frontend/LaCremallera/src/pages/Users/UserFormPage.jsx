import { useState, useEffect, useContext } from "react";
import { useNavigate, useParams } from "react-router-dom";
import { AuthContext } from "../../context/AuthContext";
import { useMessage } from "../../components/UseMessage";
import { useConfirm } from "../../components/useConfirm";

import $usersController from "../../core/UsersController";


function UserFormPage() {
  const [userData, setUserData] = useState({
    nombre: "",
    telefono: "",
    email: "",
    direccion: "",
    username: "",
    rol: "",
    fecha_registro: "",
    password: "",
    confirm_password: "",
  });

  const navegar = useNavigate();

  const { id } = useParams();
  const { usuario } = useContext(AuthContext);
  const { showMessage } = useMessage();
  const { confirm } = useConfirm();

  const rol = usuario?.rol; // admin | empleado | cliente


  const cargarDatos = async () => {
    // console.log("cargando datos");

    if (id != 0) {
      // console.log("Modo update");
      //obtener datos
      let datos = await $usersController.getUser(id);

      if (datos.success) {
        // console.log(datos);
        setUserData(datos.data);
      } else {
        showMessage("No se pudo procesar la petición " + datos, "error");
        navegar("/users");
      }
    } else {
      //modo create
      if (rol !== "admin") {
        let actualizar = { ...userData, ["rol"]: "empleado" };
        setUserData(actualizar);
      }

    }
    //else modo create
  };

  const handleOnSubmit = (evento) => {
    evento.preventDefault();
    // console.log("UserFormPage: onSubmit");

    //enviar datos
    enviarDatos();
  };

  const enviarDatos = async () => {
    // console.log("Enviando datos");
    let success;
    let statusCode = 0;

    if (id != 0) {
      //update
      const response = await $usersController.updateUser(userData, id);
      success = response.success;
      statusCode = response.estado;
    } else {
      //antes comprobar que confirm password es correcto

      if (userData.confirm_password != userData.password) {
        // console.log("ERROR, confirm password y password no coincide");
        showMessage("Su contraseña no está confirmada, escríbala correctamente", "warning");
        return;
      }
      const response = await $usersController.createUser(userData);
      success = response.success;
      statusCode = response.estado;
    }
    if (success) {
      //TODO: insertar context para guardar datos de login
      navegar("/users");
    } else {

      showMessage("Error, ha surgido un error al procesar su petición.\nCodigo de error: " +
        statusCode, "error");

    }
  };

  const handleOnCancel = (evento) => {
    evento.preventDefault();
    navegar("/users");
    //vuelve a la página de usuarios
  };

  const handleOnChange = (evento) => {
    const { name, value } = evento.target;
    let actualizar = { ...userData, [name]: value };
    setUserData(actualizar);
  };

  function reformatRegisterDate(registerDate) {
    let newDate = registerDate.split(" ")[0];
    return newDate;
  }

  useEffect(() => {
    cargarDatos(id);
  }, [id]);

  return (
    <div className="container mt-4 page-fade">
      <h2 className="mb-4">Usuario</h2>

      <form onSubmit={handleOnSubmit} className="card p-4">
        {/* Nombre */}
        <div className="mb-3">
          <label className="form-label">Nombre</label>
          <input
            type="text"
            name="nombre"
            id="nombre"
            value={userData.nombre}
            onChange={handleOnChange}
            className="form-control"
          />
        </div>

        {/* Teléfono */}
        <div className="mb-3">
          <label className="form-label">Teléfono</label>
          <input
            type="text"
            name="telefono"
            id="telefono"
            value={userData.telefono}
            onChange={handleOnChange}
            className="form-control"
          />
        </div>

        {/* Dirección */}
        <div className="mb-3">
          <label className="form-label">Dirección</label>
          <input
            type="text"
            name="direccion"
            id="direccion"
            value={userData.direccion}
            onChange={handleOnChange}
            className="form-control"
          />
        </div>

        {/* Email */}
        <div className="mb-3">
          <label className="form-label">Correo (único)</label>
          <input
            type="text"
            name="email"
            id="email"
            value={userData.email}
            onChange={handleOnChange}
            className="form-control"
          />
        </div>

        {/* Username */}
        <div className="mb-3">
          <label className="form-label">Nombre de usuario (único)</label>
          <input
            type="text"
            name="username"
            id="username"
            value={userData.username}
            onChange={handleOnChange}
            className="form-control"
          />
        </div>

        {/* Password solo si es creación */}
        {id == 0 ? (
          <div className="mb-3">
            <label className="form-label">Contraseña</label>
            <input
              type="password"
              name="password"
              id="password"
              onChange={handleOnChange}
              className="form-control"
            />

            <label className="form-label mt-2">Confirmar contraseña</label>
            <input
              type="password"
              name="confirm_password"
              id="confirm_password"
              onChange={handleOnChange}
              className="form-control"
            />
          </div>
        ) : (
          <div className="mb-3">
            <label className="form-label">Fecha de registro</label>
            <input
              type="date"
              value={reformatRegisterDate(userData.fecha_registro)}
              disabled
              className="form-control"
            />
          </div>
        )}

        {/* Rol */}
        <div className="mb-4">
          <label className="form-label">Rol</label>
          <select
            name="rol"
            id="rol"
            onChange={handleOnChange}
            className="form-select"
            disabled={(rol !== "admin")}
          >
            <option value="cliente" selected={userData.rol == "cliente"}>
              Cliente
            </option>
            <option value="empleado" selected={userData.rol == "empleado"}>
              Empleado
            </option>
            <option value="admin" selected={userData.rol == "admin"}>
              Admin
            </option>
          </select>
        </div>

        {/* Botones */}
        <div className="d-flex gap-3">
          {rol === "admin" && (
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

export default UserFormPage;
