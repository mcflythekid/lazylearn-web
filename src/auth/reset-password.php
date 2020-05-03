<?php
	require '../core.php';
	require '../lang/core.php';
	$TITLE = 'Reset password';
	top_public();
    $resetId = '';
	if (isset($_GET["id"])) $resetId = $_GET["id"];
?>



<div class="row u-mt-20">
    <div class="col-lg-4 col-md-6 col-lg-offset-4 col-md-offset-3">
        <p>
            Reset your password
        </p>
		<form id="reset">
		  <div class="form-group">
			<label for="newRawPassword">New password</label>
			<input type="text" required class="form-control" id="newRawPassword" placeholder="New password">
		  </div>
		  <button type="submit" class="btn btn-primary">Change</button>
		</form>
	</div>
</div>

<script>
$("#reset").submit((e)=>{
    e.preventDefault();
    Auth.resetPassword('<?=$resetId?>', $('#newRawPassword').val());
});
</script>

<?=bottom_public()?>