
import apiController from "./ApiController";

const $ordersController = (function () {
    console.log("Inicializando ordersController");

    async function getOrders() {
        console.log("ordersController: getOrders");

        const requestUrl = apiController.getBaseUrl() + '/trabajos';

        //TODO: gestionar errores y códigos de error

        try {
            console.log("Realizando petición a: " + requestUrl);
            const request = await fetch(requestUrl);
            const respuesta = await request.json();

            if (request.status == 200) {

                console.log("OrdersController respuesta OK 200");
                // console.log(respuesta);

                return { "data": respuesta.data, "status": 200, "success": true };
            }

            //else error
            console.log("error al obtener datos");
            return { "data": respuesta.message, "status": request.status, "success": false };


        } catch (e) {
            console.log("Excepción en petición:");
            console.log(e);

            return { "data": e, "success": false };
        }
    };

    async function getOrder(trabajoId) {
        console.log("ordersController: getOrder, id:" + trabajoId);

        const requestUrl = apiController.getBaseUrl() + '/trabajos/' + trabajoId;

        //TODO: gestionar errores y códigos de error

        try {
            console.log("Realizando petición a: " + requestUrl);
            const request = await fetch(requestUrl);
            const respuesta = await request.json();

            if (request.status == 200) {

                console.log("OrdersController respuesta OK 200");
                // console.log(respuesta);

                return { "data": respuesta.data, "status": 200, "success": true };
            }

            //else error
            console.log("error al obtener datos");
            return { "data": respuesta.message, "status": request.status, "success": false };


        } catch (e) {
            console.log("Excepción en petición:");
            console.log(e);

            return { "data": e, "success": false };
        }
    };

    async function createOrder(objOrder) {
        console.log("ordersController createOrder ");

        const requestUrl = apiController.getBaseUrl() + '/trabajos';

        try {
            console.log("Realizando petición a: " + requestUrl);
            const requestBody = {
                method: "POST",
                body: JSON.stringify(objOrder),
                headers: {
                    "Content-type": "application/json; charset=UTF-8",
                },
            }
            const request = await fetch(requestUrl, requestBody);

            const datos = await request.json();

            if (request.status == 201) {
                console.log("Respuesta 201: CREATED");
                return { estado: 201, data: datos.data, "success": true };
            }
            if (request.status == 400) {
                console.log("Respuesta 400: VALIDATION ERROR");
                // console.log(datos);
                // return { estado: 400, data: datos, "success": false };
            }

            return { estado: request.status, data: datos.message, "success": false };
        } catch (e) {
            console.log("Excepción en petición:");
            console.log(e);

            return { "data": e, "success": false };
        }
    }

    async function updateOrder(objOrder, trabajoId) {
        console.log("ordersController updateOrder ");

        const requestUrl = apiController.getBaseUrl() + '/trabajos/' + trabajoId;

        try {
            console.log("Realizando petición a: " + requestUrl);
            const requestBody = {
                method: "PUT",
                body: JSON.stringify(objOrder),
                headers: {
                    "Content-type": "application/json; charset=UTF-8",
                },
            }
            const request = await fetch(requestUrl, requestBody);

            const datos = await request.json();

            if (request.status == 200) {
                console.log("Respuesta 200: OK");
                return { estado: 200, data: datos.data, "success": true };
            }
            if (request.status == 404) {
                console.log("Respuesta 404: NOT FOUND");
                // console.log(datos);
                // return { estado: 404, data: datos, "success": false };
            }
            if (request.status == 400) {
                console.log("Respuesta 400: VALIDATION ERROR");
                // console.log(datos);
                // return { estado: 400, data: datos, "success": false };
            }

            return { estado: request.status, data: datos.message, "success": false };

        } catch (e) {
            console.log("Excepción en petición:");
            console.log(e);

            return { "data": e, "success": false };
        }
    }

    async function deleteOrder(trabajoId) {
        console.log("ordersController: deleteOrder");

        const requestUrl = apiController.getBaseUrl() + "/trabajos/" + trabajoId;

        try {
            console.log("Realizando petición a: " + requestUrl);
            const requestBody = {
                method: "DELETE",
                headers: {
                    "Content-type": "application/json; charset=UTF-8",
                },
            };
            const respuesta = await fetch(requestUrl, requestBody);

            const datos = await respuesta.json();

            if (respuesta.status == 200) {
                console.log("Respuesta 200: OK");
                return { estado: 200, data: datos.data, success: true };
            }
            if (respuesta.status == 404) {
                console.log("Respuesta 404: NOT FOUND");
                // return { estado: 404, data: datos, success: false };
            }
            if(respuesta.status==409){
                console.log("Respuesta 409 CONSTRAINT");
            }
            return { estado: respuesta.status, data: datos.message, success: false };

        } catch (e) {
            console.log("$negocioApi: Resultado error");
            console.log(e);
            return { "data": e, "success": false };
        }
    }

    return {
        getOrders,
        getOrder,
        createOrder,
        updateOrder,
        deleteOrder,
    };
})();

window.$ordersController = $ordersController;
export default $ordersController;