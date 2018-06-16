/**
 * @author McFly the Kid
 */
var AppApi = ((e, apiServer, HoldOn, Storage, FlashMessage)=>{

    var handleError = function(error){
        if (error.response && error.response.data && error.response.data.msg){
            FlashMessage.error(error.response.data.msg);
        } else{
            FlashMessage.error('Cannot process request.');
        }
    };

    var syncInstance =  axios.create({
        baseURL: apiServer,
        headers: {'Access-Control-Allow-Origin': '*'}
    });
    syncInstance.interceptors.request.use(function (config) {
        HoldOn.open();
        if (Storage.get('accessToken')) config.headers.Authorization = 'Bearer ' + Storage.get('accessToken');
        return config;
    }, function (error) {
        HoldOn.close();
        return Promise.reject(error);
    });
    syncInstance.interceptors.response.use(function (response) {
        HoldOn.close();
        return response;
    }, function (error) {
        HoldOn.close();
        handleError(error);
        return Promise.reject(error);
    });


    var asyncInstance =  axios.create({
        baseURL: apiServer,
        headers: {'Access-Control-Allow-Origin': '*'}
    });
    asyncInstance.interceptors.request.use(function (config) {
        if (Storage.get('accessToken')) config.headers.Authorization = 'Bearer ' + Storage.get('accessToken');
        return config;
    }, function (error) {
        return Promise.reject(error);
    });
    asyncInstance.interceptors.response.use(function (response) {
        return response;
    }, function (error) {
        handleError(error);
        return Promise.reject(error);
    });

    e.sync = syncInstance;
    e.async = asyncInstance;
    return e;
})({}, apiServer, HoldOn, Storage, FlashMessage);