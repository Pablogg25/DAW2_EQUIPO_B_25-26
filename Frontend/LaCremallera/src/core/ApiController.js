const apiController = (function () {
  const api_base_url = "http://44.223.237.222/api";

  let getBaseUrl = function () {
    return api_base_url;
  };

  return {
    getBaseUrl,
  };
})();

export default apiController;
