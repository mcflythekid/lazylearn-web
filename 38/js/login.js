$('#username').focus();
$('#login_form').submit(function (e) {
    var user_name = $.trim($('#username').val());
	var password = $.trim($('#password').val());
    if (user_name  === '') {
		$("div#acct_login").before('<div id="warning">Your username or password is invalid.</div>');
		$('#username').focus();
		e.preventDefault();
		return false;
    }
	if (password === '') {
		$("div#acct_login").before('<div id="warning">Your username or password is invalid.</div>');
		$('#password').focus();
		e.preventDefault();
    }
});