const apiController = (function () {
  const api_base_url = "http://127.0.0.1:8000/api";

  let getBaseUrl = function () {
    return api_base_url;
  };

  return {
    getBaseUrl,
  };
})();

export default apiController;
