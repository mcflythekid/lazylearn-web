/**
 * @author McFly the Kid
 */
var Constant = ((Constant)=>{
    Constant.dashboardUrl = '/dashboard.php';
    Constant.deckUrl = '/deck';
    Constant.minpairUrl = '/minpair';
    Constant.vocabularyUrl = '/vocabulary';
    Constant.loginUrl = '/auth/login.php';
    Constant.securityUrl = '/auth/security.php';
    Constant.blockedAfterLoginUrls = [
        "/",
        "/auth/login.php",
        "/auth/register.php",
        "/auth/forget-password.php",
        "/auth/reset-password.php",
    ];
    Constant.minpairCount = 120;
    return Constant;
})({});