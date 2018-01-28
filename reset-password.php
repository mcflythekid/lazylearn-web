<?php
	require 'core.php';
	title('Reset password');
	top();
?>
<div class="row">
	<div class="col-lg-3"></div>
	<div class="col-lg-6">
		<form id="reset">
		  <div class="form-group">
			<label for="email">New password</label>
			<input type="text" required class="form-control" id="password" placeholder="Your new password">
		  </div>
		  <button type="submit" class="btn btn-primary">Change</button>
		</form>
	</div>
</div>
<script>
    (()=>{
        $("#reset").submit((e)=>{
            e.preventDefault();
            $app.apisync.put("/user/by-forget-password/" + $tool.param('id'), {
                password: $('#password').val()
            }).then((r)=>{
                $tool.flash(1, 'Please login');
                setInterval($app.logout, 1000);
            });
        });
    })();
</script>
<?=bottom()?>