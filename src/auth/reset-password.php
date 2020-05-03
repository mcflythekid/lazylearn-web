<?php
	require '../core.php';
	require '../lang/core.php';
	$TITLE = $lang["page.reset_password.title"];
	top_public();
    $resetId = '';
	if (isset($_GET["id"])) $resetId = $_GET["id"];
?>



<div class="row u-mt-20">
    <div class="col-lg-4 col-md-6 col-lg-offset-4 col-md-offset-3">
        <p>
            <?= $lang["page.reset_password.guide"] ?>
        </p>
		<form id="reset">
		  <div class="form-group">
			<label for="newRawPassword"><?= $lang["page.reset_password.input.pass"] ?></label>
			<input type="text" required class="form-control" id="newRawPassword" placeholder="<?= $lang["page.reset_password.input.pass"] ?>">
		  </div>
		  <button type="submit" class="btn btn-primary btn-flat"><?= $lang["page.reset_password.btn.submit"] ?></button>
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