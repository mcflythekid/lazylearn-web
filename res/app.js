var $app = ((e)=>{
    e.ctx = "";
    e.endpoint = "http://localhost:8088";
    var getBearerToken = ()=>{

        var auth = $tool.getData('auth');
        if (auth){
            return "Bearer " + auth.token;
        }
        return "";
    };
    e.api = axios.create({
        baseURL: e.endpoint,
        headers: {
            'Authorization': getBearerToken(),
            'Access-Control-Allow-Origin': '*',
        }
    });
    e.apisync = axios.create({
        baseURL: e.endpoint,
        headers: {
            'Authorization': getBearerToken(),
            'Access-Control-Allow-Origin': '*',
        }
    });
    e.logout = ()=>{
        $tool.removeData('auth');
        window.location.replace(ctx + "/login.php");
    };
    var publicPages = [
        "/login.php",
        "/register.php",
    ]
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
    var ping = function(){
        if (publicPages.includes(location.pathname)) return;
        e.api.get("/ping").then(()=>{}).catch((err)=>{
            if (err.response && err.response.status == 401){
                e.logout();
            }
        });
    };
    (()=>{
        initApisync();
        ping();
        setInterval(()=>{
            ping();
        }, 5000);
    })();
    return e;
})({});