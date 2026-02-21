import datos from "./DatosFacturas";

const $facturasController=(function (){
    console.log("Inicializar $facturas TEST");

    if(!localStorage.getItem("facturas")){
        localStorage.setItem("facturas",JSON.stringify(datos));
    }
    let facturas=JSON.parse(localStorage.getItem("facturas"));

    function siguienteFacturaId() {
        let maxId = Math.max(...facturas.map(p => p.facturaId), 0);
        return maxId + 1;
    }

    async function getFacturas(params) {
        console.log("AVISO: ESTE ES UN MÉTODO DE PRUEBAS, NO TIENE CONEXIÓN REAL CON LA API");
        console.log("notificacionesController: getNotificaciones");
        return [...facturas];
    }

    async function getFactura(facturaId) {
        let index = facturas.findIndex(p => p.facturaId == facturaId);

        if (index !== -1) {
            return facturas[index];
        }
    }

    async function createFactura(facturaObj){
        facturaObj.facturaId=siguienteFacturaId();

        facturas.push(facturaObj);
        localStorage.setItem("facturas",JSON.stringify(datos));

        return facturaObj.facturaId;
    }

    async function updateFactura(facturaObj) {
        let index = facturas.findIndex(p => p.facturaId == facturaObj.facturaId);

        if (index !== -1) {
            facturas[index] = facturaObj;
            localStorage.setItem('facturas', JSON.stringify(facturas));
            return true;
        }
        return false;
    }

    async function deleteFactura(facturaId) {
        console.log("testController delete notificacion");
        let index = facturas.findIndex(p => p.facturaId == facturaId);

        if (index !== -1) {
            facturas.splice(index, 1);
            localStorage.setItem('facturas', JSON.stringify(facturas));
            return true;
        }
        return false;
    }

    function limpiarLocalStorage(){
        localStorage.removeItem('facturas');

    }

    return{
        getFacturas,
        getFactura,
        createFactura,
        updateFactura,
        deleteFactura,
        limpiarLocalStorage,
    }
})();

window.$facturasController=$facturasController;
export default $facturasController