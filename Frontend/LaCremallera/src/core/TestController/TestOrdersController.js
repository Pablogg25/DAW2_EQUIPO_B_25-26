import datos from "./DatosOrders";

const $ordersController = (function () {
    console.log("inicializar $ordersController test");

    if (!localStorage.getItem('trabajos')) {
        localStorage.setItem('trabajos', JSON.stringify(datos));
    }
    let trabajos = JSON.parse(localStorage.getItem('trabajos'));

    function siguientePacienteId() {
        let maxId = Math.max(...trabajos.map(p => p.trabajoId), 0);
        return maxId + 1;
    }

    async function getOrders(params) {
        console.log("AVISO: ESTE ES UN MÉTODO DE PRUEBAS, NO TIENE CONEXIÓN REAL CON LA API");
        console.log("ordersController: getOrders");
        
        return [...trabajos];
    }
    async function obtenerOrder(trabajoId){
        console.log("AVISO: ESTE ES UN MÉTODO DE PRUEBAS, NO TIENE CONEXIÓN REAL CON LA API");
        console.log("ordersController: obtenerOrder: "+trabajoId);
        let index=trabajos.findIndex(p=>p.trabajoId==trabajoId);

        if(index!==-1){
            return trabajos;
        }
    }

    return {
        getOrders,
        obtenerOrder,
    }
})();

window.$ordersController=$ordersController;
export default $ordersController;