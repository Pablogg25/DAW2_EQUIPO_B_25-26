import { NavLink } from "react-router-dom";

function NavBar() {
  return (
    <nav className="navbar navbar-expand-lg navbar-dark bg-dark px-3">
      {/* ICONO A LA IZQUIERDA */}
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

      {/* BOTÓN HAMBURGUESA */}
      <button
        className="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#mainNavbar"
      >
        <span className="navbar-toggler-icon"></span>
      </button>

      {/* MENÚ */}
      <div className="collapse navbar-collapse" id="mainNavbar">
        <ul className="navbar-nav ms-auto">
          <li className="nav-item">
            <NavLink to="/inventory" className="nav-link">
              Inventario
            </NavLink>
          </li>

          <li className="nav-item">
            <NavLink to="/orders" className="nav-link">
              Trabajos
            </NavLink>
          </li>

          <li className="nav-item">
            <NavLink to="/prendas" className="nav-link">
              Prendas
            </NavLink>
          </li>

          <li className="nav-item">
            <NavLink to="/users" className="nav-link">
              Usuarios
            </NavLink>
          </li>

          <li className="nav-item">
            <NavLink to="/notificaciones" className="nav-link">
              Notificaciones
            </NavLink>
          </li>

          <li className="nav-item">
            <NavLink to="/facturas" className="nav-link">
              Facturas
            </NavLink>
          </li>

          {/* NUEVO: CALENDARIO */}
          <li className="nav-item">
            <NavLink to="/calendar" className="nav-link">
              Calendario
            </NavLink>
          </li>
        </ul>
      </div>
    </nav>
  );
}

export default NavBar;
