<?php
require '../core.php';
$TITLE = 'Change password';
$HEADER = 'Change password';
$PATHS = [
    "Change password"
];
top_private();
?>

<div class="row u-mt-20">
    <div class="col-lg-4 col-md-6 col-lg-offset-4 col-md-offset-3">
        <form id="change">
            <div class="form-group">
                <label for="newRawPassword">New password</label>
                <input type="password" required class="form-control" id="newRawPassword" placeholder="New password">
            </div>
            <div class="form-group">
                <label for="confirmNewRawPassword">Confirm new password</label>
                <input type="password" required class="form-control" id="confirmNewRawPassword" placeholder="Confirm new password">
            </div>
          <button type="submit" class="btn btn-primary">Change</button>
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