import { useState } from "react";
import { AuthContext } from "./AuthContext";

function AuthProvider({ children }) {
  const [usuario, setUsuario] = useState(null);

  function login(userData) {
    setUsuario(userData.user);
    sessionStorage.setItem("authToken", userData.token);
  }

  function logout() {
    setUsuario(null);
    sessionStorage.removeItem("authToken");
  }

  function token() {
    const t = sessionStorage.getItem("authToken");
    return t && t !== "null" ? t : null;
  }

  return (
    <AuthContext.Provider value={{ usuario, token, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
}

export default AuthProvider;
