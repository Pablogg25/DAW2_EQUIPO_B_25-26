import { useState, useEffect } from "react";
import { useNavigate, useParams } from "react-router-dom";

import $ordersController from "../../core/OrdersController";
import $usuariosController from "../../core/TestController/TestUsersController";
import $prendasController from "../../core/TestController/TestPrendasController";



function OrderFormPage() {
    const [orderData, setOrderData] = useState({
        descripcion: "", empleado: 0, estado: "",
        fecha_entrega: "", fecha_inicio: "",
        precio: 0, prenda: "", trabajo_id: 0
    });

    const [usuariosData, setUsuarioData] = useState([]);
    const [prendasData, setPrendasData] = useState([]);

    const { id } = useParams();

    const cargarDatos = async () => {
        console.log("cargando datos");
        if (id != 0) {
            //modo edit
            let datos = await $ordersController.getOrder(id);
            console.log(datos);
            if (datos.success) {
                setOrderData(datos.data);
            } else {
                alert("Error, no se ha podido procesar su petición");
                navegar("/trabajos");
            }

        }
        //else modo create

        //TODO, cargar empleados y prendas por ids
        setUsuarioData(await $usuariosController.getUsuarios());
        setPrendasData(await $prendasController.getPrendas());
    }

    const navegar = useNavigate();

    const handleOnSubmit = (evento) => {
        evento.preventDefault();
        console.log("OrdersFormPage: onsubmit")

        enviarDatos();
    }

    const enviarDatos = async () => {
        let success;
        if (id != 0) {
            //update
            const response = await $ordersController.updateOrder(orderData);
            success = response.success;
        } else {
            const response = await $ordersController.createOrder(orderData);
            success = response.success;
        }
        //TODO: comprobar resultado correcto
        if (success) {
            navegar("/orders");
        } else {
            alert("Error, ha surgido un error al procesar su petición");
        }

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
                        {prendasData.map((elemento)=>{
                            return(
                                <option key={elemento.prendaId} value={elemento.prendaId} selected={orderData.prenda == elemento.prendaId}>{elemento.tipo}</option>
                            );
                        })}
                    </select>
                </div>
                <div>
                    <div> Empleado: </div>
                    {/* drop down con los empleados disponibles */}
                    <select name="empleadoId" id="empleadoId" onChange={handleOnChange}>
                        {usuariosData.map((elemento)=>{
                            return(
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
        </div>
    )
}

export default OrderFormPage;