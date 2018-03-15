<?php
require_once 'core.php';
title('Deck');
top();
require 'modal/deck-edit.php';
require 'component/chart.php';
?>
<style>
#toolbar{
	width: 300px;
}
</style>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">Stats</div>
            <div class="panel-body">
                <div class="lazychart" id="lazychart__user"></div>
            </div>
        </div>
    </div>
</div>

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

    var drawChart = ()=>{
        chart.drawUser($tool.getData('auth').userId, 'lazychart__user');
    };
    drawChart();

    var refresh = ()=>{
        $('#deck__list').bootstrapTable('refresh',{
            silent: true
        });
        drawChart();
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

    $('#deck__list').bootstrapTable({
        url: $app.endpoint + "/user/" + $tool.getData('auth').userId + "/deck/by-search",
        cache: false,
        striped: true,
        toolbar: '#toolbar',
        sidePagination: 'server',
        sortName: 'name',
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
                title: 'Name',
                sortable: true,
                formatter: (obj,row)=>{
                    return '<a href="'+ctx+'/deck.php?id='+row.id+'">'+obj+'</a>';
                }
            },
            {
                field: 'totalCard',
                title: 'Cards',
                sortable: true,
            },
            {
                field: 'totalTimeupCard',
                title: 'Timeups',
                sortable: true,
            },
            {
                width: 150,
                align: 'center',
                field: 'id',
                formatter: (obj, row)=>{
                    return '<div class="btn-group">'+
                        '<button data-deck-id="'+obj+'" data-deck-name="'+row.name+'" data-toggle="modal" data-target="#deck__modal__edit" class="btn btn-sm btn-success">Edit</button>'+
                        '<button data-deck-id="'+obj+'" class="btn btn-sm btn-danger deck__cmd--delete">Delete</button>'+
                        '</div>';
                }
            },
        ]

    });

})();
</script>
<?=bottom()?>
