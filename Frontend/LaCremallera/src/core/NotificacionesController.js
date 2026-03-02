import apiController from "./ApiController";

const $notificacionesController = (function () {
    console.log("Inicializar $notificaciones controller");

    //params: {'receptorId':int,'remitenteId':int,'trabajoId':int}
    async function getNotificaciones(authToken, params = {}) {
        console.log("notificacionesController: getNotificaciones");

        let requestUrl = apiController.getBaseUrl() + "/notificaciones";

        //añadir argumentos
        console.log(params);

        let args = '?';

        //añadir cada argumento
        for (let arg in params) {

            if (params[arg] !== -1) {
                if (args != '?') {
                    args += '&';
                }
                args += arg + '=' + params[arg];
            }

        }

        if (args != '?') {
            //si tiene argumentos
            requestUrl += args;
        }

        try {
            const requestBody = {
                headers: {
                    "Content-type": "application/json; charset=UTF-8",
                    "Authorization": "Bearer " + authToken,
                },
            };
            console.log("Realizando petición a: " + requestUrl);
            const request = await fetch(requestUrl, requestBody);
            const respuesta = await request.json();

            if (request.status == 200) {

                console.log("OrdersController respuesta OK 200");
                // console.log(respuesta);

                return { "data": respuesta.data, "status": 200, "success": true };
            }

            if (request.status == 404) {

                console.log("OrdersController respuesta NOT FOUND 404");
                // console.log(respuesta);

                return { "data": respuesta.message, "status": 404, "success": false };
            }

            //else error
            console.log("error al obtener datos");
            return { "data": respuesta.message, "status": request.status, "success": false };


        } catch (e) {
            console.log("Excepción en petición:");
            console.log(e);

            return { "data": e, "success": false };
        }
    }

    async function getNotificacion(authToken, notId) {
        console.log("notificacionesController: getNotificacion id: " + notId);

        const requestUrl = apiController.getBaseUrl() + "/notificaciones/" + notId;

        try {
            const requestBody = {
                headers: {
                    "Content-type": "application/json; charset=UTF-8",
                    "Authorization": "Bearer " + authToken,
                },
            };
            console.log("Realizando petición a: " + requestUrl);
            const request = await fetch(requestUrl, requestBody);

            const respuesta = await request.json();
            if (request.status == 200) {

                console.log("notificacionesController respuesta OK 200");
                // console.log(respuesta);

                return { "data": respuesta.data, "status": 200, "success": true };
            }

            if (request.status == 404) {

                console.log("OrdersController respuesta NOT FOUND 404");
                // console.log(respuesta);

                return { "data": respuesta.message, "status": 404, "success": false };
            }

            //else error
            console.log("error al obtener datos");
            return { "data": respuesta.message, "status": request.status, "success": false };


        } catch (e) {
            console.log("Excepción en petición:");
            console.log(e);

            return { "data": e, "success": false };
        }
    }

    async function createNotificacion(authToken, objNot) {
        console.log("notificacionesController: create notificación");

        const requestUrl = apiController.getBaseUrl() + "/notificaciones";

        try {
            console.log("Realizando petición a: " + requestUrl);
            const requestBody = {
                method: "POST",
                body: JSON.stringify(objNot),
                headers: {
                    "Content-type": "application/json; charset=UTF-8",
                    "Authorization": "Bearer " + authToken,
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
                return { estado: 400, data: datos.message, "success": false };
            }

            return { estado: request.status, data: datos.message, "success": false };
        } catch (e) {
            console.log("Excepción en petición:");
            console.log(e);

            return { "data": e, "success": false };
        }
    }

    async function updateNotificacion(authToken, objNot) {
        console.log("notificacionesController: update Notificacion");

        const requestUrl = apiController.getBaseUrl() + "/notificaciones/" + objNot.notificacionId;

        try {
            console.log("Realizando petición a: " + requestUrl);
            const requestBody = {
                method: "PUT",
                body: JSON.stringify(objNot),
                headers: {
                    "Content-type": "application/json; charset=UTF-8",
                    "Authorization": "Bearer " + authToken,
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
                return { estado: 400, data: datos.message, "success": false };
            }

            return { estado: request.status, data: datos.message, "success": false };
        } catch (e) {
            console.log("Excepción en petición:");
            console.log(e);

            return { "data": e, "success": false };
        }
    }

    async function deleteNotificacion(authToken, notId) {

        console.log("notificacionesController: delete Notificacion");

        const requestUrl = apiController.getBaseUrl() + "/notificaciones/" + notId;

        try {
            const requestBody = {
                method: "DELETE",
                headers: {
                    "Content-type": "application/json; charset=UTF-8",
                    "Authorization": "Bearer " + authToken,
                },
            };
            const respuesta = await fetch(requestUrl, requestBody);

            const datos = await respuesta.json();

            if (respuesta.status == 200) {
                console.log("Respuesta 200: OK");
                return { estado: 200, data: datos.message, success: true };
            }
            if (respuesta.status == 404) {
                console.log("Respuesta 404: NOT FOUND");
                return { estado: 404, data: datos.message, detalles: datos.detalle, success: false };
            }
            if (respuesta.status == 409) {
                console.log("respuesta 409 CONSTRAINT");
                return { estado: 409, data: datos.message, detalles: datos.detalle, success: false };

            }
            return { estado: respuesta.status, data: datos.message, detalles: datos.detalle, success: false };

        } catch (e) {
            console.log("$negocioApi: Resultado error");
            console.log(e);
            return { "data": e, "success": false };
        }

    }

    return {
        getNotificaciones,
        getNotificacion,
        createNotificacion,
        updateNotificacion,
        deleteNotificacion,
    }
})();

export default $notificacionesController;