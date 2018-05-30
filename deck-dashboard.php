<?php
require_once 'core.php';
title('Deck');
top_private();
require 'modal/deck-edit.php';
?>
<style>
    #toolbar {
        width: 300px;
    }
    .panel-heading a:after {
        font-family:'Glyphicons Halflings';
        content:"\e114";
        float: right;
        color: grey;
    }
    .panel-heading a.collapsed:after {
        content:"\e080";
    }
</style>


<div class="row">
    <div class="col-lg-12">
        <div id="toolbar">
            <form id="deck__create--form">
                <div class="input-group" >
                    <input type="text" class="form-control" id="deck__create--name" required placeholder="Deck name...">
                    <span class="input-group-btn">
						<button class="btn btn-primary" type="submit">Create</button>
					</span>
                </div>
            </form>
        </div>
        <table id="deck__list"></table>
    </div>
</div>
<script>
    (()=>{

        var refresh = ()=>{
            $('#deck__list').bootstrapTable('refresh',{
                silent: true
            });
        };

        $('#deck__create--form').submit((event)=> {
            event.preventDefault();
            $app.apisync.post("/user/" + $tool.getData('auth').userId + "/deck", {
                name : $('#deck__create--name').val().trim()
            }).then(()=>{
                $('#deck__create--name').val('');
                refresh();
            });
        });

        $(document).on('click', '.deck__cmd--delete', function(e){
            var deckId = $(this).data('deck-id');
            $tool.confirm("This will remove this deck and cannot be undone!!!", function(){
                $app.apisync.delete("/deck/" + deckId).then(()=>{
                    refresh();
                });
            });
        });

        $(document).on('click', '.deck__cmd--archive', function(e){
            var deckId = $(this).data('deck-id');
            $app.apisync.post("/deck/archive/" + deckId).then(()=>{
                refresh();
            });
        });

        $(document).on('click', '.deck__cmd--unarchive', function(e){
            var deckId = $(this).data('deck-id');
            $app.apisync.post("/deck/unarchive/" + deckId).then(()=>{
                refresh();
            });
        });

        $('#deck__list').bootstrapTable({
            classes: 'table table-hover table-bordered table-condensed table-responsive bg-white',
            url: $app.endpoint + "/user/" + $tool.getData('auth').userId + "/deck/by-search",
            cache: false,
            striped: false,
            toolbar: '#toolbar',
            sidePagination: 'server',
            sortName: 'name',
            pageSize: 5,
            search: true,
            ajaxOptions: {
                headers: {
                    Authorization: 'Bearer ' + $tool.getData('auth').token
                }
            },
            pagination: true,
            columns: [
                {
                    field: 'name',
                    title: 'Deck',
                    sortable: true,
                    formatter: (obj,row)=>{
                        return '<a href="'+ctx+'/deck.php?id='+row.id+'">'+obj+'</a>';
                    }
                },
                {
                    field: 'archived',
                    title: 'Status',
                    sortable: true,
                    formatter: (obj,row)=>{
                        return obj == 1 ? "Archived" : "Active";
                    }
                },
                {
                    width: 100,
                    field: 'totalCard',
                    title: 'Cards',
                    sortable: true,
                },
                /*{
                    field: 'totalTimeupCard',
                    title: 'Timeups',
                    sortable: true,
                },*/
                {
                    width: '190px',
                    align: 'center',
                    field: 'id',
                    formatter: (obj, row)=>{
                        return '<div class="btn-group">'+
                            '<button data-deck-id="'+obj+'" class="btn btn-sm btn-info deck__cmd--archive">Archive</button>'+
                            '<button data-deck-id="'+obj+'" class="btn btn-sm btn-info deck__cmd--unarchive">Active</button>'+
                            '<button data-deck-id="'+obj+'" data-deck-name="'+row.name+'" data-toggle="modal" data-target="#deck__modal__edit" class="btn btn-sm btn-info">Learn</button>'+
                            '<button data-deck-id="'+obj+'" data-deck-name="'+row.name+'" data-toggle="modal" data-target="#deck__modal__edit" class="btn btn-sm btn-success">Rename</button>'+
                            '<button data-deck-id="'+obj+'" class="btn btn-sm btn-danger deck__cmd--delete">Delete</button>'+
                            '</div>';
                    }
                },
            ]

        });

    })();
</script>
<?=bottom_private()?>
