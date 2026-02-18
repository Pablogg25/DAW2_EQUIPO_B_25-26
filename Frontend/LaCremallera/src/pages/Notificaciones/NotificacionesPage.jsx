import "./NotificacionesPage.css";
import $notificacionesController from "../../core/TestController/TestNotificacionesController";

import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";

function NotificacionesPage(){

    const [notificaciones,setNotificaciones]=useState([]);

    const cargarDatos= async()=>{
        console.log("Cargando datos");

        let datos=await $notificacionesController.getNotificaciones();

        setNotificaciones(datos);
    }

    const onCreateNotificacion=()=>{
        console.log("On create notificacion");
    }

    const onEditNotificacion=(notificacionId)=>{
        console.log("On edit Notificacion id: "+notificacionId);
    }

    const onDeleteNotificacion=(notId)=>{
        console.log("On delete notificación id: "+notId);
    }

    useEffect(()=>{
        cargarDatos();
    },[])

    return(
        <>
        <div>Página notificaciones</div>
        </>
    )
}

export default NotificacionesPage;