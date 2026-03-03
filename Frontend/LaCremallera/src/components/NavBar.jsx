import { NavLink, useNavigate } from "react-router-dom";
import { useContext, useState } from "react";
import { AuthContext } from "../context/AuthContext";

function NavBar() {
  const { usuario, logout } = useContext(AuthContext);
  const [open, setOpen] = useState(false);
  const navigate = useNavigate();

  const rol = usuario?.rol; // admin | empleado | cliente

  const handlePerfil = () => {
    if (!usuario) return;

    const id = usuario.usuarioId;

    if (id) {
      setOpen(false);
      navigate("/users/" + id);
    } else {
      alert("No se ha podido obtener el id del usuario");
    }
  };

  const handleLogout = () => {
    setOpen(false);
    logout();
  };

  return (
    <nav className="navbar navbar-expand-lg navbar-dark bg-dark px-3">
      <NavLink className="navbar-brand d-flex align-items-center" to="/">
        <img
          src="/logo_circular_full.png"
          alt="logo"
          width="32"
          height="32"
          className="me-2"
        />
        La Cremallera
      </NavLink>

      <button
        className="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#mainNavbar"
      >
        <span className="navbar-toggler-icon"></span>
      </button>

      <div className="collapse navbar-collapse" id="mainNavbar">
        <ul className="navbar-nav ms-auto">
          {/* INVENTARIO → admin y empleado */}
          {(rol === "admin" || rol === "empleado") && (
            <li className="nav-item">
              <NavLink to="/inventory" className="nav-link">
                Inventario
              </NavLink>
            </li>
          )}

          {/* TRABAJOS → todos */}
          {(rol === "admin" || rol === "empleado" || rol === "cliente") && (
            <li className="nav-item">
              <NavLink to="/orders" className="nav-link">
                Trabajos
              </NavLink>
            </li>
          )}

          {/* PRENDAS → admin y empleado */}
          {(rol === "admin" || rol === "empleado") && (
            <li className="nav-item">
              <NavLink to="/prendas" className="nav-link">
                Prendas
              </NavLink>
            </li>
          )}

          {/* USUARIOS → solo admin */}
          {rol === "admin" && (
            <li className="nav-item">
              <NavLink to="/users" className="nav-link">
                Usuarios
              </NavLink>
            </li>
          )}

          {/* NOTIFICACIONES → todos */}
          {(rol === "admin" || rol === "empleado" || rol === "cliente") && (
            <li className="nav-item">
              <NavLink to="/notificaciones" className="nav-link">
                Notificaciones
              </NavLink>
            </li>
          )}

          {/* FACTURAS → admin y empleado y cliente */}
          {(rol === "admin" || rol === "empleado" || rol === "cliente") && (
            <li className="nav-item">
              <NavLink to="/facturas" className="nav-link">
                Facturas
              </NavLink>
            </li>
          )}

          {/* CALENDARIO → todos */}
          {(rol === "admin" || rol === "empleado" || rol === "cliente") && (
            <li className="nav-item">
              <NavLink to="/calendar" className="nav-link">
                Calendario
              </NavLink>
            </li>
          )}

          {/* MENÚ DE USUARIO */}
          {usuario && (
            <li className="nav-item ms-3 position-relative">
              <button
                className="btn btn-secondary"
                onClick={() => setOpen(!open)}
              >
                {usuario.nombre || usuario.username || "Usuario"}
              </button>

              {open && (
                <div
                  className="position-absolute bg-white rounded shadow p-2"
                  style={{ right: 0, top: "100%", minWidth: "150px" }}
                >
                  <button className="dropdown-item" onClick={handlePerfil}>
                    Perfil
                  </button>

                  <button
                    className="dropdown-item text-danger"
                    onClick={handleLogout}
                  >
                    Cerrar sesión
                  </button>
                </div>
              )}
            </li>
          )}
        </ul>
      </div>
    </nav>
  );
}

export default NavBar;
