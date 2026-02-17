import datos from "./DatosPrendas";

const $prendasController = (function () {
    console.log("TestPrendasController inicializando");

    console.log("inicializar $ordersController test");
    // localStorage.removeItem('trabajos');

    if (!localStorage.getItem('prendas')) {
        localStorage.setItem('prendas', JSON.stringify(datos));
    }
    let prendas = JSON.parse(localStorage.getItem('prendas'));

    function siguientePrendaId() {
        let maxId = Math.max(...prendas.map(p => p.prendaId), 0);
        return maxId + 1;
    }

    async function getPrendas(params) {
        console.log("AVISO: ESTE ES UN MÉTODO DE PRUEBAS, NO TIENE CONEXIÓN REAL CON LA API");
        console.log("prendasController: getPrendas");

        return [...prendas];
    }

    function getPrenda(prendaId) {
        let index = prendas.findIndex(p => p.prendaId == prendaId);

        if (index !== -1) {
            return prendas[index];
        }
    }

    async function createPrenda(prendaObj) {
        prendaObj.prendaId = siguientePrendaId();
        prendas.push(prendaObj);
        localStorage.setItem('prendas', JSON.stringify(datos));

        return prendaObj.prendaId;
    }

    async function updatePrenda(prendaObj) {
        let index = prendas.findIndex(u => u.prendaId == prendaObj.prendaId);

        if (index !== -1) {
            prendas[index] = prendaObj;
            localStorage.setItem('prendas', JSON.stringify(datos));
            return true;

        }
        return false
    }

    async function deletePrenda(prendaId) {
        let index = prendas.findIndex(u => u.prendaId == prendaId);

        if (index !== -1) {
            prendas.splice(index,1);
            localStorage.setItem('prendas', JSON.stringify(datos));
            return true;

        }
        return false
    }

    function limpiarLocalStorage(){
        localStorage.removeItem('prendas');
    }

    return {
        getPrendas,
        getPrenda,
        createPrenda,
        updatePrenda,
        deletePrenda,
        limpiarLocalStorage
    }
})();
export default $prendasController;