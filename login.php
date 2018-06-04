<?php
	require_once 'core.php';
	title('Login');
	top_public();
?>
<div class="row public-container">
	<div class="col-lg-3"></div>
	<div class="col-lg-6">
        <p>
            Please login to continue. Or you can <a href="/register.php">create a free account</a>
        </p>
		<form id="login">
		  <div class="form-group">
			<label for="email">Email</label>
			<input type="email" required class="form-control" id="email" placeholder="Email">
		  </div>
		  <div class="form-group">
			<label for="password">Password</label>
			<input type="password" required class="form-control" id="password" placeholder="Password">
		  </div>
		  <button type="submit" class="btn btn-primary">Login</button>
		  <a class="btn btn-warning" href="./forget-password.php">Forget Password</a>
		</form>

        <div class="btn fb-login-button" data-max-rows="1" data-size="large" data-button-type="login_with" data-show-faces="false" data-auto-logout-link="false" data-use-continue-as="true"></div>
	</div>
</div>
<script>
(()=>{
	$("#login").submit((e)=>{
        e.preventDefault();
        $app.apisync.post("/login", {
            email: $('#email').val(),
            password: $('#password').val(),
        }).then((r)=>{
            $tool.setData('auth', r.data);
            window.location.replace(ctx + "/dashboard.php");
        });
    });
})();
</script>
<?=bottom_public()?>