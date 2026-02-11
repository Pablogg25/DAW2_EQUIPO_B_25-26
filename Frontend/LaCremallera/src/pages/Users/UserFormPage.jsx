import { useState, useEffect } from "react";
import { useNavigate, useParams } from "react-router-dom";

import $usersController from "../../core/UsersController";


function UserFormPage(){
    const [userData,setUserData]=useState({
        nombre:"",telefono:"",email:"",direccion:"",
        username:"",rol:"",fecha_registro:"",password:"",confirm_password:""
    });

    const navegar=useNavigate();

    const {id}=useParams();

    const cargarDatos=async()=>{
        console.log("cargando datos");

        if(id!=0){
            console.log("Modo update");
            //obtener datos
        }
        //else modo create
    }

    const handleOnSubmit=(evento)=>{
        evento.preventDefault();
        console.log("UserFormPage: onSubmit");

        //enviar datos

        //si es update o create

        //si success o no
    }

    const enviarDatos=async ()=>{
        console.log("Enviando datos");
    }

    const handleOnCancel=(evento)=>{
        evento.preventDefault();
        navegar("/users");
        //vuelve a la página de usuarios
    }

    const handleOnChange=(evento)=>{
        const {name,value}=evento.target;
        let actualizar={...userData,[name]:value};
        setUserData(actualizar);
    }

    useEffect(()=>{
        cargarDatos(id);
    },[id]);


    return (
        <div>
            <div>Formulario create/update usuarios</div>

            <form action={handleOnSubmit}>
                <div>Formulario datos Usuario</div>

                <div>
                    <div>Nombre:</div>
                    <input type="text" name="nombre" id="nombre" value={userData.nombre} onChange={handleOnChange}/>
                </div>

                <div>
                    <div>Teléfono:</div>
                    <input type="text" name="telefono" id="telefono" value={userData.telefono} onChange={handleOnChange}/>
                </div>

                <div>
                    <div>Correo (único):</div>
                    <input type="text" name="email" id="email" value={userData.email} onChange={handleOnChange}/>
                </div>

                <div>
                    <div>Nombre de usuario (único):</div>
                    <input type="text" name="username" id="username" value={userData.username} onChange={handleOnChange}/>
                </div>

                {id!=0 && (
                    // insertar password
                    <div>
                        <div>Contraseña:</div>
                        <input type="password" name="password" id="password" onChange={handleOnChange}/>
                        <div>Confirmar contraseña:</div>
                        <input type="password" name="confirm_password" id="confirm_password" onChange={handleOnChange}/>
                    </div>
                
                )}

                {id==0 &&(
                    <div>
                        <div>Fecha de registro</div>
                        <input type="date" value={userData.fecha_registro} disabled={true}/>
                    </div>
                )}

                <div>
                    <div>rol:</div>
                    <select type="text" name="rol" id="rol" onChange={handleOnChange}>
                        <option value="cliente">Cliente</option>
                        <option value="empleado">Empleado</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>

                <div>
                    <button type="submit">Enviar datos</button>
                    <button onClick={handleOnCancel}>Cancelar</button>
                </div>
            </form>
        </div>
    );
}

export default UserFormPage;
