<?php
	require '../core.php';
	require '../lang/core.php';
	$TITLE = $lang["registerpage.title"];
	top_public();
?>

<div class="row u-mt-20">
    <div class="col-lg-4 col-md-6 col-lg-offset-4 col-md-offset-3">
        <p>
            <?= $lang["registerpage.text1"] ?> <a href="/auth/login.php"><?= $lang["registerpage.text2"] ?></a>
        </p>
		<form id="register">
		  <div class="form-group">
			<label for="email"><?= $lang["registerpage.form.email.label"] ?></label>
			<input type="text" required class="form-control" id="email" placeholder='<?= $lang["registerpage.form.email.holder"] ?>'>
		  </div>
		  <div class="form-group">
			<label for="rawPassword"><?= $lang["registerpage.form.password.label"] ?></label>
			<input type="password" required class="form-control" id="rawPassword" placeholder='<?= $lang["registerpage.form.password.holder"] ?>'>
		  </div>
		  <button type="submit" class="btn btn-primary"><?= $lang["registerpage.form.btn.submit"] ?></button>
		</form>

        <button id="login-facebook-btn" class="btn btn-primary u-mt-15">
            <i class="fa fa-facebook-official" aria-hidden="true"></i>
            <?= $lang["registerpage.form.btn.facebook"] ?>
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