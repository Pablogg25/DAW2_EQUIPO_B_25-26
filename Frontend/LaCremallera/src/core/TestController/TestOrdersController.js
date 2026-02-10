import datos from "./DatosOrders";

const $ordersController = (function () {
    console.log("inicializar $ordersController test");
    // localStorage.removeItem('trabajos');

    if (!localStorage.getItem('trabajos')) {
        localStorage.setItem('trabajos', JSON.stringify(datos));
    }
    let trabajos = JSON.parse(localStorage.getItem('trabajos'));

    function siguienteOrderId() {
        let maxId = Math.max(...trabajos.map(p => p.trabajoId), 0);
        return maxId + 1;
    }

    async function getOrders(params) {
        console.log("AVISO: ESTE ES UN MÉTODO DE PRUEBAS, NO TIENE CONEXIÓN REAL CON LA API");
        console.log("ordersController: getOrders");

        return [...trabajos];
    }
    async function getOrder(trabajoId) {
        console.log("AVISO: ESTE ES UN MÉTODO DE PRUEBAS, NO TIENE CONEXIÓN REAL CON LA API");
        console.log("ordersController: obtenerOrder: " + trabajoId);
        let index = trabajos.findIndex(p => p.trabajoId == trabajoId);

        if (index !== -1) {
            return trabajos[index];
        }
    }

    async function createOrder(objOrder) {
        objOrder.trabajoId = siguienteOrderId();
        trabajos.push(objOrder);
        localStorage.setItem('trabajos', JSON.stringify(trabajos));

        return objOrder.id;
    }

    async function updateOrder(objOrder) {
        let index = trabajos.findIndex(u => u.trabajoId == objOrder.trabajoId);
        if (index !== -1) {
            trabajos[index] = objOrder;
            localStorage.setItem('trabajos', JSON.stringify(trabajos));
            return true;
        }
        return false;
    }

    async function deleteOrder(orderId) {
        console.log("testController delete order");
        let index = trabajos.findIndex(u => u.trabajoId == orderId);
        if (index !== -1) {
            trabajos.splice(index, 1);
            localStorage.setItem('trabajos', JSON.stringify(trabajos));
            return true;
        }
        return false;
    }

    function limpiarLocalStorage(){
        localStorage.removeItem('trabajos');

    }

    return {
        getOrders,
        getOrder,
        createOrder,
        updateOrder,
        deleteOrder,
        limpiarLocalStorage
    }
})();

window.$ordersController = $ordersController;
export default $ordersController;