import { useState, useEffect } from "react";
import { useNavigate, useParams } from "react-router-dom";
import $ordersController from "../../core/TestController/TestOrdersController";

function OrderFormPage() {
    const [orderData, setOrderData] = useState({
        descripcion: "", empleado: 0, estado: "",
        fecha_entrega: "", fecha_inicio: "",
        precio: 0, prenda: "", trabajo_id: 0
    });

    const { id } = useParams();

    const navegar = useNavigate();

    const handleOnSubmit = (evento) => {
        evento.preventDefault();
        console.log("OrdersFormPage: onsubmit")

        enviarDatos();
    }

    const enviarDatos = async () => {
        if (id != 0) {
            //update
            const response = $ordersController.updateOrder(orderData);
        } else {
            const response = $ordersController.createOrder(orderData);
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
                    <input type="text" name="descripcion" id="descripcion" onChange={handleOnChange} />
                </div>
                <div>
                    <div> Prenda: </div>
                    <input type="text" name="prenda" id="prenda" onChange={handleOnChange} />
                </div>
                <div>
                    <div> Empleado: </div>
                    {/* drop down con los empleados disponibles */}
                    <select name="empleado" id="empleado" onChange={handleOnChange}>
                        <option value="1">empleado 1</option>
                        <option value="2">empleado 2</option>
                        <option value="3">empleado 3</option>
                    </select>
                </div>
                <div>
                    <div> fecha de inicio: </div>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" onChange={handleOnChange} />
                </div>
                <div>
                    <div> fecha de entrega: </div>
                    <input type="date" name="fecha_entrega" id="fecha_entrega" onChange={handleOnChange} />
                </div>
                <div>
                    <div> precio: </div>
                    <input type="number" name="precio" id="precio" onChange={handleOnChange} />
                </div>
                <div>
                    <div> estado: </div>
                    <select name="estado" id="estado" onChange={handleOnChange}>
                        <option value="pendiente">pendiente</option>
                        <option value="en_proceso">En proceso</option>
                        <option value="listo">listo</option>
                        <option value="entregado">entregado</option>
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