import { useState, useEffect, useContext } from "react";
import { useNavigate, useParams } from "react-router-dom";
import { AuthContext } from "../../context/AuthContext";
import { useMessage } from "../../components/useMessage";
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

  const navigate = useNavigate();
  const { id } = useParams();
  const { usuario } = useContext(AuthContext);
  const { showMessage } = useMessage();
  const { confirm } = useConfirm();

  const rol = usuario?.rol; // admin | empleado | cliente

  // -------------------------------------------------------
  // Cargar datos
  // -------------------------------------------------------
  const cargarDatos = async () => {
    if (id !== "0") {
      const datos = await $usersController.getUser(id);

      if (datos.success) {
        setUserData(datos.data);
      } else {
        showMessage("No se pudo cargar el usuario.", "error");
        navigate("/users");
      }
    } else {
      // modo creación
      if (rol !== "admin") {
        setUserData((prev) => ({ ...prev, rol: "empleado" }));
      }
    }
  };

  // -------------------------------------------------------
  // Validaciones
  // -------------------------------------------------------
  const validar = () => {
    if (!userData.nombre.trim()) {
      showMessage("El nombre es obligatorio.", "warning");
      return false;
    }

    if (!userData.email.trim()) {
      showMessage("El correo es obligatorio.", "warning");
      return false;
    }

    if (!userData.username.trim()) {
      showMessage("El nombre de usuario es obligatorio.", "warning");
      return false;
    }

    if (id === "0") {
      if (!userData.password.trim()) {
        showMessage("La contraseña es obligatoria.", "warning");
        return false;
      }

      if (userData.password !== userData.confirm_password) {
        showMessage("Las contraseñas no coinciden.", "warning");
        return false;
      }
    }

    return true;
  };

  // -------------------------------------------------------
  // Enviar datos
  // -------------------------------------------------------
  const enviarDatos = async () => {
    if (!validar()) return;

    let response;

    if (id !== "0") {
      // UPDATE → solo admin puede
      if (rol !== "admin") {
        showMessage("No tienes permisos para editar usuarios.", "error");
        return;
      }

      response = await $usersController.updateUser(userData, id);
    } else {
      // CREATE → admin y empleado
      response = await $usersController.createUser(userData);
    }

    if (response.success) {
      showMessage("Usuario guardado correctamente.", "success");
      navigate("/users");
    } else {
      showMessage(
        "Error al procesar la petición. Código: " + response.estado,
        "error",
      );
    }
  };

  // -------------------------------------------------------
  // Handlers
  // -------------------------------------------------------
  const handleOnSubmit = (e) => {
    e.preventDefault();
    enviarDatos();
  };

  const handleOnCancel = () => {
    navigate("/users");
  };

  const handleOnChange = (e) => {
    const { name, value } = e.target;
    setUserData((prev) => ({ ...prev, [name]: value }));
  };

  const reformatRegisterDate = (registerDate) => {
    return registerDate?.split(" ")[0] || "";
  };

  useEffect(() => {
    cargarDatos();
  }, [id]);

  return (
    <div className="container mt-4 page-fade">
      <h2 className="mb-4">Usuario</h2>

      <form onSubmit={handleOnSubmit} className="card p-4">
        {/* Nombre */}
        <div className="mb-3">
          <label className="form-label">Nombre *</label>
          <input
            type="text"
            name="nombre"
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
            value={userData.direccion}
            onChange={handleOnChange}
            className="form-control"
          />
        </div>

        {/* Email */}
        <div className="mb-3">
          <label className="form-label">Correo *</label>
          <input
            type="email"
            name="email"
            value={userData.email}
            onChange={handleOnChange}
            className="form-control"
          />
        </div>

        {/* Username */}
        <div className="mb-3">
          <label className="form-label">Usuario *</label>
          <input
            type="text"
            name="username"
            value={userData.username}
            onChange={handleOnChange}
            className="form-control"
          />
        </div>

        {/* Password solo si es creación */}
        {id === "0" ? (
          <>
            <div className="mb-3">
              <label className="form-label">Contraseña *</label>
              <input
                type="password"
                name="password"
                onChange={handleOnChange}
                className="form-control"
              />
            </div>

            <div className="mb-3">
              <label className="form-label">Confirmar contraseña *</label>
              <input
                type="password"
                name="confirm_password"
                onChange={handleOnChange}
                className="form-control"
              />
            </div>
          </>
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
            value={userData.rol}
            onChange={handleOnChange}
            className="form-select"
            disabled={
              // admin puede editar siempre
              rol !== "admin" ||
              // empleado solo puede crear, no editar
              id !== "0"
            }
          >
            <option value="cliente">Cliente</option>
            <option value="empleado">Empleado</option>
            <option value="admin">Admin</option>
          </select>
        </div>

        {/* Botones */}
        <div className="d-flex gap-3">
          {rol === "admin" ||
            (rol === "empleado" && (
              <button type="submit" className="btn btn-success">
                Guardar
              </button>
            ))}

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
