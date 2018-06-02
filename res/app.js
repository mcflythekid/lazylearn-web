var $app = ((endpoint)=>{
    var e = {};
    e.axiosErrorMsg = 'Cannot process request';
    e.axiosTimeout = 30000;
    e.ctx = "";
    e.endpoint = endpoint;

    ///////////////////////////////////////////////////////////////////////////////////////////////
    var getBearerToken = ()=>{
        var auth = $tool.getData('auth');
        if (auth){
            return "Bearer " + auth.token;
        }
        return "";
    };
    e.api = axios.create({
        timeout: e.axiosTimeout,
        baseURL: e.endpoint,
        headers: {
            'Authorization': getBearerToken(),
            'Access-Control-Allow-Origin': '*',
        }
    });
    e.apisync = axios.create({
        timeout: e.axiosTimeout,
        baseURL: e.endpoint,
        headers: {
            'Authorization': getBearerToken(),
            'Access-Control-Allow-Origin': '*',
        }
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
            processError(error);
            return Promise.reject(error);
        });
    };
    var initApi = ()=>{
        e.api.interceptors.response.use(function (response) {
            return response;
        }, function (error) {
            processError(error);
            return Promise.reject(error);
        });
    };
    var processError = function(error){
        if (error.response && error.response.data && error.response.data.msg){
            $tool.flash(0, error.response.data.msg);
        } else if (error.response && error.response.data && error.response.data.error === 'invalid_token') {
            $tool.removeData('auth');
            window.location.replace(ctx + "/login.php");
        } else{
            $tool.flash(0, e.axiosErrorMsg);
        }
    };
    initApisync();
    initApi();
    ///////////////////////////////////////////////////////////////////////////////////////////////

    ///////////////////////////////////////////////////////////////////////////////////////////////
    var publicPages = [  // Cannot see when logged in
        "/login.php",
        "/forget-password.php",
        "/reset-password.php",
        "/register.php",
        "/"
    ];
    var checkPrivatePublicSite = ()=>{
        if ($tool.getData('auth') && publicPages.includes(location.pathname)){ // login: ban public
            window.stop();
            window.location.replace(ctx + "/dashboard.php");
            return;
        }
        if (!$tool.getData('auth') && !publicPages.includes(location.pathname)){ // guest: ban private
            window.stop();
            window.location.replace(ctx + "/login.php");
            return;
        }
        if ($tool.getData('auth')){ // perform a ping (Force login if token is invalid)
            e.api.post("/ping");
        }
    };
    checkPrivatePublicSite();
    ///////////////////////////////////////////////////////////////////////////////////////////////

    e.logout = function(){
        e.apisync.post("/revoke-token").then(function(res){
            $tool.removeData('auth');
            window.location.replace(ctx + "/login.php");
        });
    };
    ///////////////////////////////////////////////////////////////////////////////////////////////
    e.quillFrontConf = {
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline'],
                [{align: ''}, {align: 'center'}, {align: 'right'}, {align: 'justify'}],
                ['link', 'image', 'code-block']
            ],
            imageResize: {
                // See optional "config" below
            },
            magicUrl: true
        },
        placeholder: 'Front side...',
        theme: 'snow'  // or 'bubble'
    };
    e.quillBackConf = {
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline'],
                [{align: ''}, {align: 'center'}, {align: 'right'}, {align: 'justify'}],
                ['link', 'image', 'code-block']
            ],
            imageResize: {
                // See optional "config" below
            },
            magicUrl: true
        },
        placeholder: 'Front side...',
        theme: 'snow'  // or 'bubble'
    };
    return e;
})(endpoint);