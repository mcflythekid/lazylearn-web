<?php
	require_once '../core.php';
	$TITLE = 'Login';
	top_public();
?>

<div class="row u-mt-20">
	<div class="col-lg-4 col-md-6 col-lg-offset-4 col-md-offset-3">
        <p>
            Please login to continue. Or you can <a href="/auth/register.php">create a free account</a>
        </p>
		<form id="login">
		  <div class="form-group">
			<label for="email">Email</label>
			<input type="email" required class="form-control" id="email" placeholder="Email" value="odopoc@gmail.com">
		  </div>
		  <div class="form-group">
			<label for="rawPassword">Password</label>
			<input type="password" required class="form-control" id="rawPassword" placeholder="Password" value="dkmm">
		  </div>
		  <button type="submit" class="btn btn-primary">Login</button>
		  <a class="btn btn-warning" href="forget-password.php">Forget Password</a>
		</form>

        <button id="login-facebook-btn" class="btn btn-primary u-mt-15">
            <i class="fa fa-facebook-official u-mr-5" aria-hidden="true"></i>
            Continue with Facebook
        </button>

	</div>
</div>

<script>
$("#login").submit((e)=>{
    e.preventDefault();
    Auth.login($('#email').val(), $('#rawPassword').val());
});
$('#login-facebook-btn').click(()=>{
    Auth.loginFacebook();
})
</script>

<?=bottom_public()?>