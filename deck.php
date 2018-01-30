<?php
require_once 'core.php';
title('Loading...');
top();
?>

<script>
    $app.api.get("/user/" + $tool.getData('auth').userId + "/deck/" + $tool.param('id')).then((r)=>{
        var deck = r.data;
        document.title = deck.name;
    }).catch((e)=>{
        $tool.flash(0, 'Cannot get deck');
    });
</script>
<?=bottom()?>