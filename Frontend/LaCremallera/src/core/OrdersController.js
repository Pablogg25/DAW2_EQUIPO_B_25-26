
import apiController from "./ApiController";

const $ordersController = (function () {
    // console.log("Inicializando ordersController");

    // argumentos: {'empleadoId':int,'estado':string,'prendaId':int} exactos
    async function getOrders(params = {}) {
        // console.log("ordersController: getOrders");

        let requestUrl = apiController.getBaseUrl() + '/trabajos';

        //añadir argumentos
        // console.log(params);
        let args = '?';

        //añadir cada argumento
        for (let arg in params) {

            //params.nombre.trim() !== ""
            if (typeof params[arg] == "string") {
                if (params[arg].trim().length !== 0 && params[arg].trim() !== "-1") {
                    //añade un caracter de adición si ya hay otros argumentos
                    if (args != '?') {
                        args += '&';
                    }
                    args += arg + '=' + params[arg];
                }
            } else {
                if (params[arg] !== -1) {
                    //añade un caracter de adición si ya hay otros argumentos
                    if (args != '?') {
                        args += '&';
                    }
                    args += arg + '=' + params[arg];
                }
            }


        }

        if (args != '?') {
            //si tiene argumentos
            requestUrl += args;
        }

        try {
            const authToken = apiController.getAuthToken();
            if (!authToken) {
                return { data: "ERROR, NO AUTH TOKEN", success: false };
            }
            const requestBody = {
                method: "GET",
                headers: {
                    "Authorization": `Bearer ${authToken}`,
                    "Content-type": "application/json; charset=UTF-8",
                },
            };
            // console.log(requestBody);
            // console.log("Realizando petición a: " + requestUrl);
            const request = await fetch(requestUrl, requestBody);
            const respuesta = await request.json();

            // console.log(request);

            if (request.status == 200) {

                // console.log("OrdersController respuesta OK 200");
                // console.log(respuesta);

                return { "data": respuesta.data, "status": 200, "success": true };
            }

            //else error
            // if (request.status == 404) {
            //     console.log("Orders controller respuesta NOT FOUND 404")
            // }
            // console.log("error al obtener datos");
            return { "data": respuesta.message, "status": request.status, "success": false };


        } catch (e) {
            // console.log("Excepción en petición:");
            console.log(e);

            return { "data": e, "success": false };
        }
    };

    async function getOrder(trabajoId) {
        // console.log("ordersController: getOrder, id:" + trabajoId);

        const requestUrl = apiController.getBaseUrl() + '/trabajos/' + trabajoId;

        //TODO: gestionar errores y códigos de error

        try {
            const authToken = apiController.getAuthToken();
            if (!authToken) {
                return { data: "ERROR, NO AUTH TOKEN", success: false };
            }
            const requestBody = {
                headers: {
                    "Content-type": "application/json; charset=UTF-8",
                    "Authorization": "Bearer " + authToken,
                },
            };
            // console.log("Realizando petición a: " + requestUrl);
            const request = await fetch(requestUrl, requestBody);
            const respuesta = await request.json();

            if (request.status == 200) {

                // console.log("OrdersController respuesta OK 200");
                // console.log(respuesta);

                return { "data": respuesta.data, "status": 200, "success": true };
            }

            //else error
            // console.log("error al obtener datos");
            return { "data": respuesta.message, "status": request.status, "success": false };


        } catch (e) {
            // console.log("Excepción en petición:");
            console.log(e);

            return { "data": e, "success": false };
        }
    };

    async function createOrder(objOrder) {
        // console.log("ordersController createOrder ");

        const requestUrl = apiController.getBaseUrl() + '/trabajos';

        try {
            const authToken = apiController.getAuthToken();
            if (!authToken) {
                return { data: "ERROR, NO AUTH TOKEN", success: false };
            }
            // console.log("Realizando petición a: " + requestUrl);
            const requestBody = {
                method: "POST",
                body: JSON.stringify(objOrder),
                headers: {
                    "Content-type": "application/json; charset=UTF-8",
                    "Authorization": "Bearer " + authToken,
                },
            }
            const request = await fetch(requestUrl, requestBody);

            const datos = await request.json();

            if (request.status == 201) {
                // console.log("Respuesta 201: CREATED");
                return { estado: 201, data: datos.data, "success": true };
            }
            // if (request.status == 400) {
            //     console.log("Respuesta 400: VALIDATION ERROR");
            //     // console.log(datos);
            //     // return { estado: 400, data: datos, "success": false };
            // }

            return { estado: request.status, data: datos.message, "success": false };
        } catch (e) {
            // console.log("Excepción en petición:");
            console.log(e);

            return { "data": e, "success": false };
        }
    }

    async function updateOrder(objOrder, trabajoId) {
        // console.log("ordersController updateOrder ");

        const requestUrl = apiController.getBaseUrl() + '/trabajos/' + trabajoId;

        try {
            const authToken = apiController.getAuthToken();
            if (!authToken) {
                return { data: "ERROR, NO AUTH TOKEN", success: false };
            }
            // console.log("Realizando petición a: " + requestUrl);
            const requestBody = {
                method: "PUT",
                body: JSON.stringify(objOrder),
                headers: {
                    "Content-type": "application/json; charset=UTF-8",
                    "Authorization": "Bearer " + authToken,
                },
            }
            const request = await fetch(requestUrl, requestBody);

            const datos = await request.json();

            if (request.status == 200) {
                // console.log("Respuesta 200: OK");
                return { estado: 200, data: datos.data, "success": true };
            }
            // if (request.status == 404) {
            //     console.log("Respuesta 404: NOT FOUND");
            //     // console.log(datos);
            //     // return { estado: 404, data: datos, "success": false };
            // }
            // if (request.status == 400) {
            //     console.log("Respuesta 400: VALIDATION ERROR");
            //     // console.log(datos);
            //     // return { estado: 400, data: datos, "success": false };
            // }

            return { estado: request.status, data: datos.message, "success": false };

        } catch (e) {
            // console.log("Excepción en petición:");
            console.log(e);

            return { "data": e, "success": false };
        }
    }

    async function deleteOrder(trabajoId) {
        // console.log("ordersController: deleteOrder");

        const requestUrl = apiController.getBaseUrl() + "/trabajos/" + trabajoId;

        try {
            const authToken = apiController.getAuthToken();
            if (!authToken) {
                return { data: "ERROR, NO AUTH TOKEN", success: false };
            }
            // console.log("Realizando petición a: " + requestUrl);
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
                // console.log("Respuesta 200: OK");
                return { estado: 200, data: datos.data, success: true };
            }
            // if (respuesta.status == 404) {
            //     console.log("Respuesta 404: NOT FOUND");
            //     // return { estado: 404, data: datos, success: false };
            // }
            // if (respuesta.status == 409) {
            //     console.log("Respuesta 409 CONSTRAINT");
            // }
            return { estado: respuesta.status, data: datos.message, success: false };

        } catch (e) {
            // console.log("$negocioApi: Resultado error");
            console.log(e);
            return { "data": e, "success": false };
        }
    }

    async function getConsumos(trabajoId) {
        // console.log("ordersController: getOrders");

        let requestUrl = apiController.getBaseUrl() + '/trabajos/' + trabajoId + '/consumos';

        try {
            const authToken = apiController.getAuthToken();
            if (!authToken) {
                return { data: "ERROR, NO AUTH TOKEN", success: false };
            }
            const requestBody = {
                headers: {
                    "Content-type": "application/json; charset=UTF-8",
                    "Authorization": "Bearer " + authToken,
                },
            };
            // console.log("Realizando petición a: " + requestUrl);

            const request = await fetch(requestUrl, requestBody);
            const respuesta = await request.json();

            if (request.status == 200) {

                // console.log("OrdersController consumos respuesta OK 200");
                // console.log(respuesta);

                return { "data": respuesta.data, "status": 200, "success": true };
            }

            //else error
            // if (request.status == 404) {
            //     console.log("Orders controller respuesta NOT FOUND 404")
            // }
            // console.log("error al obtener datos");
            return { "data": respuesta.message, "status": request.status, "success": false };


        } catch (e) {
            // console.log("Excepción en petición:");
            console.log(e);

            return { "data": e, "success": false };
        }
    }

    async function asociarConsumo(trabajoId, consumoObj) {
        //consumo: {itemid,cantidad_usada}
        // console.log("ordersController: getOrders");

        let requestUrl = apiController.getBaseUrl() + '/trabajos/' + trabajoId + '/consumos';

        try {
            // console.log("Realizando petición a: " + requestUrl);
            const authToken = apiController.getAuthToken();
            if (!authToken) {
                return { data: "ERROR, NO AUTH TOKEN", success: false };
            }
            const requestBody = {
                method: "POST",
                body: JSON.stringify(consumoObj),
                headers: {
                    "Content-type": "application/json; charset=UTF-8",
                    "Authorization": "Bearer " + authToken,
                },
            }
            const request = await fetch(requestUrl, requestBody);
            const respuesta = await request.json();

            if (request.status == 201) {
                // console.log("OrdersController respuesta CREATED 201");
                // console.log(respuesta);

                return { "data": respuesta.data, "status": 201, "success": true };
            }

            //else error
            // if (request.status == 404) {
            //     console.log("Orders controller respuesta NOT FOUND 404")
            // }
            // console.log("error al obtener datos");
            return { "data": respuesta.message, "status": request.status, "success": false };


        } catch (e) {
            // console.log("Excepción en petición:");
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
        getConsumos,
        asociarConsumo,
    };
})();

window.$ordersController = $ordersController;
export default $ordersController;