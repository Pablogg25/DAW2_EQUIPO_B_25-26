const apiController = (function () {
  // const api_base_url = "http://44.223.237.222/api";
  const api_base_url = "http://127.0.0.1:8000/api"; //localhost

  let getBaseUrl = function () {
    return api_base_url;
  };

  let getAuthToken=function (){
    return sessionStorage.getItem("authToken");
  }

  return {
    getBaseUrl,
    getAuthToken,
  };
})();

export default apiController;
