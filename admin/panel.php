<?php
require_once '../core.php';
$TITLE = 'Admin: Setting';
$HEADER = "Admin: Setting";
$PATHS = [
    "Admin: Setting"
];
top_private();
?>

<div class="row">
    <div class="col-lg-12">
        <button class="btn btn-info" id="refresh-all-vocab">Refresh Vocab</button>
    </div>
</div>

<div class="row u-mt-15">
    <div class="col-lg-12">
        <button class="btn btn-info" id="refresh-all-vocabdeck">Refresh Vocabdeck</button>
    </div>
</div>

<script>

    var refreshAllVocab = ()=>{
        AppApi.sync.post("/admin/refresh-all-vocab").then((response)=>{
            FlashMessage.success(response.data.msg);
        });
    };
    $("#refresh-all-vocab").click(refreshAllVocab);

    var refreshAllVocabdeck = ()=>{
        AppApi.sync.post("/admin/refresh-all-vocabdeck").then((response)=>{
            FlashMessage.success(response.data.msg);
        });
    };
    $("#refresh-all-vocabdeck").click(refreshAllVocabdeck);



</script>
<?=bottom_private()?>