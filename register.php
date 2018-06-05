<?php
	require 'core.php';
	title('Register');
	top_public();
?>

<div class="row public-container">
	<div class="col-lg-3"></div>
	<div class="col-lg-6">
        <p>
            Create your free account, or <a href="/login.php">login</a>
        </p>
		<form id="register">
		  <div class="form-group">
			<label for="email">Email</label>
			<input type="text" required class="form-control" id="email" placeholder="Email">
		  </div>
		  <div class="form-group">
			<label for="password">Password</label>
			<input type="password" required class="form-control" id="password" placeholder="Password">
		  </div>
		  <button type="submit" class="btn btn-primary">Register</button>
		</form>
	</div>
</div>
<script>
(()=>{
    $("#register").submit((e)=>{
        e.preventDefault();
        $app.apisync.post("/register", {
            email: $('#email').val(),
            password: $('#password').val()
        }).then((r)=>{
            $tool.setData('auth', r.data);
            window.location.replace(ctx + "/dashboard.php");
        });
    });
})();
</script>


<?=bottom_public()?>