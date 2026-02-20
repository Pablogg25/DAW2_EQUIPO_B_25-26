import apiController from "./ApiController";

const $facturasController=(function (){
    console.log("Inicializar $facturas controller");

    async function getFacturas(params){
        console.log("facturas controller: getFacturas");

        const requestUrl=apiController.getBaseUrl()+"/facturas";


        try{
            console.log("realizando petición a: "+requestUrl);
            const request = await fetch(requestUrl);

            if (request.status == 200) {
                const respuesta = await request.json();

                console.log("facturas controller respuesta OK 200");
                // console.log(respuesta);

                return { "data": respuesta, "status": 200, "success": true };
            }

            //else error
            console.log("error al obtener datos");
            return { "data": request.message, "status": request.status, "success": false };
 

        } catch (e) {
            console.log("Excepción en petición:");
            console.log(e);

            return {"data":e,"success":false};
        }
    }
})();

