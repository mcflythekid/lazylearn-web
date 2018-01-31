<?php
require_once 'core.php';
title('Loading...');
top();
require 'modal/card-edit.php';
?>

<style>
    #toolbar{
        width: 300px;
    }
</style>

    <style>
        .lzcard_side {
            padding-left: 10px;
            padding-right: 10px;
            background-color: #fff;
            border-bottom: 1px solid #cecece;
            border-left: 1px solid #d8d8d8;
            border-right: 1px solid #cecece;
            border-top: 1px solid #d8d8d8;
            min-height: 6em;
        }
        #card__list img{
            max-width:100%;
            max-height:100%;
        }
        .lzcard{
            margin-bottom:10px;
        }
        #lzcard_newform{
            margin-bottom:30px;
        }
        #lzcard_sample{
            display: none;
        }
    </style>

    <div class="row" id="lzcard_newform">
        <div class="col-lg-11">
            <div class="row">
                <div class="col-xs-6">
                    <div id="card__create--front"></div>
                </div>
                <div class="col-xs-6">
                    <div id="card__create--back"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-1">
            <button type="submit" id="card__create--submit" class="btn btn-success pull-right">
                Create
            </button>
        </div>
    </div>

<div class="row">
    <div class="col-lg-12">
        <div id="toolbar"></div>
        <table id="card__list"></table>
    </div>
</div>

<script>
    (()=>{

        var quill_modules = {
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    ['image', 'code-block']
                ]
            },
            placeholder: '',
            theme: 'snow'  // or 'bubble'
        };
        var new_front = new Quill('#card__create--front', {
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    ['image', 'code-block']
                ]
            },
            placeholder: 'Front side...',
            theme: 'snow'  // or 'bubble'
        });
        var new_back = new Quill('#card__create--back', {
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    ['image', 'code-block']
                ]
            },
            placeholder: 'Back side...',
            theme: 'snow'  // or 'bubble'
        });

        $('#card__create--submit').click((event)=> {
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


        var deckId = $tool.param('id');

        var refresh = ()=>{
            $('#card__list').bootstrapTable('refresh',{
                silent: true
            });
        };

        $app.api.get("/deck/" + deckId).then((r)=>{
            var deck = r.data;
            document.title = deck.name;
        }).catch((e)=>{
            $tool.flash(0, 'Cannot get deck');
        });

        $(document).on('click', '.card__cmd--delete', function(e){
            var cardId = $(this).data('card-id');
            $tool.confirm("This will remove this card and cannot be undone!!!", function(){
                $app.apisync.delete("/card/" + cardId).then(()=>{
                    refresh();
                });
            });
        });

        $(document).on('click', '.card__cmd--edit', function(e){
            var cardId = $(this).data('card-id');
            $card__modal__edit.edit(cardId, refresh);
        });

        $('#card__list').bootstrapTable({
            url: $app.endpoint + "/deck/" + deckId + "/card/by-search",
            classes: 'table',
            cache: false,
            striped: false,
            toolbar: '#toolbar',
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

                            '<button data-card-id="'+obj+'" class="btn btn-sm btn-success card__cmd--edit"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>'+
                            '<button data-card-id="'+obj+'" class="btn btn-sm btn-danger card__cmd--delete"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>'+
                            '</div>';
                    },
                    width: '10%'
                },
            ]

        });

    })();
</script>
<?=bottom()?>