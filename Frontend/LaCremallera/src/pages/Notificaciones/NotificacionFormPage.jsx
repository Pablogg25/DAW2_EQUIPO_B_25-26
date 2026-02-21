import { useState, useEffect, useContext } from "react";
import { useNavigate, useParams } from "react-router-dom";

// import $notificacionesController from "../../core/TestController/TestNotificacionesController";
import $notificacionesController from "../../core/NotificacionesController";
import $usersController from "../../core/UsersController";
import $ordersController from "../../core/OrdersController";
import AuthProvider from "../../context/AuthProvider";

function NotificacionFormPage() {
    const [notificacionData, setNotificacionData] = useState({
        notificacionId: 0,
        receptorId: 1,
        remitenteId: 1,
        trabajoId: 1,
        tipo: "",
        asunto: "",
        mensaje: "",
        fecha_envio: ""
    });
    const [usuariosData, setUsuariosData] = useState([]);
    const [trabajosData, setTrabajosData] = useState([]);

    const navegar = useNavigate();
    //useContext
    const [usuario]=useContext(AuthProvider);

    const { id } = useParams();

    const cargarDatos = async () => {
        console.log("Cargando datos");

        if (usuariosData.length == 0) {
            let datosUsuario = await $usersController.getUsers();

            if (datosUsuario.success) {
                setUsuariosData(datosUsuario.data);
            } else {
                alert("Error, ha surgido un error al procesar su petición.\nCodigo de error: " + datosUsuario.status);

                navegar("/notificaciones");
            }


        }

        if (trabajosData.length == 0) {
            let datosTrabajo = await $ordersController.getOrders();

            

            if (datosTrabajo.success) {
                setTrabajosData(datosTrabajo.data);
            } else {
                alert("Error, ha surgido un error al procesar su petición.\nCodigo de error: " + datosTrabajo.status);

                navegar("/notificaciones");
            }

        }

        if (id != 0) {
            let datosNot = await $notificacionesController.getNotificacion(id);
            if (datosNot.success) {
                setNotificacionData(datosNot.data);
            } else {
                alert("Error, ha surgido un error al procesar su petición.\nCodigo de error: " + datosNot.status);

            }

        }


    }

    const handeOnSubmit = (evento) => {
        evento.preventDefault();
        console.log("NotificacionFormPage onSubmit");
        enviarDatos();
    }

    const enviarDatos = async () => {
        console.log("Enviar datos");

        console.log(notificacionData);

        let result;

        if (id != 0) {
            console.log("Modo update");

            let datos = { ...notificacionData, ["notificacionId"]: id };
            result = await $notificacionesController.updateNotificacion(datos);

        } else {
            console.log("Modo create");
            result = await $notificacionesController.createNotificacion(notificacionData);

        }

        if (result.success) {
            alert("Datos enviados correctamente");
            navegar("/notificaciones");
        } else {
            if (result.estado == 404) {
                alert("Error 404, no se ha podido encontrar la notificación")
            } else if (result.estado == 400) {
                alert("Error 400: error de validación: compruebe que los campos están correctamente rellenados");
            } else {
                alert("Error, ha surgido un error al procesar su petición.\nCodigo de error: " + result.estado);

            }

        }

    }

    const handleOnCancel = (evento) => {
        evento.preventDefault();
        navegar("/notificaciones");
    }

    const handleOnChange = (evento) => {
        const { name, value } = evento.target;
        let actualizar = { ...notificacionData, [name]: value };
        setNotificacionData(actualizar);
    }

    useEffect(() => {
        cargarDatos();
    }, []);

    function formatDateToInput(date) {
        if (!date) {
            return "";
        }
        //2026-02-18 17:24:20" does not conform to the required format, "yyyy-MM-dd"
        // console.log("Parsing date: "+date);

        let dateObj = new Date(date);
        let newFormat = dateObj.toISOString().split("T")[0];
        // console.log("Parsing "+date+" to "+newFormat);

        return newFormat;
    }

    return (
        <>
            <div>Notificación form page</div>

            <form onSubmit={handeOnSubmit}>
                <div>Formulario datos de notificación</div>

                <div>
                    <div>receptor</div>
                    <select name="receptorId" id="receptorId" onChange={handleOnChange}
                        value={notificacionData.receptorId}>
                        {usuariosData.map((elemento) => {
                            return (
                                <option value={elemento["usuarioId"]}>
                                    {elemento["nombre"]}
                                </option>
                            )
                        })}
                    </select>
                </div>

                <div>
                    <div>remitente</div>
                        {/* si estoy creando solo uno mismo peude ser el remitente */}
                    {
                        (id!=0)? (<select name="remitenteId" id="remitenteId" onChange={handleOnChange}
                        value={notificacionData.remitenteId}>
                        {usuariosData.map((elemento) => {
                            return (
                                <option value={elemento["usuarioId"]}>
                                    {elemento["nombre"]}
                                </option>
                            )
                        })}
                    </select>):(<select name="remitenteId" id="remitenteId" onChange={handleOnChange}
                        value={usuario.usuario} disabled={true}>
                        {usuariosData.map((elemento) => {
                            return (
                                <option value={elemento["usuarioId"]}>
                                    {elemento["nombre"]}
                                </option>
                            )
                        })}
                    </select>)
                    }
                    
                </div>

                <div>
                    <div>Trabajo</div>
                    <select name="trabajoId" id="trabajoId" onChange={handleOnChange}
                        value={notificacionData.trabajoId}>
                        {trabajosData.map((elemento) => {
                            return (
                                <option value={elemento["trabajoId"]}>
                                    {elemento["descripcion"]}
                                </option>
                            )
                        })}
                    </select>
                </div>

                {/* 'notificacion', 'recordatorio_entrega', 'trabajo_listo', 'factura_generada' */}
                <div>
                    <div>tipo</div>
                    <select name="tipo" id="tipo" onChange={handleOnChange}
                        value={notificacionData.tipo}>
                        <option value="notificacion">notificacion</option>
                        <option value="recordatorio_entrega">recordatorio_entrega</option>
                        <option value="trabajo_listo">trabajo_listo</option>
                        <option value="factura_generada">factura_generada</option>
                    </select>
                </div>

                <div>
                    <div>Asunto:</div>
                    <input type="text" name="asunto" id="asunto" onChange={handleOnChange}
                        value={notificacionData.asunto} />
                </div>

                <div>
                    <div>Mensaje</div>
                    <textarea name="mensaje" id="mensaje" onChange={handleOnChange}
                        value={notificacionData.mensaje} />
                </div>

                <div>
                    <div>Fecha de envío</div>
                    <input type="date" name="fecha_envio" id="fecha_envio"
                        value={formatDateToInput(notificacionData.fecha_envio)}
                        onChange={handleOnChange}
                        disabled={true} />
                </div>

                <div>
                    <button type="submit">Enviar datos</button>
                    <button onClick={handleOnCancel}>Volver</button>
                </div>

            </form>
        </>
    )
}

export default NotificacionFormPage;