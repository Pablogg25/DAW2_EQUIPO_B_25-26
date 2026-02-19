
import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";

import $facturasController from "../../core/TestController/TestFacturasController";

function FacturasPage(){
    const [facturas,setFacturas]=useState([]);

    const navegar=useNavigate();

    const cargarDatos=async()=>{
        console.log("Cargando datos");

        let datos=await $facturasController.getFacturas()
    }
}

export default FacturasPage;