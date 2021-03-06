<?php
require_once '../core.php';
require_once '../lang/core.php';
$TITLE = $lang["common.loading"];
$HEADER = '<span id="appHeader">' . $lang["common.loading"] . '</span>';
$PATHS = [
    '<span id="appBreadcrumb1">' . $lang["common.loading"] . '</span>'
];

top_private();

Vocab();
Deck();
Card();
$deckId = ''; if (isset($_GET['id'])) $deckId = escape($_GET['id']);
?>

<div class="row" id="lazychart__deck--wrapper">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-target="#collapseOne" href="#" class="a-no-underline display-block">
                        <?= $lang["page.deckview.chart01.text"] ?>
                    </a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in">
                <div class="panel-body">
                    <div class="lazychart" id="lazychart__deck"></div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="row deck-control" id="cardcreate">
    <div class="col-lg-11 u-mb-10">
        <div class="row">
            <div class="col-xs-6">
                <div id="cardcreate__front"></div>
            </div>
            <div class="col-xs-6">
                <div id="cardcreate__back"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-1">
        <button type="submit" id="cardcreate__submit" class="btn btn-info btn-flat pull-right">
            <?= $lang["page.deckview.btn.create"] ?>
        </button>
    </div>
</div>

<div class="row">

    <div class="col-lg-12 deck-control">
        <div class="btn-group">
            <button class="btn btn-flat btn-info deck-action" data-action="learn"><?= $lang["common.learn"] ?></button>
            <button class="btn btn-flat btn-info deck-action" data-action="review"><?= $lang["common.review"] ?></button>
        </div>
        <div class="btn-group">
            <button class="btn btn-flat btn-info deck-action" data-action="parent" style="display: none;"><?= $lang["page.deckview.btn.parent"] ?></button>
        </div>
        <div class="btn-group">
            <button class="btn btn-flat btn-info deck-action" data-action="edit"><?= $lang["common.rename"] ?></button>
            <button class="btn btn-flat btn-info deck-action" data-action="archive"><?= $lang["common.archive"] ?></button>
            <button class="btn btn-flat btn-info deck-action" data-action="unarchive"><?= $lang["common.unarchive"] ?></button>
        </div>
        <div class="btn-group">
            <button class="btn btn-flat btn-danger deck-action" data-action="delete"><?= $lang["common.delete"] ?></button>
        </div>
    </div>

    <div class="col-lg-12">
        <table id="cardlist"></table>
    </div>
</div>


<script>
    var deckId = '<?=$deckId?>';
    var deck;
    var backEditor = new Quill(
        '#cardcreate__back',
        Editor.generateQuillConfig(
            'Back side',
            ()=>{
                frontEditor.focus();
            },
            ()=>{
                $('#cardcreate__submit').focus();
            }
        )
    );
    var frontEditor = new Quill(
        '#cardcreate__front',
        Editor.generateQuillConfig(
            'Front side',
            ()=>{},
            ()=>{
                backEditor.focus();
            }
        )
    );
    var refresh = ()=>{
        $('#cardlist').bootstrapTable('refresh',{
            silent: true
        });
        Deck.get(deckId, (deckResponseObject)=>{
            deck = deckResponseObject;

            if (deck.type == "topic"){
                $('.deck-control').hide();
            }

            if (!deck.archived) {
                $('.deck-action[data-action="archive"]').show();
                $('.deck-action[data-action="unarchive"]').hide();
                $('.deck-action[data-action="learn"]').show();
                $('.deck-action[data-action="review"]').show();
                $('#appHeader').text(deck.name);
                AppChart.drawDeck(deckId, 'lazychart__deck');
            } else {
                $('#lazychart__deck--wrapper').remove();
                $('.deck-action[data-action="archive"]').hide();
                $('.deck-action[data-action="unarchive"]').show();
                $('.deck-action[data-action="learn"]').hide();
                $('.deck-action[data-action="review"]').hide();
                $('#appHeader').html(deck.name + ' <span class="archived u-pb-5">Archived</span>');
            }
            document.title = deck.name;
            $('#appBreadcrumb1').text(deck.name);

            if (deck.vocabdeckId){
                $('.deck-action[data-action="archive"]').hide();
                $('.deck-action[data-action="unarchive"]').hide();
                $('.deck-action[data-action="edit"]').hide();
                $('.deck-action[data-action="delete"]').hide();
                $('#cardcreate').hide();
                $('.deck-action[data-action="parent"]').show();
            }
        });

    };

    $(document).on('click', '.deck-action', function(){
        var action = $(this).data('action');
        switch(action) {
            case 'delete':
                Deck.delete(deckId, ()=>{
                    window.location = Constant.deckUrl;
                });
                break;
            case 'archive':
                Deck.archive(deckId, ()=>{
                    window.stop();
                    location.reload();
                });
                break;
            case 'unarchive':
                Deck.unarchive(deckId, ()=>{
                    window.stop();
                    location.reload();
                });
                break;
            case 'edit':
                Deck.openEdit(deckId, deck.name, refresh);
                break;
            case 'learn':
                window.location = '/deck/learn.php?type=learn&id=' + deckId;
                break;
            case 'review':
                window.location = '/deck/learn.php?type=review&id=' + deckId;
                break;
            case 'parent':
                window.location = '/vocabulary/view.php?&id=' + deck.vocabdeckId;
                break;
            default:
        }
    });

    $(document).on('click', '.cardcmd__delete', function(){
        if (deck.vocabdeckId){
            Vocab.delete($(this).data('vocabid'), refresh);
        } else {
            Card.delete($(this).data('cardid'), refresh);
        }
    });

    $(document).on('click', '.cardcmd__edit', function(e){
        if (deck.vocabdeckId){
            Vocab.openEdit($(this).data('vocabid'), refresh);
        } else {
            Card.openEdit($(this).data('cardid'), refresh);
        }
    });

    $('#cardcreate__submit').click(()=> {
        Card.create(deckId, frontEditor, backEditor, refresh);
    });

    $('#cardlist').bootstrapTable({
        url: apiServer + "/card/search",
        classes: 'table bg-white',
        cache: false,
        method: 'post',
        striped: false,
        formatSearch: ()=> { return '<?= $lang["page.deckview.input.search.holder"] ?>' },
        sidePagination: 'server',
        sortName: 'createdDate',
        sortOrder: 'desc',
        queryParams: (params)=>{
            params.deckId = deckId;
            return params;
        },
        showHeader: true,
        search: true,
        ajaxOptions: {
            headers: {
                Authorization: AppApi.getAuthorization()
            }
        },
        pagination: true,
        columns: [
            {
                field: 'front',
                title: '<?= $lang["page.deckview.column.front"] ?>',
                sortable: true,
                formatter: (obj)=>{
                    return obj;
                },
                width: '40%'
            },
            {
                field: 'back',
                title: '<?= $lang["page.deckview.column.back"] ?>',
                sortable: true,
                formatter: (obj)=>{
                    return obj;
                },
                width: '40%'
            },
            {
                field: 'sm2Ef',
                title: '<?= $lang["page.deckview.column.easy_score"] ?>',
                sortable: true
            },
            {
                field: 'step',
                title: '<?= $lang["page.deckview.column.deck_id"] ?>',
                sortable: true,
                formatter: function(data){
                    return data + 1;
                },
            },
            {
                align: 'center',
                formatter: (obj, row)=>{
                    return '<div class="btn-group">'+
                        '<button data-vocabid="'+row.vocabId+'" data-cardid="'+row.id+'" class="btn btn-flat btn-sm btn-info cardcmd__edit"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>'+
                        '<button data-vocabid="'+row.vocabId+'" data-cardid="'+row.id+'" class="btn btn-flat btn-sm btn-danger cardcmd__delete"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>'+
                    '</div>';
                },
                width: '12%'
            },
        ]

    });

    refresh();
</script>
<?=bottom_private()?>
