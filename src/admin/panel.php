<?php
require_once '../core.php';
$TITLE = 'Advanced Settings';
$HEADER = "Advanced Settings";
$PATHS = [
    "Advanced Settings"
];
top_private();
?>

<div class="u-center">
    <div class="col-lg-3">
        <button class="btn btn-info btn-flat" id="refresh-all-vocab">Refresh Vocab</button>
    </div>
    <div class="col-lg-3">
        <button class="btn btn-info btn-flat" id="refresh-all-vocabdeck">Refresh Vocabdeck</button>
    </div>
    <div class="col-lg-3">
        <button class="btn btn-info btn-flat" id="refresh-all-minpair">Refresh Minpair</button>
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

    var refreshAllMinpair = ()=>{
        AppApi.sync.post("/admin/refresh-all-minpair").then((response)=>{
            FlashMessage.success(response.data.msg);
        });
    };
    $("#refresh-all-minpair").click(refreshAllMinpair);

</script>
<?=bottom_private()?>