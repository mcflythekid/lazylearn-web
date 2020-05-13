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

<div class="row u-mt-40">
    <div class="col-lg-4 col-md-6 col-lg-offset-4 col-md-offset-3">
        <label for="changeEmail">Name</label>
        <div class="input-group">
            <input type="email" class="form-control" id="changeName" placeholder="Name">
            <span class="input-group-btn">
                <button type="button" class="btn btn-info btn-flat" id="changeNameSubmit">Update</button>
            </span>
        </div>
    </div>
</div>

<div class="row u-mt-40">
    <div class="col-lg-4 col-md-6 col-lg-offset-4 col-md-offset-3">
        <label for="changeEmail">Email</label>
        <div class="input-group">
            <input type="email" class="form-control" id="changeEmail" placeholder="Email">
            <span class="input-group-btn">
                <button type="button" class="btn btn-info btn-flat" id="changeEmailSubmit">Update</button>
            </span>
        </div>
    </div>
</div>

<div class="row u-mt-40">
    <div class="col-lg-4 col-md-6 col-lg-offset-4 col-md-offset-3">
        <label>Timezone</label>
        <div class="form-group input-group">
            <select class="form-control" id="changeTimezone">
                <option value="GMT+12">GMT+12</option>
                <option value="GMT+11">GMT+11</option>
                <option value="GMT+10">GMT+10</option>
                <option value="GMT+9">GMT+9</option>
                <option value="GMT+8">GMT+8</option>
                <option value="GMT+7">GMT+7</option>
                <option value="GMT+6">GMT+6</option>
                <option value="GMT+5">GMT+5</option>
                <option value="GMT+4">GMT+4</option>
                <option value="GMT+3">GMT+3</option>
                <option value="GMT+2">GMT+2</option>
                <option value="GMT+1">GMT+1</option>
                <option value="GMT">GMT</option>
                <option value="GMT-1">GMT-1</option>
                <option value="GMT-2">GMT-2</option>
                <option value="GMT-3">GMT-3</option>
                <option value="GMT-4">GMT-4</option>
                <option value="GMT-5">GMT-5</option>
                <option value="GMT-6">GMT-6</option>
                <option value="GMT-7">GMT-7</option>
                <option value="GMT-8">GMT-8</option>
                <option value="GMT-9">GMT-9</option>
                <option value="GMT-10">GMT-10</option>
                <option value="GMT-11">GMT-11</option>
                <option value="GMT-12">GMT-12</option>
            </select>
            <span class="input-group-btn">
                <button type="button" class="btn btn-info btn-flat" id="changeTimezoneSubmit">Update</button>
            </span>
        </div>
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
    AppApi.async.get("/get-setting").then(res=>{
        const data = res.data;
        console.log(data);

        $("input#changeEmail").val(data.email);
        $("input#changeName").val(data.name);
        document.getElementById('changeTimezone').value = data.timezone;
    });

    $("button#changeNameSubmit").click(function(){
        const newName = $("input#changeName").val();
        if (!newName){
            FlashMessage.error("Invalid name");
            return;
        }
        AppApi.sync.post("/change-name/", { name : newName }).then(res=>{
            FlashMessage.success(res.data.msg);
            setTimeout(()=>{
                location.reload();
            }, 500);
        });
    });

    $("button#changeEmailSubmit").click(function(){
        const newEmail = $("input#changeEmail").val();
        if (!newEmail){
            FlashMessage.error("Invalid email address");
            return;
        }
        AppApi.sync.post("/change-email/", { email : newEmail }).then(res=>{
            FlashMessage.success(res.data.msg);
            setTimeout(()=>{
                location.reload();
            }, 500);
        });
    });

    $("button#changeTimezoneSubmit").click(function(){
        const selector = document.getElementById("changeTimezone");
        const newTimezone = selector.options[ selector.selectedIndex ].value;
        AppApi.sync.post("/change-timezone/", { timezone : newTimezone }).then(res=>{
            FlashMessage.success(res.data.msg);
            setTimeout(()=>{
                location.reload();
            }, 500);
        });
    });

</script>
<?=bottom_private()?>