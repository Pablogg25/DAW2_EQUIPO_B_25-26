import { useState, useEffect, useContext } from "react";
import { useNavigate } from "react-router-dom";
import { AuthContext } from "../../context/AuthContext";
import { useMessage } from "../../components/useMessage";
import { useConfirm } from "../../components/useConfirm";

import $usersController from "../../core/UsersController";

function UsersPage() {
  const [users, setUsers] = useState([]);
  const [busqueda, setBusqueda] = useState("");

  const { usuario } = useContext(AuthContext);
  const { showMessage } = useMessage();
  const { confirm } = useConfirm();

  const navigate = useNavigate();
  const rol = usuario?.rol; // admin | empleado | cliente

  // -------------------------------------------------------
  // Cargar datos
  // -------------------------------------------------------
  const cargarDatos = async (nombre = "") => {
    const datos = await $usersController.getUsers({ username: nombre });

    if (datos.success) {
      setUsers(datos.data);
    } else {
      if (datos.status !== 404) {
        showMessage("Error al cargar usuarios: " + datos.data, "error");
      } else {
        showMessage("No se encontraron usuarios con ese nombre.", "info");
        setUsers([]);
      }
    }
  };

  // -------------------------------------------------------
  // Buscar por nombre
  // -------------------------------------------------------
  const handleBuscar = (e) => {
    setBusqueda(e.target.value);
  };

  const startBusqueda = () => {
    cargarDatos(busqueda);
  };

  // -------------------------------------------------------
  // Crear usuario
  // -------------------------------------------------------
  const onCreateUser = () => {
    navigate("/users/0");
  };

  // -------------------------------------------------------
  // Editar usuario
  // -------------------------------------------------------
  const onEditUser = (userId) => {
    if (userId) navigate("/users/" + userId);
  };

  // -------------------------------------------------------
  // Eliminar usuario (solo admin)
  // -------------------------------------------------------
  const onDeleteUser = async (userId) => {
    if (rol !== "admin") {
      showMessage("No tienes permisos para eliminar usuarios.", "warning");
      return;
    }

    const seguro = await confirm("¿Seguro que deseas eliminar este usuario?");
    if (!seguro) return;

    const result = await $usersController.deleteUser(userId);

    if (!result.success) {
      showMessage("Error al eliminar usuario: " + result.data, "error");
    } else {
      showMessage("Usuario eliminado correctamente.", "success");
      cargarDatos();
    }
  };

  useEffect(() => {
    cargarDatos();
  }, []);

  return (
    <div className="container mt-4 page-fade">
      <h2 className="mb-2">Usuarios</h2>
      <p className="text-muted mb-3">Gestión de usuarios del sistema</p>

      {/* Buscador */}
      <input
        type="text"
        className="form-control mb-3"
        placeholder="Buscar usuario por nombre..."
        value={busqueda}
        onChange={handleBuscar}
      />

      <button className="btn btn-success mb-3 me-2" onClick={startBusqueda}>
        Aplicar filtro
      </button>

      {rol === "admin" ||
        (rol === "empleado" && (
          <button className="btn btn-primary mb-3" onClick={onCreateUser}>
            Crear Usuario
          </button>
        ))}

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
        {users.map((u) => (
          <div key={u.usuarioId} className="fila cols-9">
            <div className="col">{u.usuarioId}</div>
            <div className="col">{u.nombre}</div>
            <div className="col">{u.telefono}</div>
            <div className="col">{u.email}</div>
            <div className="col">{u.direccion}</div>
            <div className="col">{u.username}</div>
            <div className="col">{u.rol}</div>
            <div className="col">{u.fecha_registro}</div>

            <div className="col acciones">
              <button
                className="btn-edit"
                onClick={() => onEditUser(u.usuarioId)}
              >
                Ver / Editar
              </button>

              {rol === "admin" && (
                <button
                  className="btn-delete"
                  onClick={() => onDeleteUser(u.usuarioId)}
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
