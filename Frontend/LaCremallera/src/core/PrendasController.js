import apiController from "./ApiController";

const $prendasController = (function () {
    console.log("PrendasController inicializado");

    //params {'usuarioId'=int}
    async function getPrendas(params = {}) {
        console.log("Prendas controler getPrendas");

        let requestUrl = apiController.getBaseUrl() + "/prendas";

        if (params['usuarioId'] && params['usuarioId'] != -1) {
            requestUrl += '?usuarioId=' + params['usuarioId'];
        }

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
            console.log("Realizando petición a: " + requestUrl);

            const request = await fetch(requestUrl, requestBody);
            const respuesta = await request.json();

            if (request.status == 200) {
                console.log("prendasController repuesta 200 OK");
                // console.log(respuesta);

                return { "data": respuesta.data, "status": 200, "success": true };

            }

            if (request.status == 404) {
                console.log("respuesta 404 NOT FOUND");
                // return { "data": request.message, "status": request.status, "success": false };

            }
            //else error
            console.log("error al obtener datos");
            return { "data": request.message, "status": request.status, "success": false };

        } catch (e) {
            console.log("Excepción en petición:");
            console.log(e);

            return { "data": e, "success": false };
        }
    }

    async function getPrenda(prendaId) {
        console.log("PrendasControler getPrenda id: " + prendaId);

        const requestUrl = apiController.getBaseUrl() + "/prendas/" + prendaId;

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
            console.log("Realizando petición a: " + requestUrl);

            const request = await fetch(requestUrl, requestBody);
            const respuesta = await request.json();

            if (request.status == 200) {
                console.log("prendasController repuesta 200 OK");

                return { "data": respuesta.data, "status": 200, "success": true };

            }
            if (request.status == 404) {
                console.log("PrendasControler respuesta NOT FOUND 404");
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

    async function createPrenda(prendaObj) {
        console.log("PrendasControler create prenda");

        const requestUrl = apiController.getBaseUrl() + "/prendas";
        const authToken = apiController.getAuthToken();
        if (!authToken) {
            return { data: "ERROR, NO AUTH TOKEN", success: false };
        }
        const requestBody = {
            method: "POST",
            body: JSON.stringify(prendaObj),
            headers: {
                "Content-type": "application/json; charset=UTF-8",
            },
            "Authorization": "Bearer " + authToken,
        }

        try {

            console.log("Realizando petición a: " + requestUrl);

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
            //else error
            console.log("error al obtener datos");
            return { estado: request.status, data: datos.message, "success": false };


        } catch (e) {
            console.log("Excepción en petición:");
            console.log(e);

            return { "data": e, "success": false };
        }
    }

    async function updatePrenda(prendaObj, prendaId) {
        console.log("PrendasControler update prenda id: " + prendaId);

        const requestUrl = apiController.getBaseUrl() + "/prendas/" + prendaId;
        const authToken = apiController.getAuthToken();
        if (!authToken) {
            return { data: "ERROR, NO AUTH TOKEN", success: false };
        }
        const requestBody = {
            method: "PUT",
            body: JSON.stringify(prendaObj),
            headers: {
                "Content-type": "application/json; charset=UTF-8",
                "Authorization": "Bearer " + authToken,
            },
        }

        try {
            console.log("Realizando petición a: " + requestUrl);

            const request = await fetch(requestUrl, requestBody);
            const datos = await request.json();


            if (request.status == 200) {
                console.log("Respuesta 200: OK");
                return { estado: 201, data: datos.data, "success": true };
            }
            if (request.status == 404) {
                console.log("Respuesta 404: NOT FOUND");
                // console.log(datos);
                return { estado: 404, data: datos.message, "success": false };
            }
            if (request.status == 400) {
                console.log("Respuesta 400: VALIDATION ERROR");
                // console.log(datos);
                return { estado: 400, data: datos.message, "success": false };
            }
            //else error
            console.log("error al obtener datos");
            return { estado: request.status, data: datos.message, "success": false };


        } catch (e) {
            console.log("Excepción en petición:");
            console.log(e);

            return { "data": e, "success": false };
        }
    }

    async function deletePrenda(prendaId) {
        console.log("prendasController: delete prenda id: " + prendaId);

        try {
            const authToken = apiController.getAuthToken();
            if (!authToken) {
                return { data: "ERROR, NO AUTH TOKEN", success: false };
            }
            const requestBody = {
                method: "DELETE",
                headers: {
                    "Content-type": "application/json; charset=UTF-8",
                    "Authorization": "Bearer " + authToken,
                },
            };
            const respuesta = await fetch(apiController.getBaseUrl() + "/prendas/" + prendaId, requestBody);

            const datos = await respuesta.json();

            if (respuesta.status == 200) {
                console.log("Respuesta 200: OK");
                return { estado: 200, data: datos.data, success: true };
            }
            if (respuesta.status == 404) {
                console.log("Respuesta 404: NOT FOUND");
                // return { estado: 404, data: datos, success: false };
            }
            if (respuesta.status == 409) {
                console.log("Respuesta 404: CONSTRAINT");
                // return { estado: 404, data: datos, success: false };
            }
            return { estado: respuesta.status, data: datos.message, success: false };
        } catch (e) {
            console.log("$negocioApi: Resultado error");
            console.log(e);
            return { "data": e, "success": false };
        }

    }

    return {
        getPrendas,
        getPrenda,
        createPrenda,
        updatePrenda,
        deletePrenda
    }
})();

export default $prendasController;