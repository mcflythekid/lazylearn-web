var $app = ((endpoint)=>{
    var e = {};

    e.axiosErrorMsg = 'Cannot process request';
    e.axiosTimeout = 30000;

    e.ctx = "";
    e.endpoint = endpoint;
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


    e.logout = ()=>{
        $tool.removeData('auth');
        window.location.replace(ctx + "/login.php");
    };
    var onlyPublicPages = [  // Cannot see when logged in
        "/login.php",
        "/forget-password.php",
        "/reset-password.php",
        "/register.php",
        "/"
    ];
    var lockOnlyPublicPages = ()=>{
        if ($tool.getData('auth') && onlyPublicPages.includes(location.pathname)){
            window.location.replace(ctx + "/dashboard.php");
        }
    };


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
        } else {
            $tool.flash(0, e.axiosErrorMsg);
        }
    };


    (()=>{
        initApisync();
        initApi();
        lockOnlyPublicPages();
    })();


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