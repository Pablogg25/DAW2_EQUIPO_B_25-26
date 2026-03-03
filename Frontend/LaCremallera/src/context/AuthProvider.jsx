import { useState } from "react";
import { AuthContext } from "./AuthContext";

function AuthProvider({ children }) {
  const [usuario, setUsuario] = useState(null);
  // const [token, setToken] = useState(null);

  function login(userData) {
    // console.log("Auth provider, saving data: ");
    // console.log(userData);
    setUsuario(userData.user);
    // setToken(userData.token);
    sessionStorage.setItem("authToken",userData.token);
  }

  function logout() {
    setUsuario(null);
    // setToken(null);
    sessionStorage.setItem("authToken",null);

  }

  function token(){
    return sessionStorage.getItem("authToken");
  }


  return (
    <AuthContext.Provider value={{ usuario, token, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
}

export default AuthProvider;
