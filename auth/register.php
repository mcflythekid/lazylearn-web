<?php
	require '../core.php';
	$TITLE = 'Register';
	top_public();
?>

<div class="row u-mt-20">
    <div class="col-lg-4 col-md-6 col-lg-offset-4 col-md-offset-3">
        <p>
            Create your free account, or <a href="/auth/login.php">login</a>
        </p>
		<form id="register">
		  <div class="form-group">
			<label for="email">Email</label>
			<input type="text" required class="form-control" id="email" placeholder="Email">
		  </div>
		  <div class="form-group">
			<label for="rawPassword">Password</label>
			<input type="password" required class="form-control" id="rawPassword" placeholder="Password">
		  </div>
		  <button type="submit" class="btn btn-primary">Register</button>
		</form>

        <button id="login-facebook-btn" class="btn btn-primary u-mt-15">
            <i class="fa fa-facebook-official" aria-hidden="true"></i>
            Continue with Facebook
        </button>
	</div>
</div>

<script>
$("#register").submit((e)=>{
    e.preventDefault();
    Auth.register($('#email').val(), $('#rawPassword').val());
});
$('#login-facebook-btn').click(()=>{
    Auth.loginFacebook();
})
</script>

<?=bottom_public()?>