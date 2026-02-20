import { useContext } from "react";
import { Navigate } from "react-router-dom";
import { AuthContext } from "../context/AuthContext";

function RoleRoute({ children, roles }) {
  const { usuario } = useContext(AuthContext);

  if (!usuario) {
    return <Navigate to="/login" replace />;
  }

  if (!roles.includes(usuario.rol)) {
    return <Navigate to="/" replace />;
  }

  return children;
}

export default RoleRoute;
