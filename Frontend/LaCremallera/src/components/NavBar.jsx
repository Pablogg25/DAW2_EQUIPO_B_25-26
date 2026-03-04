import { NavLink, useNavigate } from "react-router-dom";
import { useContext } from "react";
import { AuthContext } from "../context/AuthContext";

function NavBar() {
  const { usuario, logout } = useContext(AuthContext);
  const navigate = useNavigate();

  const rol = usuario?.rol; // admin | empleado | cliente

  const handlePerfil = () => {
    if (!usuario) return;
    navigate("/users/" + usuario.usuarioId);
  };

  const handleLogout = () => {
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
          {/* INVENTARIO → admin, empleado */}
          {(rol === "admin" || rol === "empleado") && (
            <li className="nav-item">
              <NavLink to="/inventory" className="nav-link">
                Inventario
              </NavLink>
            </li>
          )}

          {/* TRABAJOS → admin, empleado, cliente */}
          {(rol === "admin" || rol === "empleado" || rol === "cliente") && (
            <li className="nav-item">
              <NavLink to="/orders" className="nav-link">
                Trabajos
              </NavLink>
            </li>
          )}

          {/* PRENDAS → admin, empleado */}
          {(rol === "admin" || rol === "empleado") && (
            <li className="nav-item">
              <NavLink to="/prendas" className="nav-link">
                Prendas
              </NavLink>
            </li>
          )}

          {/* USUARIOS → admin y empleado */}
          {(rol === "admin" || rol === "empleado") && (
            <li className="nav-item">
              <NavLink to="/users" className="nav-link">
                Usuarios
              </NavLink>
            </li>
          )}

          {/* NOTIFICACIONES → admin, empleado, cliente */}
          {(rol === "admin" || rol === "empleado" || rol === "cliente") && (
            <li className="nav-item">
              <NavLink to="/notificaciones" className="nav-link">
                Notificaciones
              </NavLink>
            </li>
          )}

          {/* FACTURAS → admin, empleado, cliente */}
          {(rol === "admin" || rol === "empleado" || rol === "cliente") && (
            <li className="nav-item">
              <NavLink to="/facturas" className="nav-link">
                Facturas
              </NavLink>
            </li>
          )}

          {/* CALENDARIO → admin, empleado, cliente */}
          {(rol === "admin" || rol === "empleado" || rol === "cliente") && (
            <li className="nav-item">
              <NavLink to="/calendar" className="nav-link">
                Calendario
              </NavLink>
            </li>
          )}

          {/* MENÚ DE USUARIO (Bootstrap dropdown) */}
          {usuario && (
            <li className="nav-item dropdown ms-3">
              <button
                className="btn btn-secondary dropdown-toggle"
                data-bs-toggle="dropdown"
              >
                {usuario.nombre || usuario.username || "Usuario"}
              </button>

              <ul className="dropdown-menu dropdown-menu-end">
                <li>
                  <button className="dropdown-item" onClick={handlePerfil}>
                    Perfil
                  </button>
                </li>

                <li>
                  <button
                    className="dropdown-item text-danger"
                    onClick={handleLogout}
                  >
                    Cerrar sesión
                  </button>
                </li>
              </ul>
            </li>
          )}
        </ul>
      </div>
    </nav>
  );
}

export default NavBar;
