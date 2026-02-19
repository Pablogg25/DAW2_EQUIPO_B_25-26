import "./FacturasPage.css";
import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";

import $facturasController from "../../core/TestController/TestFacturasController";
import $usuariosController from "../../core/TestController/TestUsersController";

function FacturasPage(){
    const [facturas,setFacturas]=useState([]);
    const [usuarios,setUsuarios]=useState([]);

    const navegar=useNavigate();

    const cargarDatos=async()=>{
        console.log("Cargando datos");

        let datos=await $facturasController.getFacturas()

        setFacturas(datos);

        let datosUsuario=await $usuariosController.getUsuarios();
        setUsuarios(datosUsuario);

    }

    const onCreateFactura=()=>{
        console.log("On create factura");

        //navegar
        
    }

    const onEditFactura=(facturaId)=>{
        console.log("On edit factura id: "+facturaId);

        //navegar
    }

    const onDeleteFactura=(facturaId)=>{
        console.log("On delete factura id: "+facturaId);

        //ejecutar petición 
    }


    useEffect(()=>{
        cargarDatos();
    },[]);

    function getUsuarioName(usuarioId) {
        if (usuarios) {
            let index = usuarios.findIndex(p => p.usuarioId == usuarioId);

            if (index !== -1) {
                return usuarios[index];
            }
        }

    }

    function getTotalFactura(factura){
        if(factura["total_calculado"]){
            return factura["total_calculado"];
        }
        //else
        let calc=0;
        for(let t of factura["trabajos"]){
            calc+=parseInt(t["precio"]);
        }
        return calc;
    }

    return(
        <>
        <div>Página facturas</div>

        <div>
            <button onClick={()=>{onCreateFactura();}}>Crear factura</button>
        </div>

        <div>
            {/* tabla */}
            <div className="tableRow">
                <div>
                    <strong>Id</strong>
                </div>
                <div>
                    <strong>Usuario</strong>
                </div>
                <div>
                    <strong>Fecha</strong>
                </div>
                <div>
                    <strong>Pago</strong>
                </div>
                <div>
                    <strong>Total factura</strong>
                </div>
                <div>
                    <strong>Número de trabajo</strong>
                </div>
                <div>
                    <strong>Operaciones</strong>
                </div>
            </div>

            {/* filas */}

            {facturas.map((elemento)=>{
                return (
                    <div key={elemento["facturaId"]} className="tableRow">
                        <div>{elemento["facturaId"]}</div>
                        <div>{getUsuarioName(elemento["usuarioId"])}</div>
                        <div>{elemento["fecha"]}</div>
                        <div>{elemento["pagado"]}</div>
                        <div>{getTotalFactura(elemento)}</div>
                        <div>{elemento["trabajos"].length}</div>
                        <div>
                            <button onClick={()=>{onEditFactura(elemento["facturaId"])}}>Ver/editar</button>
                            <button onClick={()=>{onDeleteFactura(elemento["facturaId"])}}>Eliminar</button>
                        </div>
                    </div>
                )
            })}
        </div>
        </>
    )
}

export default FacturasPage;