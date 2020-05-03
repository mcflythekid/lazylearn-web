<?php
require '../core.php';
require '../lang/core.php';
$TITLE = $lang["page.change_password.title"];
$HEADER = $lang["page.change_password.header"];
$PATHS = [
    $lang["page.change_password.header"]
];
top_private();
?>

<div class="row u-mt-20">
    <div class="col-lg-4 col-md-6 col-lg-offset-4 col-md-offset-3">
        <form id="change">
            <div class="form-group">
                <label for="newRawPassword"><?= $lang["page.change_password.input.pass1"] ?></label>
                <input type="password" required class="form-control" id="newRawPassword" placeholder="<?= $lang["page.change_password.input.pass1"] ?>">
            </div>
            <div class="form-group">
                <label for="confirmNewRawPassword"><?= $lang["page.change_password.input.pass2"] ?></label>
                <input type="password" required class="form-control" id="confirmNewRawPassword" placeholder="<?= $lang["page.change_password.input.pass2"] ?>">
            </div>
          <button type="submit" class="btn btn-primary"><?= $lang["page.change_password.btn.submit"] ?></button>
        </form>
    </div>
</div>

<script>
    $("#change").submit((e)=>{
        e.preventDefault();
        Auth.changePassword(
            $('#newRawPassword').val(),
            $('#confirmNewRawPassword').val(),
            ()=>{
                $('#newRawPassword').focus().val('');
                $('#confirmNewRawPassword').val('');
            }
        )
    });
</script>
<?=bottom_private()?>