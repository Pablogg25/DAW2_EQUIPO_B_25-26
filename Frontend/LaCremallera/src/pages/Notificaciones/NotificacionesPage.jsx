import "./NotificacionesPage.css";
// import $notificacionesController from "../../core/TestController/TestNotificacionesController";
import $notificacionesController from "../../core/NotificacionesController";
import $usersController from "../../core/UsersController";
import $ordersController from "../../core/OrdersController";

import { useState, useEffect, useContext } from "react";
import { useNavigate } from "react-router-dom";
import AuthProvider from "../../context/AuthProvider";

function NotificacionesPage() {

    const [notificaciones, setNotificaciones] = useState([]);
    const [usuarios, setUsuarios] = useState([]);
    const [trabajos, setTrabajos] = useState([]);
    //datos usuario
    //datos trabajo

    const navegar = useNavigate();

    //useContext
    const [usuario]=useContext(AuthProvider);

    const cargarDatos = async () => {
        console.log("Cargando datos");

        let datos;

        if(usuario.rol!="admin"){
            console.log("Cargando notificaciones de empleado");
            datos = await $notificacionesController.getNotificaciones({
                "receptorId":usuario.usuarioId,
            });
        }else{
            datos = await $notificacionesController.getNotificaciones([]);
        }

        
        if (datos.success) {
            setNotificaciones(datos.data);
        } else {
            alert("Ha surgido un error al cargar los datos de notificaciones");
        }


        if (usuarios.length == 0) {
            console.log("Cargando datos de usuario");
            let datosUsuario = $usersController.getUsers();
            setUsuarios(datosUsuario.data);
        }

        if (trabajos.length == 0) {
            console.log("Cargando datos de trabajos");
            let datosTrabajo = $ordersController.getOrders();
            setTrabajos(datosTrabajo.data);
        }

    }

    const onCreateNotificacion = () => {
        console.log("On create notificacion");
        navegar("/notificaciones/0");
    }

    const onEditNotificacion = (notificacionId) => {
        console.log("On edit Notificacion id: " + notificacionId);
        navegar("/notificaciones/" + notificacionId);
    }

    const onDeleteNotificacion = async (notId) => {
        console.log("On delete notificación id: " + notId);

        if (notId) {
            if (confirm("¿Seguro que desea eliminar la notificación?")) {
                let result = await $notificacionesController.deleteNotificacion(notId);

                if (result.success) {
                    cargarDatos();
                    navegar("/notificaciones");
                } else {
                    if (result.estado == 409) {
                        alert("Error 409: no se puede eliminar la notificación porque es dependiente de otro elemento en la base de datos")
                    } else {
                        alert("Error, ha surgido un error al procesar su petición.\nCodigo de error: " + result.estado);

                    }

                }
            }
        }
    }

    useEffect(() => {
        cargarDatos();
    }, []);

    function getUsuarioName(usuarioId) {
        if (usuarios) {
            let index = usuarios.findIndex(p => p.usuarioId == usuarioId);

            if (index !== -1) {
                return usuarios[index];
            }
        }

    }

    return (
        <>
            <div>Página notificaciones</div>
            <div>Lista para realizar crud sobre notificaciones</div>

            <div>
                <button onClick={() => {
                    onCreateNotificacion();
                }}>Crear Notificación</button>
            </div>

            <div>
                <div className="tableRow">
                    <div>
                        <strong>Id</strong>
                    </div>
                    <div>
                        <strong>Receptor</strong>
                    </div>
                    <div>
                        <strong>Remitente</strong>
                    </div>
                    <div>
                        <strong>Trabajo</strong>
                    </div>
                    <div>
                        <strong>Tipo</strong>
                    </div>
                    <div>
                        <strong>Asunto</strong>
                    </div>
                    <div>
                        <strong>Mensaje</strong>
                    </div>
                    <div>
                        <strong>Fecha de envío</strong>
                    </div>
                    <div>
                        <strong>Operaciones</strong>
                    </div>
                </div>

                {/* filas */}

                {notificaciones.map((elemento) => {
                    return (
                        <div key={elemento["notificacionId"]} className="tableRow">
                            <div>{elemento["notificacionId"]}</div>
                            <div>{getUsuarioName(elemento["receptorId"])}</div>
                            <div>{getUsuarioName(elemento["remitenteId"])}</div>
                            <div>{elemento["trabajoId"]}</div>
                            <div>{elemento["tipo"]}</div>
                            <div>{elemento["asunto"]}</div>
                            <div>{elemento["mensaje"]}</div>
                            <div>{elemento["fecha_envio"]}</div>
                            <div>
                                <button onClick={() => { onEditNotificacion(elemento["notificacionId"]) }}>Ver/editar</button>
                                <button onClick={() => { onDeleteNotificacion(elemento["notificacionId"]) }}>Eliminar</button>
                            </div>
                        </div>
                    )
                })}
            </div>
        </>
    )
}

export default NotificacionesPage;