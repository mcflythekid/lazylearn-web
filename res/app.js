var ctx = "";

var $app = ((e)=>{
    e.endpoint = "http://localhost:8088";
    e.api =  axios.create({
        baseURL: e.endpoint,
        headers: {'Bearer': $tool.getData("token")}
    });
    e.apisync =  axios.create({
        baseURL: e.endpoint,
        headers: {'Bearer': $tool.getData("token")}
    });
    var initApisync = ()=>{
        e.apisync.interceptors.request.use(function (config) {
            $tool.lock();
            return config;
        }, function (error) {
            $tool.unlock();
            return Promise.reject(error);
        });
        e.apisync.interceptors.response.use(function (response) {
            $tool.unlock();
            return response;
        }, function (error) {
            $tool.unlock();
            return Promise.reject(error);
        });
    };
    (()=>{
        initApisync();
    })();
	return e;
})({});
//$app.init();