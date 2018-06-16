/**
 * @author McFly the Kid
 */
var Application = ((Application, Storage, Auth, Constant)=>{

    Application.publicPagesCheck = ()=>{
        if (Storage.get('accessToken') && Constant.blockedAfterLoginUrls.includes(location.pathname)){ // login: ban public
            window.stop();
            window.location.replace(Constant.dashboardUrl);
            return;
        }
    };

    Application.privatePagesCheck = ()=>{
        /*------------------------------------------------------------------------------------------------*/
        var userData = Storage.get('userData');
        if (!userData){
            Auth.logout();
            return;
        }
        /*------------------------------------------------------------------------------------------------*/
        if (userData.email) {
            $('#app__user_email').text(userData.email);
        } else {
            $('#app__user_changepassword').hide();
        }
        if (userData.authorities.includes('admin')){
            $('#admin_menu').show();
        }
        $('#app__user_fullname').text(userData.fullName);
        /*------------------------------------------------------------------------------------------------*/
    };

    return Application;
})({}, Storage, Auth, Constant);