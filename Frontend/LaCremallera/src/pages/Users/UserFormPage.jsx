import { useState, useEffect } from "react";
import { useNavigate, useParams } from "react-router-dom";

import $usersController from "../../core/UsersController";


function UserFormPage() {
    const [userData, setUserData] = useState({
        nombre: "", telefono: "", email: "", direccion: "",
        username: "", rol: "", fecha_registro: "", password: "", confirm_password: ""
    });

    const navegar = useNavigate();

    const { id } = useParams();

    const cargarDatos = async () => {
        console.log("cargando datos");

        if (id != 0) {
            console.log("Modo update");
            //obtener datos
            let datos = await $usersController.getUser(id);

            if (datos.success) {
                console.log(datos);
                setUserData(datos.data);
            } else {
                alert("ERROR, "+datos);
                navegar("/users");
            }
        }
        //else modo create
    }

    const handleOnSubmit = (evento) => {
        evento.preventDefault();
        console.log("UserFormPage: onSubmit");

        //enviar datos
        enviarDatos();
    }

    const enviarDatos = async () => {
        console.log("Enviando datos");
        let success;
        let statusCode = 0;

        if (id != 0) {
            //update
            const response = await $usersController.updateUser(userData, id);
            success = response.success;
            statusCode = response.estado;
        } else {
            //antes comprobar que confirm password es correcto

            if (userData.confirm_password != userData.password) {
                console.log("ERROR, confirm password y password no coincide");
                alert("ERROR: su contraseña no está confirmada, escríbala correctamente");
                return;
            }
            const response = await $usersController.createUser(userData);
            success = response.success;
            statusCode = response.estado;
        }
        if (success) {
            //TODO: insertar context para guardar datos de login
            navegar("/users");
        } else {
            alert("Error, ha surgido un error al procesar su petición.\nCodigo de error: " + statusCode);
        }
    }

    const handleOnCancel = (evento) => {
        evento.preventDefault();
        navegar("/users");
        //vuelve a la página de usuarios
    }

    const handleOnChange = (evento) => {
        const { name, value } = evento.target;
        let actualizar = { ...userData, [name]: value };
        setUserData(actualizar);
    }

    function reformatRegisterDate(registerDate){

        let newDate=registerDate.split(' ')[0];
        return newDate;
    }

    useEffect(() => {
        cargarDatos(id);
    }, [id]);


    return (
        <div>
            <div>Formulario create/update usuarios</div>

            <form onSubmit={handleOnSubmit}>
                <div>Formulario datos Usuario</div>

                <div>
                    <div>Nombre:</div>
                    <input type="text" name="nombre" id="nombre" value={userData.nombre} onChange={handleOnChange} />
                </div>

                <div>
                    <div>Teléfono:</div>
                    <input type="text" name="telefono" id="telefono" value={userData.telefono} onChange={handleOnChange} />
                </div>

                <div>
                    <div>Dirección:</div>
                    <input type="text" name="direccion" id="direccion" value={userData.direccion} onChange={handleOnChange} />
                </div>

                <div>
                    <div>Correo (único):</div>
                    <input type="text" name="email" id="email" value={userData.email} onChange={handleOnChange} />
                </div>

                <div>
                    <div>Nombre de usuario (único):</div>
                    <input type="text" name="username" id="username" value={userData.username} onChange={handleOnChange} />
                </div>

                {(id == 0)? (
                    // insertar password
                    <div>
                        <div>Contraseña:</div>
                        <input type="password" name="password" id="password" onChange={handleOnChange} />
                        <div>Confirmar contraseña:</div>
                        <input type="password" name="confirm_password" id="confirm_password" onChange={handleOnChange} />
                    </div>

                ):(
                    <div>
                        <div>Fecha de registro</div>
                        <input type="date" value={reformatRegisterDate(userData.fecha_registro)} disabled={true} />
                    </div>
                )}

                <div>
                    <div>rol:</div>
                    <select type="text" name="rol" id="rol" onChange={handleOnChange}>
                        <option value="cliente" selected={userData.rol=="cliente"}>Cliente</option>
                        <option value="empleado" selected={userData.rol=="empleado"}>Empleado</option>
                        <option value="Admin" selected={userData.rol=="admin"}>Admin</option>
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
