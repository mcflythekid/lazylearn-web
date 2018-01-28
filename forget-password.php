<?php
	require 'core.php';
	title('Forget password');
	top();
?>
<div class="row">
	<div class="col-lg-3"></div>
	<div class="col-lg-6">
		<form id="forget">
		  <div class="form-group">
			<label for="email">Email</label>
			<input type="text" required class="form-control" id="email" placeholder="Email">
		  </div>
		  <button type="submit" class="btn btn-primary">Submit</button>
		</form>
	</div>
</div>
<script>
    (()=>{
        $("#forget").submit((e)=>{
            e.preventDefault();
            $app.apisync.post("/forget-password", {
                email: $('#email').val()
            }).then((r)=>{
                $tool.flash(1, 'Please check your inbox');
            });
        });
    })();
</script>
<?=bottom()?>