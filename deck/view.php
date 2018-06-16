<?php
require_once '../core.php';
$TITLE = ('loading...');
$HEADER = '<span id="headerName">loading..</span>';
$PATHS = [
    ["/deck", "Deck"],
    '<span id="breadcrumbName">loading..</span>'
];

top_private();
modal();
$deckId = '';
if (isset($_GET['id'])){
    $deckId = $_GET['id'];
}
?>

<div class="row">
    <div class="col-lg-12">
        <div class="lazychart" id="lazychart__deck"></div>
    </div>
</div>

<div class="row" id="cardcreate">
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
        <button type="submit" id="cardcreate__submit" class="btn btn-success pull-right">Create</button>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div id="cardcreate__toolbar">
            <div class="btn-group">
                    <button class="btn btn-primary cardlearn" data-learn-type="learn">Lean</button>
                    <button class="btn btn-primary cardlearn" data-learn-type="review">Review</button>
            </div>
        </div>
        <table id="cardlist"></table>
    </div>
</div>


<script>
    var deckId = '<?=$deckId?>';

    var deck;

    var backEditor = new Quill('#cardcreate__back', Editor.generateQuillConfig('Back side', ()=>{
        frontEditor.focus();
    }, ()=>{
        $('#cardcreate__submit').focus();
    }));

    var frontEditor = new Quill('#cardcreate__front', Editor.generateQuillConfig('Front side', ()=>{
    }, ()=>{
        backEditor.focus();
    }));

    var refresh = ()=>{
        $('#cardlist').bootstrapTable('refresh',{
            silent: true
        });
        //if (!deck.archived) AppChart.drawDeck(deckId, 'lazychart__deck');
        document.title = deck.name;
        $('#breadcrumbName').text(deck.name);
        $('#headerName').text(deck.name);
    };

    Deck.get(deckId, (deckResponse)=>{
        deck = deckResponse;
        refresh();
    });

    $('#cardcreate__submit').click(()=> {
        if (Editor.isBlank(frontEditor) || Editor.isBlank(backEditor)) {
            FlashMessage.warning("Please check your content");
            return;
        }
        Card.create(Editor.getHtml(frontEditor), Editor.getHtml(backEditor), deckId, ()=>{
            Editor.clear(frontEditor);
            Editor.clear(backEditor);
            frontEditor.focus();
            refresh();
        })
    });

    $('#cardlist').bootstrapTable({
        url: apiServer + "/card/search",
        classes: 'table bg-white',
        cache: false,
        method: 'post',
        striped: false,
        toolbar: '#cardcreate__toolbar',
        sidePagination: 'server',
        sortName: 'createdDate',
        sortOrder: 'desc',
        queryParams: (params)=>{
            params.deckId = deckId;
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
                field: 'front',
                title: 'Front',
                sortable: true,
                formatter: (obj)=>{
                    return obj;
                },
                width: '45%'
            },
            {
                field: 'back',
                title: 'Back',
                sortable: true,
                formatter: (obj)=>{
                    return obj;
                },
                width: '45%'
            },
            {
                align: 'center',
                field: 'id',
                formatter: (obj, row)=>{
                    return '<div class="btn-group">'+

                        '<button data-card-id="'+obj+'" class="btn btn-sm btn-success cardcmd__edit"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>'+
                        '<button data-card-id="'+obj+'" class="btn btn-sm btn-danger cardcmd__delete"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>'+
                        '</div>';
                },
                width: '10%'
            },
        ]

    });



/*

    $(document).on('click', '.cardcmd__delete', function(){
        var cardId = $(this).data('card-id');
        $tool.confirm("This will remove this card and cannot be undone!!!", function(){
            $app.apisync.delete("/card/" + cardId).then(()=>{
                refresh();
            });
        });
    });

    (()=>{
        var deckId = $tool.param('id');
        var enableChart = true;


        $(document).on('click', '.cardcmd__edit', function(e){
            var cardId = $(this).data('card-id');
            $card__modal__edit.edit(cardId, refresh);
        });
        $('.cardlearn',).click(function(){
            window.location.href = "./deck-learn.php?id=" + deckId + "&type=" + $(this).data('learn-type');
        });








    })();*/
</script>
<?=bottom_private()?>
