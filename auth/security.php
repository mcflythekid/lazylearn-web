<?php
require_once '../core.php';
$TITLE = 'Security';
$HEADER = 'Security';
$PATHS = [
    "Security"
];
top_private();
?>

<div class=" u-mt-20">
    <h4>Where You're Logged In</h4>
    <p id="sessions">Loading...</p>
    <button class="btn btn-success" id="logout-all-session-btn">Log Out Of All Sessions</button>
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