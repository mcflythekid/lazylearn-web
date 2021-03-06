<?php
require_once '../core.php';
require_once '../lang/core.php';
$TITLE = $lang["common.loading"];
$HEADER = '<span id="appHeader">' . $lang["common.loading"] . '</span>';
$PATHS = [
    ["/vocabdeck", $lang["page.vocabdeck.header"]],
    '<span id="appBreadcrumb1">' . $lang["common.loading"] . '</span>'
];
top_private();
Vocab();
Vocabdeck();
$vocabdeckId = ''; if (isset($_GET['id'])) $vocabdeckId = escape($_GET['id']);
?>

<div class="row">
    <div class="col-lg-12">
        <div class="btn-group">
            <button class="btn btn-info btn-flat" id="vocabdeck-btn-create"><?= $lang["page.vocabdeck_view.btn.create"] ?></button>
        </div>
        <div class="pull-right"><div class="btn-group">
                <button class="btn btn-info btn-flat" id="vocabdeck-btn-rename"><?= $lang["common.rename"] ?></button>
                <button class="btn btn-info btn-flat" id="vocabdeck-btn-archive"><?= $lang["common.archive"] ?></button>
                <button class="btn btn-info btn-flat" id="vocabdeck-btn-unarchive"><?= $lang["common.unarchive"] ?></button>
            </div>
            <div class="btn-group">
                <button class="btn btn-danger btn-flat" id="vocabdeck-btn-delete"><?= $lang["common.delete"] ?></button>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <table id="vocab-list"></table>
    </div>
</div>

<script>
    var vocabdeckId = '<?=$vocabdeckId?>';
    var vocabdeckObject;

    var refresh = ()=>{
        $('#vocab-list').bootstrapTable('refresh',{
            silent: true
        });
        Vocabdeck.get(vocabdeckId, (remoteVocabdeckObject)=>{
            vocabdeckObject = remoteVocabdeckObject;
            document.title = remoteVocabdeckObject.name;
            $('#appBreadcrumb1').text(remoteVocabdeckObject.name);
            if (vocabdeckObject.archived) {
                $('#vocabdeck-btn-archive').hide();
                $('#vocabdeck-btn-unarchive').show();
                $('#appHeader').html(remoteVocabdeckObject.name + ' <span class="archived u-pb-5">Archived</span>');
            } else {
                $('#vocabdeck-btn-archive').show();
                $('#vocabdeck-btn-unarchive').hide();
                $('#appHeader').text(remoteVocabdeckObject.name);
            }
        });
    };

    $(document).ready(refresh);
    $('#vocabdeck-btn-rename').click(()=>{
        Vocabdeck.openRename(vocabdeckId, refresh);
    });
    $('#vocabdeck-btn-archive').click(()=>{
        Vocabdeck.archive(vocabdeckId, refresh);
    });
    $('#vocabdeck-btn-unarchive').click(()=>{
        Vocabdeck.unarchive(vocabdeckId, refresh);
    });
    $('#vocabdeck-btn-create').click(()=>{
        Vocab.openCreate(vocabdeckId, refresh);
    });
    $('#vocabdeck-btn-delete').click(()=>{
        Vocabdeck.delete(vocabdeckId, ()=>{
            window.location = Constant.vocabularyUrl;
        });
    });
    $(document).on('click', '.vocab-btn-delete', function() {
        Vocab.delete($(this).data('vocabid'), refresh);
    });
    $(document).on('click', '.vocab-btn-edit', function() {
        Vocab.openEdit($(this).data('vocabid'), refresh);
    });

    $('#vocab-list').bootstrapTable({
        url: apiServer + "/vocab/search",
        classes: 'table bg-white',
        cache: false,
        method: 'post',
        striped: false,
        sidePagination: 'server',
        sortName: 'createdDate',
        sortOrder: 'desc',
        formatSearch: ()=> { return '<?= $lang["page.vocabdeck_view.input.search.holder"] ?>' },
        queryParams: (params)=>{
            params.vocabdeckId = vocabdeckId;
            return params;
        },
        showHeader: false,
        search: true,
        ajaxOptions: {
            headers: {
                Authorization: AppApi.getAuthorization()
            }
        },
        pagination: true,
        columns: [
            {
                field: 'word',
                title: 'Word',
                sortable: true,
                width: '44%'
            },
            {
                field: 'phonetic',
                title: 'Phonetic',
                sortable: true,
                width: '44%'
            },
            {
                align: 'center',
                field: 'id',
                formatter: (obj, row)=>{
                    return '<div class="btn-group">'+
                        '<button data-vocabid="'+obj+'" class="btn btn-sm btn-success vocab-btn-edit"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>'+
                        '<button data-vocabid="'+obj+'" class="btn btn-sm btn-danger vocab-btn-delete"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>'+
                        '</div>';
                },
                width: '12%'
            },
        ]

    });



    /*$(document).on('click', '.deck-action', function(){
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
            default:
        }
    });

    $(document).on('click', '.cardcmd__delete', function(){
        Card.delete($(this).data('card-id'), ()=>{
            refresh();
        })
    });

    $('#cardcreate__submit').click(()=> {
        Card.create(deckId, ()=>{
            refresh();
        })
    });

    $(document).on('click', '.cardcmd__delete', function(){
        Card.delete($(this).data('card-id'), ()=>{
            refresh();
        })
    });

    $(document).on('click', '.cardcmd__edit', function(e){
        Card.openEdit($(this).data('card-id'), refresh);
    });*/




</script>
<?=bottom_private()?>
