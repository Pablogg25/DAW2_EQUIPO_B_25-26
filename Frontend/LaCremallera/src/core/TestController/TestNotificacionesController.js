import datos from "./DatosNotificaciones";

const $notificacionesController=(function () {
    console.log("Inicializar $notificacionesController test");

    if(!localStorage.getItem("notificaciones")){
        localStorage.setItem("notificaciones",JSON.stringify(datos));
    }
    let notificaciones=JSON.parse(localStorage.getItem("notificaciones"));

    function siguienteNotId() {
        let maxId = Math.max(...notificaciones.map(p => p.notificacionId), 0);
        return maxId + 1;
    }

    async function getNotificaciones(params){
        console.log("AVISO: ESTE ES UN MÉTODO DE PRUEBAS, NO TIENE CONEXIÓN REAL CON LA API");
        console.log("notificacionesController: getNotificaciones");
        return [...notificaciones];
    }

    async function getNotificacion(notId){
        let index = notificaciones.findIndex(p => p.notificacionId == notId);

        if (index !== -1) {
            return notificaciones[index];
        }
    }

    async function createNotificacion(notObj){
        notObj.notificacionId=siguienteNotId();

        notificaciones.push(notObj);
        localStorage.setItem("notificaciones",JSON.stringify(datos));

        return notObj.notificacionId;
    }

    async function updateNotificacion(notObj) {
        let index = notificaciones.findIndex(p => p.notificacionId == notObj.notificacionId);

        if (index !== -1) {
            notificaciones[index] = notObj;
            localStorage.setItem('notificaciones', JSON.stringify(notificaciones));
            return true;
        }
        return false;
    }

    async function deleteNotificacion(orderId) {
        console.log("testController delete notificacion");
        let index = notificaciones.findIndex(p => p.notificacionId == orderId);

        if (index !== -1) {
            notificaciones.splice(index, 1);
            localStorage.setItem('notificaciones', JSON.stringify(notificaciones));
            return true;
        }
        return false;
    }

    function limpiarLocalStorage(){
        localStorage.removeItem('notificaciones');

    }

    return {
        getNotificaciones,
        getNotificacion,
        createNotificacion,
        updateNotificacion,
        deleteNotificacion,
        limpiarLocalStorage
    }

})();

window.$notificacionesController=$notificacionesController;
export default $notificacionesController;