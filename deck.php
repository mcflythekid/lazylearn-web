<?php
require_once 'core.php';
title('Loading...');
top();
?>

<style>
    #toolbar{
        width: 300px;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div id="toolbar">
            <form id="card__create--form">
                <div class="input-group" >
                    <input type="text" class="form-control" id="card__create--front" required placeholder="Front...">
                    <input type="text" class="form-control" id="card__create--back" required placeholder="Back...">
                    <span class="input-group-btn">
                    <button class="btn btn-primary" type="submit">Create</button>
                </span>
                </div>
            </form>
        </div>
        <table id="card__list"></table>
    </div>
</div>

<script>



    (()=>{

        var deckId = $tool.param('id');
        var userId = $tool.getData('auth').userId;


        var refresh = ()=>{
            $('#card__list').bootstrapTable('refresh',{
                silent: true
            });
        };

        $app.api.get("/user/" + userId + "/deck/" + deckId).then((r)=>{
            var deck = r.data;
            document.title = deck.name;
        }).catch((e)=>{
            $tool.flash(0, 'Cannot get deck');
        });

        $('#card__create--form').submit((event)=> {
            event.preventDefault();
            $app.apisync.post("/user/" + userId + "/deck/" + deckId + "/card", {
                front : $('#card__create--front').val().trim(),
                back : $('#card__create--back').val().trim()
            }).then(()=>{
                $('#card__create--front').val('');
                $('#card__create--back').val('');
                refresh();
            });
        });

        $('#card__list').bootstrapTable({
            url: $app.endpoint + "/user/" + userId + "/deck/" + deckId + "/card",
            cache: false,
            striped: true,
            toolbar: '#toolbar',
            sidePagination: 'server',
            sortName: 'id',
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
                    }
                },
                {
                    field: 'back',
                    title: 'Back',
                    sortable: true,
                    formatter: (obj)=>{
                        return obj;
                    }
                },
                {
                    width: 150,
                    align: 'center',
                    field: 'id',
                    formatter: (obj, row)=>{
                        return '<div class="btn-group">'+
                            '<button data-deck-id="'+obj+'" data-deck-name="'+row.name+'" data-toggle="modal" data-target="#deck__modal__edit" class="btn btn-sm btn-success deck__cmd--edit">Edit</button>'+
                            '<button data-deck-id="'+obj+'" class="btn btn-sm btn-danger deck__cmd--delete">Delete</button>'+
                            '</div>';
                    }
                },
            ]

        });

    })();
</script>
<?=bottom()?>