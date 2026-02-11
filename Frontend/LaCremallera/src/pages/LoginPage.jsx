import React from "react";
import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";

import $usersController from "../core/UsersController";


function LoginPage() {
  const [userCred,setUserCred]=useState({
    username:"",password:""
  });


  const navegar=useNavigate();


  const handleOnSubmit=(event)=>{
    event.preventDefault();
    enviarDatos();
  }

  const enviarDatos=async()=>{
    console.log("Enviar datos");

    const response=await $usersController.loginUser(userCred);
      console.log(response);

    if(response.success){
      console.log("Datos recividos");
      if(response.data){
        alert("login correcto");

        //TODO: insertar información del rol y permisos de usuario 
        // en context
        // TODO: hacer context

        navegar("/");
      }else{
        alert("Credenciales incorrectas");
      }
    }

  }

  const handleOnChange = (evento) => {
        const { name, value } = evento.target;
        let actualizar = { ...userCred, [name]: value };
        setUserCred(actualizar);
    }

  const handleOnCancel=(event)=>{
    //TODO: navegar a homepage
    event.preventDefault();
    navegar("/");
  }


  return (<div>
    <div>
      Página Login
    </div>

    <form onSubmit={handleOnSubmit}>
      <div>
        <div>
          Nombre de usuario:
        </div>
        <input type="text" name="username" id="username" onChange={handleOnChange}/>
      </div>

      <div>
        <div>
          password:
        </div>
        <input type="password" name="password" id="password" onChange={handleOnChange}/>
      </div>

      <div>
        <button type="submit">Conectarse</button>
        <button onClick={()=>{handleOnCancel();}}>Cancelar</button>
      </div>
      
    </form>

  </div>);
}
export default LoginPage;
