import { useState, useEffect } from "react";
import { useNavigate, useParams } from "react-router-dom";
// import $ordersController from "../../core/TestController/TestOrdersController";
import $ordersController from "../../core/OrdersController";

function OrderFormPage() {
    const [orderData, setOrderData] = useState({
        descripcion: "", empleado: 0, estado: "",
        fecha_entrega: "", fecha_inicio: "",
        precio: 0, prenda: "", trabajo_id: 0
    });

    const { id } = useParams();

    const cargarDatos=async()=>{
        console.log("cargando datos");
        if(id!=0){
            //modo edit
            let datos=await $ordersController.getOrder(id);
            setOrderData(datos);
            console.log(datos);
        }
        //else modo create
    }

    const navegar = useNavigate();

    const handleOnSubmit = (evento) => {
        evento.preventDefault();
        console.log("OrdersFormPage: onsubmit")

        enviarDatos();
    }

    const enviarDatos = async () => {
        if (id != 0) {
            //update
            const response =await $ordersController.updateOrder(orderData);
        } else {
            const response =await $ordersController.createOrder(orderData);
        }
        //TODO: comprobar resultado correcto
        navegar("/orders");
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

    useEffect(()=>{
        cargarDatos(id);
    },[id]);

    return (
        <div>
            <div>Formulario create</div>

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
                    <input type="text" name="prenda" id="prenda" value={orderData.prenda} onChange={handleOnChange} />
                </div>
                <div>
                    <div> Empleado: </div>
                    {/* drop down con los empleados disponibles */}
                    <select name="empleado" id="empleado" onChange={handleOnChange}>
                        <option value="1" selected={orderData.empleado==1}>empleado 1</option>
                        <option value="2" selected={orderData.empleado==2}>empleado 2</option>
                        <option value="3" selected={orderData.empleado==3}>empleado 3</option>
                        <option value="4" selected={orderData.empleado==4}>empleado 4</option>
                        <option value="5" selected={orderData.empleado==5}>empleado 5</option>
                        <option value="6" selected={orderData.empleado==6}>empleado 6</option>
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
                        <option value="pendiente" selected={orderData.estado=="pendiente"}>pendiente</option>
                        <option value="en_proceso" selected={orderData.estado=="en_proceso"}>En proceso</option>
                        <option value="listo" selected={orderData.estado=="listo"}>listo</option>
                        <option value="entregado" selected={orderData.estado=="entregado"}>entregado</option>
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