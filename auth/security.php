<?php
require_once '../core.php';
require_once '../lang/core.php';
$TITLE = $lang["page.security.title"];
$HEADER = $lang["page.security.header"];
$PATHS = [
    $lang["page.security.header"]
];
top_private();
?>

<div class=" u-mt-20">
    <h4><?= $lang["page.security.label.sessions"] ?></h4>
    
    <button class="btn btn-info btn-flat" id="logout-all-session-btn"><?= $lang["page.security.btn.force_logout"] ?></button>

    <p id="sessions"><?= $lang["common.loading"] ?></p>
</div>

<script>
    Auth.getAllSession((sessions)=>{
        $('#sessions').html(sessions);
    })
    $('#logout-all-session-btn').click(()=>{
        Auth.logoutAllSession();
    });
</script>

<?=bottom_private()?>