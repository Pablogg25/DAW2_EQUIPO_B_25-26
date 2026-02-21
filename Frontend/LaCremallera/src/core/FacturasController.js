import apiController from "./ApiController";

const $facturasController = (function () {
    console.log("Inicializar $facturas controller");

    //params{'trabajoId':int,'usuarioId':int}
    async function getFacturas(params) {
        console.log("facturas controller: getFacturas");

        let requestUrl = apiController.getBaseUrl() + "/facturas";

        let args = '?';

        //añadir cada argumento
        for (let arg in params) {
            if (args != '?') {
                args += '&';
            }
            args += arg + '=' + params[arg];
        }

        if (args != '?') {
            //si tiene argumentos
            requestUrl += args;
        }
        try {
            console.log("realizando petición a: " + requestUrl);
            const request = await fetch(requestUrl);

            if (request.status == 200) {
                const respuesta = await request.json();

                console.log("facturas controller respuesta OK 200");
                // console.log(respuesta);

                return { "data": respuesta.data, "status": 200, "success": true };
            }

            if (request.status == 404) {
                const respuesta = await request.json();

                console.log("facturas respuesta NOT FOUND 404");
                // console.log(respuesta);

                return { "data": respuesta.message, "status": 404, "success": false };
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

    async function getFactura(facturaId) {
        console.log("facturas controller: getFactura id: " + facturaId);

        const requestUrl = apiController.getBaseUrl() + "/facturas/" + facturaId;
        try {
            console.log("realizando petición a: " + requestUrl);
            const request = await fetch(requestUrl);

            const respuesta = await request.json();
            if (request.status == 200) {

                console.log("facturas controller respuesta OK 200");
                // console.log(respuesta);

                return { "data": respuesta.data, "status": 200, "success": true };
            }

            if (request.status == 404) {

                console.log("facturas respuesta NOT FOUND 404");
                // console.log(respuesta);

                return { "data": respuesta.message, "status": 404, "success": false };
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

    async function createFactura(facturaObj) {
        console.log("facturas controller: createFactura ");
        const requestUrl = apiController.getBaseUrl() + "/facturas";

        console.log("Realizando petición a: " + requestUrl);
        try {
            const requestBody = {
                method: "POST",
                body: JSON.stringify(facturaObj),
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

    async function updateFactura(facturaObj) {
        console.log("facturas controller: updatefactura ");
        const requestUrl = apiController.getBaseUrl() + "/facturas/" + facturaObj.facturaId;

        console.log("Realizando petición a: " + requestUrl);
        try {
            const requestBody = {
                method: "PUT",
                body: JSON.stringify(facturaObj),
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

    async function deleteFactura(facturaId) {
        console.log("facturas controller: deleteFactura id: " + facturaId);

        const requestUrl = apiController.getBaseUrl() + "/facturas/" + facturaId;
        try {
            const requestBody = {
                method: "DELETE",
                headers: {
                    "Content-type": "application/json; charset=UTF-8",
                },
            };
            console.log("realizando petición a: " + requestUrl);
            const request = await fetch(requestUrl, requestBody);

            if (request.status == 200) {
                const respuesta = await request.json();

                console.log("facturas controller respuesta OK 200");
                // console.log(respuesta);

                return { "data": respuesta.data, "status": 200, "success": true };
            }
            if (request.status == 404) {
                console.log("Respuesta 404: NOT FOUND");
                // return {estado:404,data:datos,success:false};
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

    async function asociarTrabajo(facturaId, trabajoId) {
        console.log("facturas controller: asociar trabajo " + trabajoId + " a factura " + facturaId);
        const requestUrl = apiController.getBaseUrl() + "/facturas/" + facturaId + "/asociar-trabajo";

        console.log("Realizando petición a: " + requestUrl);
        try {
            const requestBody = {
                method: "PUT",
                body: JSON.stringify({
                    'trabajoId': trabajoId,
                }),
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
            if (request.status == 422) {
                console.log("Respuesta 422: VALIDATION ERROR");
                // console.log(datos);
                // return { estado: 422, data: datos, "success": false };
            }

            return { estado: request.status, data: datos.message, "success": false };
        } catch (e) {
            console.log("Excepción en petición:");
            console.log(e);

            return { "data": e, "success": false };
        }
    }

    async function desasociarTrabajo(facturaId, trabajoId) {
        console.log("facturas controller: asociar trabajo " + trabajoId + " a factura " + facturaId);
        const requestUrl = apiController.getBaseUrl() + "/facturas/" + facturaId + "/desasociar-trabajo";

        console.log("Realizando petición a: " + requestUrl);
        try {
            const requestBody = {
                method: "PUT",
                body: JSON.stringify({
                    'trabajoId': trabajoId,
                }),
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
            if (request.status == 422) {
                console.log("Respuesta 422: VALIDATION ERROR");
                // console.log(datos);
                // return { estado: 422, data: datos, "success": false };
            }

            return { estado: request.status, data: datos.message, "success": false };
        } catch (e) {
            console.log("Excepción en petición:");
            console.log(e);

            return { "data": e, "success": false };
        }
    }

    return {
        getFacturas,
        getFactura,
        createFactura,
        updateFactura,
        deleteFactura,
        asociarTrabajo,
        desasociarTrabajo,
    }
})();

window.$facturasController = $facturasController;
export default $facturasController;