import { useState } from "react";
import { AuthContext } from "./AuthContext";

function AuthProvider({ children }) {
  const [usuario, setUsuario] = useState(null);
  const [token, setToken] = useState(null);

  function login(userData) {
    // console.log("Auth provider, saving data: ");
    // console.log(userData);
    setUsuario(userData.user);
    setToken(userData.token)
  }

  function logout() {
    setUsuario(null);
    setToken(null);
  }

  return (
    <AuthContext.Provider value={{ usuario, token, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
}

export default AuthProvider;
