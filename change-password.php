<?php
	require 'core.php';
	title('Change password');
	top_private();
?>
<div class="row">
	<div class="col-lg-3"></div>
	<div class="col-lg-6">
		<form id="change">
            <div class="form-group">
                <label for="newPassword">New password</label>
                <input type="password" required class="form-control" id="newPassword" placeholder="New password">
            </div>
            <div class="form-group">
                <label for="newPassword2">Confirm new password</label>
                <input type="password" required class="form-control" id="newPassword2" placeholder="Confirm new password">
            </div>
		  <button type="submit" class="btn btn-primary">Change</button>
		</form>
	</div>
</div>
<script>
    (()=>{
        $("#change").submit((e)=>{
            e.preventDefault();
            if ($('#newPassword').val() !== $('#newPassword2').val()){
                $tool.flash(0, 'New password does not matched');
                return;
            }
            $app.apisync.put("/user/" + $tool.getData('auth').userId + "/password", {
                newPassword: $('#newPassword').val()
            }).then((r)=>{
                $('#newPassword').val('');
                $('#newPassword').focus();
                $('#newPassword2').val('');
                $tool.flash(1, 'Password changed');
            });
        });
    })();
</script>
<?=bottom_private()?>