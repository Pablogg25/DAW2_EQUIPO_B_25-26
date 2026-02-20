import { useState, useEffect } from "react";
import { useNavigate, useParams } from "react-router-dom";

import $facturasController from "../../core/TestController/TestFacturasController";
import $usuariosController from "../../core/TestController/TestUsersController";
import $ordersController from "../../core/TestController/TestOrdersController";

function FacturaFormPage() {
    const [facturaDatos, setFacturaDatos] = useState({
        "facturaId": 0,
        "usuarioId": 1,
        "fecha": "",
        "pagado": 0,
        "total_calculado": null,
        //   trabajosId
        "trabajos": []
        // [{
        //   "trabajoId": 1,
        //   "prendaId": 1,
        //   "empleadoId": 3,
        //   "descripcion": "Bajo completo y ajuste lateral",
        //   "fecha_inicio": "2025-11-20",
        //   "fecha_entrega": "2025-11-25",
        //   "estado": "en_proceso",
        //   "precio": "12.50",
        //   "pivot": {
        //     "trabajoId": 1,
        //     "facturaId": 1
        //   }
        // }]

    })
    const [usuariosData, setUsuariosData] = useState([]);
    const [trabajosData, setTrabajosData] = useState([]);

    //array trabajos a añadir
    //array trabajos a eliminar

    const navegar = useNavigate();

    const { id } = useParams();

    const cargarDatos = async () => {
        console.log("Cargando datos");

        if (usuariosData.length == 0) {
            let datosUsuario = await $usuariosController.getUsuarios();
            // let datosUsuario = await $usersController.getUsers();

            if (datosUsuario) {
                setUsuariosData(datosUsuario);
                // setUsuariosData(datosUsuario.data);
            } else {
                alert("Ha surgido un error al cargar datos");
                navegar("/notificaciones");
            }

        }

        if (trabajosData.length == 0) {
            let datosTrabajo = await $ordersController.getOrders();

            if (datosTrabajo) {
                setTrabajosData(datosTrabajo);
            } else {
                alert("Ha surgido un error al cargar datos");
                navegar("/notificaciones");
            }
        }

        let datos = await $facturasController.getFactura(id);

        setFacturaDatos(datos);
    }


    const handeOnSubmit = (evento) => {
        evento.preventDefault();
        console.log("FacturaFormPage onSubmit");
        enviarDatos();
    }

    const enviarDatos = async () => {
        console.log("Enviar datos");

        console.log(facturaDatos);

        let result;

        if (id != 0) {
            console.log("Modo update");

            let datos = { ...facturaDatos, ["facturaId"]: id };
            result = await $facturasController.updateFactura(datos);

        } else {
            console.log("Modo create");
            result = await $facturasController.createFactura(facturaDatos);
        }

        // if (result.success) {
        //     alert("Datos enviados correctamente");
        //     navegar("/facturas");
        // } else {
        //     alert("Ha surgido un error al enviar datos");
        // }
    }

    const handleOnCancel = (evento) => {
        evento.preventDefault();
        navegar("/facturas");
    }

    const handleOnChange = (evento) => {
        const { name, value } = evento.target;
        let actualizar = { ...facturaDatos, [name]: value };
        setFacturaDatos(actualizar);
    }

    const handleOnAddItem=()=>{

    }

    const handleOnRemoveItem=(trabajoId)=>{

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

    function getTotalFactura(factura) {
        if (factura["total_calculado"]) {
            return factura["total_calculado"];
        }
        //else está vacío
        if (!factura["trabajos"]) {
            return 0;
        }
        //else calcular
        let calc = 0;
        for (let t of factura["trabajos"]) {
            calc += parseInt(t["precio"]);
        }
        return calc;
    }

    console.log(facturaDatos);

    return (
        <>
            <div>Factura form page</div>


            <form onSubmit={handeOnSubmit}>
                <div>formulario de datos de factura</div>

                <div>
                    <div>Usuario</div>
                    <select name="usuarioId" id="usuarioId" onChange={handleOnChange}
                        value={facturaDatos.usuarioId}>
                        {usuariosData.map((elemento) => {
                            return (
                                <option key={elemento["usuarioId"]} value={elemento["usuarioId"]}>
                                    {elemento["nombre"]}
                                </option>
                            )
                        })}
                    </select>
                </div>

                <div>
                    <div>Fecha</div>
                    <input type="date" name="fecha" id="fecha" value={facturaDatos.fecha} onChange={handleOnChange} />
                </div>

                <div>
                    <div>Pagado:</div>
                    <input type="checkbox" name="pagado" id="pagado" checked={facturaDatos.pagado==1} onChange={handleOnChange} />
                </div>

                <div>
                    <div>Objetos</div>
                    <div>Dropdown con trabajos y botón para insertar</div>

                    <div>
                        trabajos:
                    </div>

                    <select name="trabajos" id="trabajos">
                        {trabajosData.map((elemento)=>{
                            return(
                                <option key={elemento["trabajoId"]} value={elemento["trabajoId"]}>
                                    {elemento["trabajoId"]} - {elemento["descripcion"]} - {elemento["precio"]} €
                                </option>
                            )
                        })}
                    </select>
                    <div>
                        <button onClick={()=>{handleOnAddItem();}}>Añadir item</button>
                    </div>

                    <div>Lista items</div>
                    <div>
                        {facturaDatos["trabajos"].map((elemento) => {
                            return (<div key={elemento["trabajoId"]}>
                                {elemento["trabajoId"]} - {elemento["descripcion"]} - {elemento["precio"]} € 
                                <button onClick={()=>{handleOnRemoveItem(elemento["trabajoId"])}}>-Eliminar-</button>
                            </div>)
                        })}
                    </div>

                    <div>
                        Total: <input type="number" value={getTotalFactura(facturaDatos)} disabled />
                    </div>
                </div>

                <div>
                    <button type="submit">Enviar datos</button>
                    <button onClick={() => { handleOnCancel(); }}>Volver</button>
                </div>

            </form>
        </>
    )
}

export default FacturaFormPage;