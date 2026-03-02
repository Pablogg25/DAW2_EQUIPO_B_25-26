import apiController from "./ApiController";

const $usersController = (function () {
  console.log("Inicializando userscontroller");

  //params {'username':string} exacto
  async function getUsers(params={}) {
    console.log("usersController getUsers");

    let requestUrl = apiController.getBaseUrl() + "/usuarios";
    console.log(params);
    if(params['username'] && params['username'].trim() !== ""){
      requestUrl+='?username='+params['username'];
    }
    try {
      console.log("Realizando petición a: " + requestUrl);
      const request = await fetch(requestUrl);
      const respuesta = await request.json();

      if (request.status === 200) {
        console.log("usersController respuesta 200 OK");
        return { data: respuesta.data, status: 200, success: true };
      }

      return {
        data: respuesta.message,
        status: request.status,
        success: false,
      };
    } catch (e) {
      return { data: e, success: false };
    }
  }

  async function getUser(userId) {
    const requestUrl = apiController.getBaseUrl() + "/usuarios/" + userId;

    try {
      const request = await fetch(requestUrl);
      const respuesta = await request.json();

      if (request.status === 200) {
        return { data: respuesta.data, status: 200, success: true };
      }

      return {
        data: respuesta.message,
        status: request.status,
        success: false,
      };
    } catch (e) {
      return { data: e, success: false };
    }
  }

  async function getUserByUsername(username) {
    const requestUrl =
      apiController.getBaseUrl() + "/usuarios?username=" + username;

    try {
      const request = await fetch(requestUrl);
      const respuesta = await request.json();

      if (request.status === 200) {
        return { data: respuesta.data[0], status: 200, success: true };
      }

      return {
        data: respuesta.message,
        status: request.status,
        success: false,
      };
    } catch (e) {
      return { data: e, success: false };
    }
  }

  async function createUser(objUser) {
    const requestUrl = apiController.getBaseUrl() + "/usuarios";

    try {
      const requestBody = {
        method: "POST",
        body: JSON.stringify(objUser),
        headers: {
          "Content-type": "application/json; charset=UTF-8",
        },
      };

      const request = await fetch(requestUrl, requestBody);
      const datos = await request.json();

      if (request.status === 201) {
        return { estado: 201, data: datos.data, success: true };
      }

      return { estado: request.status, data: datos.message, success: false };
    } catch (e) {
      return { data: e, success: false };
    }
  }

  async function loginUser(objUserCred) {
    console.log("usersController loginUser");

    const requestUrl = apiController.getBaseUrl() + "/usuarios/login";

    try {
      const requestBody = {
        method: "POST",
        body: JSON.stringify(objUserCred),
        headers: {
          "Content-type": "application/json; charset=UTF-8",
        },
      };

      const request = await fetch(requestUrl, requestBody);
      const respuesta = await request.json();

      if (request.status === 200) {
        return { data: respuesta.valid, status: 200, success: true };
      }

      return {
        data: respuesta.message,
        status: request.status,
        success: false,
      };
    } catch (e) {
      return { data: e, success: false };
    }
  }

  async function updateUser(objUser, userId) {
    const requestUrl = apiController.getBaseUrl() + "/usuarios/" + userId;

    try {
      const requestBody = {
        method: "PUT",
        body: JSON.stringify(objUser),
        headers: {
          "Content-type": "application/json; charset=UTF-8",
        },
      };

      const request = await fetch(requestUrl, requestBody);
      const datos = await request.json();

      if (request.status === 200) {
        return { estado: 200, data: datos.data, success: true };
      }

      return { estado: request.status, data: datos.message, success: false };
    } catch (e) {
      return { data: e, success: false };
    }
  }

  async function updatePassword(objPassword, userId) {
    const requestUrl =
      apiController.getBaseUrl() + "/usuarios/" + userId + "/password";

    try {
      const requestBody = {
        method: "PUT",
        body: JSON.stringify(objPassword),
        headers: {
          "Content-type": "application/json; charset=UTF-8",
        },
      };

      const request = await fetch(requestUrl, requestBody);
      const datos = await request.json();

      if (request.status === 200) {
        return { estado: 200, data: datos, success: true };
      }

      return { estado: request.status, data: datos.message, success: false };
    } catch (e) {
      return { data: e, success: false };
    }
  }

  async function deleteUser(userId) {
    const requestUrl = apiController.getBaseUrl() + "/usuarios/" + userId;

    try {
      const requestBody = {
        method: "DELETE",
        headers: {
          "Content-type": "application/json; charset=UTF-8",
        },
      };

      const respuesta = await fetch(requestUrl, requestBody);
      const datos = await respuesta.json();

      if (respuesta.status === 200) {
        return { estado: 200, data: datos.data, success: true };
      }

      return { estado: respuesta.status, data: datos.message, success: false };
    } catch (e) {
      return { data: e, success: false };
    }
  }

  return {
    getUsers,
    getUser,
    getUserByUsername,
    loginUser,
    createUser,
    updateUser,
    updatePassword,
    deleteUser,
  };
})();

export default $usersController;
