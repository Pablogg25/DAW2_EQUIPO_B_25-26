import apiController from "./ApiController";

const $prendasController = (function () {
    console.log("PrendasController inicializado");

    async function getPrendas(params) {
        console.log("Prendas controler getPrendas");

        const requestUrl = apiController.getBaseUrl() + "/prendas";

        try {
            console.log("Realizando petición a: " + requestUrl);

            const request = await fetch(requestUrl);

            if (request.status == 200) {
                console.log("prendasController repuesta 200 OK");

                const respuesta = await request.json();
                return { "data": respuesta, "status": 200, "success": true };

            }
            //else error
            console.log("error al obtener datos");
            return { "data": null, "status": request.status, "success": false };

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
            console.log("Realizando petición a: " + requestUrl);

            const request = await fetch(requestUrl);

            if (request.status == 200) {
                console.log("prendasController repuesta 200 OK");

                const respuesta = await request.json();
                return { "data": respuesta, "status": 200, "success": true };

            }
            if (request.status == 404) {
                console.log("PrendasControler respuesta NOT FOUND 404")
            }
            //else error
            console.log("error al obtener datos");
            return { "data": null, "status": request.status, "success": false };

        } catch (e) {
            console.log("Excepción en petición:");
            console.log(e);

            return { "data": e, "success": false };
        }
    }

    async function createPrenda(prendaObj) {
        console.log("PrendasControler create prenda");

        const requestUrl = apiController.getBaseUrl() + "/prendas";
        const requestBody = {
            method: "POST",
            body: JSON.stringify(prendaObj),
            headers: {
                "Content-type": "application/json; charset=UTF-8",
            },
        }

        try {
            console.log("Realizando petición a: " + requestUrl);

            const request = await fetch(requestUrl, requestBody);
            const datos = await request.json();


            if (request.status == 201) {
                console.log("Respuesta 201: CREATED");
                return { estado: 201, data: datos, "success": true };
            }
            if (request.status == 400) {
                console.log("Respuesta 400: VALIDATION ERROR");
                // console.log(datos);
                return { estado: 400, data: datos, "success": false };
            }
            //else error
            console.log("error al obtener datos");
            return { estado: request.status, data: datos, "success": false };


        } catch (e) {
            console.log("Excepción en petición:");
            console.log(e);

            return { "data": e, "success": false };
        }
    }

    async function updatePrenda(prendaObj,prendaId){
        console.log("PrendasControler update prenda id: "+prendaId);

        const requestUrl = apiController.getBaseUrl() + "/prendas/"+prendaId;
        const requestBody = {
            method: "PUT",
            body: JSON.stringify(prendaObj),
            headers: {
                "Content-type": "application/json; charset=UTF-8",
            },
        }

        try {
            console.log("Realizando petición a: " + requestUrl);

            const request = await fetch(requestUrl, requestBody);
            const datos = await request.json();


            if(request.status==200){
                console.log("Respuesta 200: OK");
                return {estado:201,data:datos,"success":true};
            }
            if (request.status == 400) {
                console.log("Respuesta 400: VALIDATION ERROR");
                // console.log(datos);
                return { estado: 400, data: datos, "success": false };
            }
            //else error
            console.log("error al obtener datos");
            return { estado: request.status, data: datos, "success": false };


        } catch (e) {
            console.log("Excepción en petición:");
            console.log(e);

            return { "data": e, "success": false };
        }
    }

    async function deletePrenda(prendaId) {
        console.log("prendasController: delete prenda id: "+prendaId);

        try{
            const requestBody={
                method:"DELETE",
                headers:{
                    "Content-type":"application/json; charset=UTF-8",
                },
            };
            const respuesta= await fetch(apiController.getBaseUrl()+"/prendas/"+prendaId,requestBody);

            const datos=await respuesta.json();

            if(respuesta.status==200){
                console.log("Respuesta 200: OK");
                return {estado:200,data:datos,success:true};
            }
            if(respuesta.status==404){
                console.log("Respuesta 404: NOT FOUND");
                return {estado:404,data:datos,success:false};
            }
            return {estado:respuesta.status,data:datos,success:false};
        }catch(e){
            console.log("$negocioApi: Resultado error");
            console.log(e);
            return {"data":e,"success":false};
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