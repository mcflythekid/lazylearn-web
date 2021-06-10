<?php
	require_once '../core.php';
	require_once '../lang/core.php';
	$TITLE = $lang["loginpage.title"];
	top_public();
?>

<div class="row u-mt-20">
	<div class="col-lg-4 col-md-6 col-lg-offset-4 col-md-offset-3">
        <p>
            <?= $lang["loginpage.text1"] ?><a href="/auth/register.php"><?= $lang["loginpage.text2"] ?></a>
        </p>
		<form id="login">
		  <div class="form-group">
			<label for="email"><?= $lang["loginpage.form.email.label"] ?></label>
			<input type="email" required class="form-control" id="email" placeholder='<?= $lang["loginpage.form.email.holder"] ?>'>
		  </div>
		  <div class="form-group">
			<label for="rawPassword"><?= $lang["loginpage.form.password.label"] ?></label>
			<input type="password" required class="form-control" id="rawPassword" placeholder='<?= $lang["loginpage.form.password.holder"] ?>'>
		  </div>
		  <button type="submit" class="btn btn-primary"><?= $lang["loginpage.form.btn.submit"] ?></button>
		  <a class="btn btn-warning" href="forget-password.php"><?= $lang["loginpage.form.btn.forgot"] ?></a>
		</form>

        <!-- <button id="login-facebook-btn" class="btn btn-primary u-mt-15"> -->
           <!--   <i class="fa fa-facebook-official u-mr-5" aria-hidden="true"></i> -->
          <!--   <?= $lang["loginpage.form.btn.facebook"] ?> -->
       <!--  </button> -->

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