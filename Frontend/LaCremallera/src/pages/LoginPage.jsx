import React, { useState, useContext } from "react";
import { useNavigate } from "react-router-dom";
import $usersController from "../core/UsersController";
import { AuthContext } from "../context/AuthContext";

function LoginPage() {
  const [userCred, setUserCred] = useState({ username: "", password: "" });
  const navigate = useNavigate();
  const { login } = useContext(AuthContext);

  const handleOnSubmit = async (event) => {
    event.preventDefault();

    const response = await $usersController.loginUser(userCred);

    if (response.success) {
      if (response.data === true) {
        // Obtener datos del usuario
        const userData = await $usersController.getUserByUsername(
          userCred.username,
        );

        if (userData.success) {
          login(userData.data); // Guardar usuario en contexto
          alert("Login correcto");
          navigate("/");
        } else {
          alert("No se pudo obtener la información del usuario");
        }
      } else {
        alert("Credenciales incorrectas");
      }
    } else {
      alert("Error en login. Código: " + response.status);
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

        <form onSubmit={handleOnSubmit}>
          <div className="mb-3">
            <label className="form-label">Usuario</label>
            <input
              type="text"
              name="username"
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
      </div>
    </div>
  );
}

export default LoginPage;
