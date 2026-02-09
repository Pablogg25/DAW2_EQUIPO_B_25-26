
import { useEffect } from "react";
import apiController from "./ApiController";

const ordersController=(function(){
    console.log("Inicializando ordersController");

    async function getOrders(){
        console.log("ordersController: getOrders");

        const requestUrl=apiController.getBaseUrl()+'/orders';

        //TODO: gestionar errores y códigos de error

        try{
            console.log("Realizando petición a: "+request);
            const request=await fetch(requestUrl);

            const respuesta=await request.json();

            console.log("OrdersController respuesta:");
            console.log(respuesta);

            return respuesta;

        }catch(e){
            console.log("Excepción en petición:");
            console.log(e);

            return false;
        }
    };


    return {
        getOrders,
    };
})();

export default ordersController;