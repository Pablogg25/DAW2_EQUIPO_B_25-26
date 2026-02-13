import apiController from "./ApiController";

const $usersController = (function () {
    console.log("Inicializando userscontroller");

    async function getUsers() {
        console.log("usersController getUsers");

        const requestUrl = apiController.getBaseUrl() + '/usuarios';

        try{
            console.log("Realizando petición a: " + requestUrl);
            const request = await fetch(requestUrl);

            if (request.status == 200) {
                const respuesta = await request.json();

                console.log("usersController respuesta OK 200");
                // console.log(respuesta);

                return { "data": respuesta, "status": 200, "success": true };
            }

            //else error
            console.log("error al obtener datos");
            return { "data": null, "status": request.status, "success": false };
 
        }catch (e) {
            console.log("Excepción en petición:");
            console.log(e);

            return {"data":e,"success":false};
        }
    }

    async function getUser(userId){
        console.log("usersController getUser id: "+userId);

        const requestUrl = apiController.getBaseUrl() + '/usuarios/'+userId;

        try{
            console.log("Realizando petición a: " + requestUrl);
            const request = await fetch(requestUrl);

            if (request.status == 200) {
                const respuesta = await request.json();

                console.log("usersController respuesta OK 200");
                // console.log(respuesta);

                return { "data": respuesta, "status": 200, "success": true };
            }

            if(request.status==404){
                console.log("usersController respuesta NOT FOUND 404")
            }
            //else error
            console.log("error al obtener datos");
            return { "data": null, "status": request.status, "success": false };
 
        }catch (e) {
            console.log("Excepción en petición:");
            console.log(e);

            return {"data":e,"success":false};
        }
    }

    async function createUser(objUser){
        console.log("usersController create user");

        const requestUrl = apiController.getBaseUrl() + '/usuarios';

        try{
            console.log("Realizando petición a: " + requestUrl);
            const requestBody={
                method:"POST",
                body:JSON.stringify(objUser),
                headers:{
                    "Content-type":"application/json; charset=UTF-8",
                },
            }
            const request = await fetch(requestUrl,requestBody);

            const datos=await request.json();

            if(request.status==201){
                console.log("Respuesta 201: CREATED");
                return {estado:201,data:datos,"success":true};
            }
            if(request.status==400){
                console.log("Respuesta 400: VALIDATION ERROR");
                // console.log(datos);
                return {estado:400,data:datos,"success":false};
            }

            return {estado:request.status,data:datos,"success":false};
        }catch (e) {
            console.log("Excepción en petición:");
            console.log(e);

            return {"data":e,"success":false};
        }
    }

    async function loginUser(objUserCred){
        console.log("usersController loginUser");

        const requestUrl = apiController.getBaseUrl() + '/login';

        try{
            console.log("Realizando petición a: " + requestUrl);
            const requestBody={
                method:"POST",
                body:JSON.stringify(objUserCred),
                headers:{
                    "Content-type":"application/json; charset=UTF-8",
                },
            }
            const request = await fetch(requestUrl,requestBody);

            if (request.status == 200) {
                const respuesta = await request.json();

                console.log("usersController respuesta OK 200");
                // console.log(respuesta);

                return { "data": respuesta, "status": 200, "success": true };
            }

            if(request.status==401){
                console.log("usersController respuesta WRONG CREDENTIALS 401");
                return { "data": null, "status": 401, "success": false };
            }
            //else error
            console.log("error al obtener datos");
            return { "data": null, "status": request.status, "success": false };
 
        }catch (e) {
            console.log("Excepción en petición:");
            console.log(e);

            return {"data":e,"success":false};
        }
    }

    async function updateUser(objUser,userId) {
        console.log("usersController update user");

        const requestUrl = apiController.getBaseUrl() + '/usuarios/'+userId;

        try{
            console.log("Realizando petición a: " + requestUrl);
            const requestBody={
                method:"PUT",
                body:JSON.stringify(objUser),
                headers:{
                    "Content-type":"application/json; charset=UTF-8",
                },
            }
            const request = await fetch(requestUrl,requestBody);

            const datos=await request.json();

            if(request.status==200){
                console.log("Respuesta 200: OK");
                return {estado:201,data:datos,"success":true};
            }
            if(request.status==400){
                console.log("Respuesta 400: VALIDATION ERROR");
                // console.log(datos);
                return {estado:400,data:datos,"success":false};
            }

            return {estado:request.status,data:datos,"success":false};
        }catch (e) {
            console.log("Excepción en petición:");
            console.log(e);

            return {"data":e,"success":false};
        }
    }

    async function updatePassword(objPassword,userId){
        console.log("usersController update password");

        const requestUrl = apiController.getBaseUrl() + '/usuarios/'+userId+'/password';

        try{
            console.log("Realizando petición a: " + requestUrl);
            const requestBody={
                method:"PUT",
                body:JSON.stringify(objPassword),
                headers:{
                    "Content-type":"application/json; charset=UTF-8",
                },
            }
            const request = await fetch(requestUrl,requestBody);

            const datos=await request.json();

            if(request.status==201){
                console.log("Respuesta 200: OK");
                return {estado:200,data:datos,"success":true};
            }
            if(request.status==400){
                console.log("Respuesta 400: VALIDATION ERROR");
                // console.log(datos);
                return {estado:400,data:datos,"success":false};
            }

            return {estado:request.status,data:datos,"success":false};
        }catch (e) {
            console.log("Excepción en petición:");
            console.log(e);

            return {"data":e,"success":false};
        }
    }

    async function deleteUser(userId){
        console.log("usersController: deleteOrder");

        try{
            const requestBody={
                method:"DELETE",
                headers:{
                    "Content-type":"application/json; charset=UTF-8",
                },
            };
            const respuesta= await fetch(apiController.getBaseUrl()+"/usuarios/"+userId,requestBody);

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
        getUsers,
        getUser,
        loginUser,
        createUser,
        updateUser,
        updatePassword,
        deleteUser
    }
})();

export default $usersController;