import React, { useState, useContext } from "react";
import { useNavigate } from "react-router-dom";
import $usersController from "../core/UsersController";
import { AuthContext } from "../context/AuthContext";

function LoginPage() {
  const [userCred, setUserCred] = useState({ login: "", password: "" });
  const [loading, setLoading] = useState(false);
  const navigate = useNavigate();
  const { login } = useContext(AuthContext);

  const handleOnSubmit = async (event) => {
    event.preventDefault();
    if (loading) {
      // console.log("loading login... please wait");
      return;
    }

    setLoading(true);
    const response = await $usersController.loginUser(userCred);
    setLoading(false);
    if (response.success) {
      // Obtener datos del usuario
      const userData = response;
      // console.log(userData);

      if (userData.success) {
        login(userData.data); // Guardar usuario en contexto
        alert("Login correcto");
        navigate("/");
      } else {
        alert("No se pudo obtener la información del usuario");
      }
    } else {
      if (response.status == 401) {
        alert("Credenciales incorrectas");
      } else {
        alert("Error en login. Código: " + response.status);
      }

    }
  };

  const handleOnChange = (e) => {
    const { name, value } = e.target;
    setUserCred({ ...userCred, [name]: value });
  };

  return (
    <div className="login-container">
      <div className="login-card">
        <h2 className="login-title">Iniciar sesión</h2>

        {loading && (
          <div className="mb-3">
            Cargando... Por favor, espere.
          </div>
        )}

        {!loading && (
          <form onSubmit={handleOnSubmit}>
            <div className="mb-3">
              <label className="form-label">Usuario</label>
              <input
                type="text"
                name="login"
                onChange={handleOnChange}
                className="form-control"
              />
            </div>

            <div className="mb-3">
              <label className="form-label">Contraseña</label>
              <input
                type="password"
                name="password"
                onChange={handleOnChange}
                className="form-control"
              />
            </div>

            <button type="submit" className="btn btn-primary w-100 mt-3">
              Entrar
            </button>

          </form>
        )}


      </div>
    </div>
  );
}

export default LoginPage;
