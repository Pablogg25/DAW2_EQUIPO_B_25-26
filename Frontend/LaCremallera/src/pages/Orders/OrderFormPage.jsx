import { useState, useEffect } from "react";
import { useNavigate, useParams } from "react-router-dom";

import $ordersController from "../../core/OrdersController";
// import $usuariosController from "../../core/TestController/TestUsersController";
import $usersController from "../../core/UsersController";
import $prendasController from "../../core/PrendasController";
import $inventarioController from "../../core/InventoryController";


function OrderFormPage() {
    const [orderData, setOrderData] = useState({
        descripcion: "", empleado: 0, estado: "",
        fecha_entrega: "", fecha_inicio: "",
        precio: 0, prenda: "", trabajo_id: 0
    });

    const [usuariosData, setUsuarioData] = useState([]);
    const [prendasData, setPrendasData] = useState([]);

    //consumos {"success":true,"data":[{"trabajoId":1,"itemId":1,"cantidad_usada":2}]}
    const [consumoDatos, setConsumoDatos] = useState([]);
    const [inventarioData, setInventarioData] = useState([]);

    const { id } = useParams();
    const [nuevoConsumo,setNuevoConsumo]=useState({trabajoId:id,itemId:0,cantidad_usada:0})

    const cargarDatos = async () => {
        console.log("cargando datos OrderFormPage");
        if (id != 0) {
            // console.log("Modo edit / ver");
            //modo edit
            let datos = await $ordersController.getOrder(id);
            console.log(datos);
            if (datos.success) {
                setOrderData(datos.data);

                //cargar consumos
                // console.log("Realizando petición a consumos");

                let requestConsumo = await $ordersController.getConsumos(id);

                if (requestConsumo.success) {
                    // console.log("datos de request consumos"),
                    // console.log(requestConsumo.data);
                    setConsumoDatos(requestConsumo.data)
                } else {
                    // console.log("No se han cargaod bien los consumos");
                    alert("Error, ha surgido un error al procesar su petición.\nCodigo de error: " + datos.status);

                    navegar("/orders");
                }

            } else {
                alert("Error, ha surgido un error al procesar su petición.\nCodigo de error: " + datos.status);

                navegar("/orders");
            }

        }
        //else modo create

        //TODO, cargar empleados y prendas por ids
        let datosUsuario = await $usersController.getUsers([]);
        if (datosUsuario.success) {
            setUsuarioData(datosUsuario.data);
        } else {
            alert("Error, ha surgido un error al procesar su petición.\nCodigo de error: " + datosUsuario.status);

            navegar("/orders");
        }

        let datosPrenda = await $prendasController.getPrendas([]);

        if (datosPrenda.success) {
            setPrendasData(datosPrenda.data);
        } else {
            alert("Error, ha surgido un error al procesar su petición.\nCodigo de error: " + datosUsuario.status);

            navegar("/orders");
        }

        let datosInventario = await $inventarioController.obtenerInventario([]);

        if (datosInventario.success) {
            setInventarioData(datosInventario.data);
            //establecer consumo al primer elemento de la lista por defecto
            setNuevoConsumo({trabajoId:id,itemId:datosInventario.data[0].itemId,cantidad_usada:0});
        } else {
            alert("Error, ha surgido un error al procesar su petición.\nCodigo de error: " + datosUsuario.status);

            navegar("/orders");
        }

    }

    const navegar = useNavigate();

    const handleOnSubmit = (evento) => {
        evento.preventDefault();
        console.log("OrdersFormPage: onsubmit");

        enviarDatos();
    }

    const enviarDatos = async () => {
        let success;
        let statusCode = 0;
        if (id != 0) {
            //update
            const response = await $ordersController.updateOrder(orderData, id);
            success = response.success;
            statusCode = response.estado;
        } else {
            const response = await $ordersController.createOrder(orderData);
            success = response.success;
            statusCode = response.estado;
        }

        if (success) {
            navegar("/orders");
        } else {
            if (statusCode == 400) {
                alert("Error de validación: compruebe que ha rellenado correctamente los campos");
            } else {
                alert("Error, ha surgido un error al procesar su petición.\nCodigo de error: " + statusCode);

            }
        }

    }

    //formulario consumos
    const handleOnSubmitConsumos = (evento) => {
        evento.preventDefault();
        console.log("OrderFormPage handle on submit consumos");
        enviarDatosConsumo();
    }

    const enviarDatosConsumo = async () => {
        console.log("Enviar datos de consumo");

        let result = await $ordersController.asociarConsumo(id, nuevoConsumo);

        if (result.success) {
            alert("Datos guardados correctamente");
            cargarDatos();
        } else {
            alert("Error, ha surgido un error al procesar su petición.\nCodigo de error: " + result.status);

        }
    }

    const handleOnChangeConsumo = (evento) => {
        const { name, value } = evento.target;
        let actualizar = { ...consumoDatos, [name]: value };
        setNuevoConsumo(actualizar);
    }

    const handleOnCancel = (evento) => {
        evento.preventDefault();
        navegar("/orders");
    }

    //metodo para adaptar formulario
    const handleOnChange = (evento) => {
        const { name, value } = evento.target;
        let actualizar = { ...orderData, [name]: value };
        setOrderData(actualizar);
    }

    useEffect(() => {
        cargarDatos(id);
    }, [id]);

    function getNombreItem(itemId) {
        let index = inventarioData.findIndex(p => p.itemId == itemId);

        if (index !== -1) {
            return inventarioData[index].nombre;
        }
        return "n/a";
    }


    return (
        <div>
            <div>Formulario create/update</div>

            <form onSubmit={handleOnSubmit}>
                <div>
                    Formulario de datos Order
                </div>
                {/* formulario */}
                <div>
                    <div> Descripción: </div>
                    <input type="text" name="descripcion" id="descripcion" value={orderData.descripcion} onChange={handleOnChange} />
                </div>
                <div>
                    <div> Prenda: </div>
                    <select type="text" name="prendaId" id="prendaId" onChange={handleOnChange} >
                        {prendasData.map((elemento) => {
                            return (
                                <option key={elemento.prendaId} value={elemento.prendaId} selected={orderData.prenda == elemento.prendaId}>{elemento.tipo}</option>
                            );
                        })}
                    </select>
                </div>
                <div>
                    <div> Empleado: </div>
                    {/* drop down con los empleados disponibles */}
                    <select name="empleadoId" id="empleadoId" onChange={handleOnChange}>
                        {usuariosData.map((elemento) => {
                            return (
                                <option key={elemento.usuarioId} value={elemento.usuarioId} selected={orderData.empleado == elemento.usuarioId}>{elemento.nombre}</option>
                            );
                        })}
                    </select>
                </div>
                <div>
                    <div> fecha de inicio: </div>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" value={orderData.fecha_inicio} onChange={handleOnChange} />
                </div>
                <div>
                    <div> fecha de entrega: </div>
                    <input type="date" name="fecha_entrega" id="fecha_entrega" value={orderData.fecha_entrega} onChange={handleOnChange} />
                </div>
                <div>
                    <div> precio: </div>
                    <input type="number" name="precio" id="precio" value={orderData.precio} onChange={handleOnChange} />
                </div>
                <div>
                    <div> estado: </div>
                    <select name="estado" id="estado" onChange={handleOnChange}>
                        <option value="pendiente" selected={orderData.estado == "pendiente"}>pendiente</option>
                        <option value="en_proceso" selected={orderData.estado == "en_proceso"}>En proceso</option>
                        <option value="listo" selected={orderData.estado == "listo"}>listo</option>
                        <option value="entregado" selected={orderData.estado == "entregado"}>entregado</option>
                    </select>
                </div>

                <div>
                    <button type="submit">Enviar datos</button>
                    <button onClick={handleOnCancel}>Cancelar</button>
                </div>
            </form>

            <form onSubmit={handleOnSubmitConsumos}>
                {/* dropdown con items de inventario */}
                <select name="itemId" id="itemId" value={nuevoConsumo.itemId} onChange={handleOnChangeConsumo}>
                    {inventarioData.map((elemento) => {
                        return (
                            <option key={elemento["itemId"]} value={elemento["itemId"]}>{elemento["nombre"]}</option>
                        )
                    })}
                </select>
                <div>
                    Cantidad usada:
                    <input type="number" name="cantidad_usada" id="cantidad_usada" min={0} value={nuevoConsumo.cantidad_usada} onChange={handleOnChangeConsumo} />
                </div>
                <button type="submit">Añadir consumo</button>
                {/* lista consumos */}
                <div>
                    {/* //consumos {"success":true,"data":[{"trabajoId":1,"itemId":1,"cantidad_usada":2}]} */}

                    <div>Lista de consumos</div>
                    {
                        consumoDatos.map((elemento) => {
                            return (<div key={elemento["itemId"]+"-"+elemento["trabajoId"]+"-"+elemento["cantidad_usada"]}>
                                {getNombreItem(elemento["itemId"])} - usado: {elemento["cantidad_usada"]}
                                </div>)
                        })
                    }
                </div>
            </form>
        </div>
    )
}

export default OrderFormPage;