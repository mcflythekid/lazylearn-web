<?php
	require '../core.php';
	$TITLE = 'Forget password';
	top_public();
?>

<div class="row u-mt-20">
    <div class="col-lg-4 col-md-6 col-lg-offset-4 col-md-offset-3">
        <p>
            Please provide your email address, and then we will send you an instruction
        </p>
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
$("#forget").submit((e)=>{
    e.preventDefault();
    Auth.forgetPassword($('#email').val())
});
</script>

<?=bottom_public()?>