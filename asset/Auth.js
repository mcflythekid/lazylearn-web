/**
 * @author McFly the Kid
 */
var Auth = ((e, AppApi, Constant, FlashMessage)=>{

    var postLogin = (loginResponseData, redirectUrl)=>{
        Storage.set('accessToken', loginResponseData.accessToken);
        Storage.set('userData', loginResponseData.userData);
        if (!redirectUrl){
            window.location = Constant.dashboardUrl;
        } else {
            window.location = redirectUrl;
        }
    };

    e.logout = ()=>{
        Storage.delete('accessToken');
        Storage.delete('userData');
        //window.location = Constant.loginUrl;
        window.location = "/";
    };

    e.register = (email, rawPassword)=>{
        AppApi.sync.post("/register", {
            email: email,
            rawPassword: rawPassword
        }).then((response)=>{
            postLogin(response.data);
        });
    };

    e.login = (email, rawPassword)=>{
        AppApi.sync.post("/login", {
            email: email,
            rawPassword: rawPassword
        }).then((response)=>{
            postLogin(response.data);
        });
    };

    e.forceLogin = (userId)=>{
        AppApi.sync.post("/force-login/" + userId).then((response)=>{
            postLogin(response.data);
        });
    };

    e.loginFacebook = ()=>{
        FB.login(function(facebookResponse) {
            if (facebookResponse.status= "connected"){
                AppApi.sync.post("/login-facebook", {
                    accessToken: facebookResponse.authResponse.accessToken
                }).then((response)=>{
                    postLogin(response.data);
                });
            }
        }, {scope: 'public_profile'});
    };

    e.changePassword = (newRawPassword, confirmNewRawPassword, callback)=>{
        if (newRawPassword !== confirmNewRawPassword){
            FlashMessage.error('Password does not matched');
            return;
        }
        AppApi.sync.post("/change-password", {
            newRawPassword: newRawPassword
        }).then((response)=>{
            FlashMessage.success(response.data.msg);
            if (callback){
                callback();
            }
        });
    };

    e.getAllSession = (callback)=>{
        AppApi.async.post("/get-all-session").then((response)=>{
            callback(response.data);
        });
    };

    e.logoutAllSession = ()=>{
        AppApi.sync.post("/logout-all-session").then((response)=>{
            postLogin(response.data, Constant.securityUrl);
        });
    };

    e.forgetPassword = (email)=>{
        AppApi.sync.post('/forget-password', {
            email: email
        }).then((response)=>{
            FlashMessage.success(response.data.msg);
        });
    };

    e.resetPassword = (resetId, newRawPassword)=>{
        AppApi.sync.post('/reset-password', {
            resetId: resetId,
            newRawPassword: newRawPassword
        }).then((response)=>{
            postLogin(response.data);
        });
    }

    return e;
})({}, AppApi, Constant, FlashMessage);