<?php
function asset(){
    global $ASSET;
    global $TITLE;
    global $API_SERVER;
?>

    <title><?=escape($TITLE)?></title>
    <link href="<?=$ASSET?>/img/fav.png" id ="shorticon" rel="shortcut icon">

    <script src="/node_modules/jquery/dist/jquery.min.js"></script>

    <script src="/node_modules/axios/dist/axios.min.js"></script>

    <script src="/external/Holdon/HoldOn.min.js"></script>
    <link href="/external/Holdon/HoldOn.min.css" rel="stylesheet">

    <script src="/node_modules/jstorage/jstorage.min.js"></script>

    <script src="/node_modules/quill/dist/quill.min.js"></script>
    <link href="/node_modules/quill/dist/quill.snow.css" rel="stylesheet">
    <script src="/node_modules/quill-image-resize-module/image-resize.min.js"></script>
    <script src="/node_modules/quill-magic-url/dist/index.js"></script>

    <script src="/node_modules/bootstrap-table/dist/bootstrap-table.min.js"></script>
    <link href="/node_modules/bootstrap-table/dist/bootstrap-table.min.css" rel="stylesheet">
    <script src="/external/bootstrap-table-contextmenu.min.js"></script>

    <script src="/node_modules/bootstrap3/dist/js/bootstrap.min.js"></script>
    <link href="/node_modules/bootstrap3/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="/node_modules/bootstrap3-dialog/dist/js/bootstrap-dialog.min.js"></script>
    <link href="/node_modules/bootstrap3-dialog/dist/css/bootstrap-dialog.min.css" rel="stylesheet">

    <link href="/node_modules/css-utility-classes/dist/utils.css" rel="stylesheet">

    <script src="/external/flashjs/dist/flash.min.js"></script>
    <link href="/external/flashjs/dist/flash.min.css" rel="stylesheet">

    <link href="/node_modules/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <script src="//www.gstatic.com/charts/loader.js"></script>

    <script>
        var apiServer = '<?=$API_SERVER?>';
    </script>

    <link href="<?=$ASSET?>/app.css" rel="stylesheet">

    <script src="<?=$ASSET?>/Constant.js"></script>
    <script src="<?=$ASSET?>/Storage.js"></script>
    <script src="<?=$ASSET?>/AppApi.js"></script>
    <script src="<?=$ASSET?>/Auth.js"></script>
    <script src="<?=$ASSET?>/Dialog.js"></script>
    <script src="<?=$ASSET?>/Editor.js"></script>

    <script src="<?=$ASSET?>/Application.js"></script>
    <script src="<?=$ASSET?>/Chart.js"></script>
    <script src="<?=$ASSET?>/Deck.js"></script>
    <script src="<?=$ASSET?>/Card.js"></script>

<?php } ?>