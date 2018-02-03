<?php
require_once 'core.php';
title('loading...');
top();
require 'modal/card-edit.php';
require 'component/chart.php';
?>

<div class="row">
    <div class="col-lg-12">
        <div id="deckchart"></div>
    </div>
</div>

<div class="row" id="cardcreate">
    <div class="col-lg-11">
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
    (()=>{
        var deckId = $tool.param('id');

        $(document).on('click', '.cardcmd__delete', function(){
            var cardId = $(this).data('card-id');
            $tool.confirm("This will remove this card and cannot be undone!!!", function(){
                $app.apisync.delete("/card/" + cardId).then(()=>{
                    refresh();
                });
            });
        });
        $(document).on('click', '.cardcmd__edit', function(e){
            var cardId = $(this).data('card-id');
            $card__modal__edit.edit(cardId, refresh);
        });
        $('.cardlearn',).click(function(){
            window.location.href = "./learn.php?id=" + deckId + "&type=" + $(this).data('learn-type');
        });
        $('#cardcreate__submit').click(()=> {
            if ($tool.quillIsBlank(new_front) || $tool.quillIsBlank(new_back)){
                return;
            }
            $app.apisync.post("/deck/" + deckId + "/card", {
                front : $tool.quillGetHtml(new_front),
                back : $tool.quillGetHtml(new_back)
            }).then(()=>{
                $tool.quillClear(new_front);
                $tool.quillClear(new_back);
                refresh();
            });
        });

        var new_front = new Quill('#cardcreate__front', {
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    ['image', 'code-block']
                ]
            },
            placeholder: 'Front side...',
            theme: 'snow'  // or 'bubble'
        });
        var new_back = new Quill('#cardcreate__back', {
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    ['image', 'code-block']
                ]
            },
            placeholder: 'Back side...',
            theme: 'snow'  // or 'bubble'
        });
        var refresh = ()=>{
            $('#cardlist').bootstrapTable('refresh',{
                silent: true
            });
            chart.drawDeck(deckId, 'deckchart')
        };

        chart.drawDeck(deckId, 'deckchart');
        $app.api.get("/deck/" + deckId).then((r)=>{
            var deck = r.data;
            document.title = deck.name;
        }).catch((e)=>{
            $tool.flash(0, 'Cannot get deck');
        });
        $('#cardlist').bootstrapTable({
            url: $app.endpoint + "/deck/" + deckId + "/card/by-search",
            classes: 'table',
            cache: false,
            striped: false,
            toolbar: '#cardcreate__toolbar',
            sidePagination: 'server',
            sortName: 'createdOn',
            sortOrder: 'DESC',
            showHeader: false,
            search: true,
            ajaxOptions: {
                headers: {
                    Authorization: 'Bearer ' + $tool.getData('auth').token
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

    })();
</script>
<?=bottom()?>