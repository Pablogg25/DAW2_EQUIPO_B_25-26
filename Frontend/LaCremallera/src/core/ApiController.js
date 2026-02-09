
const apiController=(function (){
    const api_base_url="http.//127.0.0.1:5000";

    let getBaseUrl=function(){
        return api_base_url;
    }

    return {
        getBaseUrl,
    }
})();

export default apiController;