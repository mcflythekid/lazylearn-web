<?php
	require '../core.php';
	require '../lang/core.php';
	$TITLE = $lang["forgotpassword.title"];
	top_public();
?>

<div class="row u-mt-20">
    <div class="col-lg-4 col-md-6 col-lg-offset-4 col-md-offset-3">
        <p>
            <?= $lang["forgotpassword.text"] ?>
        </p>
		<form id="forget">
		  <div class="form-group">
			<label for="email"><?= $lang["registerpage.form.email.label"] ?></label>
			<input type="text" required class="form-control" id="email" placeholder='<?= $lang["registerpage.form.email.holder"] ?>'>
		  </div>
		  <button type="submit" class="btn btn-primary"><?= $lang["registerpage.form.btn.submit"] ?></button>
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