<?php
require_once 'core.php';
title('Deck test11');
top();
require 'modal/deck-edit.php';
require 'component/chart.php';
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
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-target="#collapseOne" href="#" class="a-no-underline display-block">
                        Statistics Chart
                    </a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in">
                <div class="panel-body">
                    <div class="lazychart" id="lazychart__user"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
	<div class="col-lg-6">
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
        classes: 'table table-hover table-bordered table-condensed table-responsive bg-white',
        url: $app.endpoint + "/user/" + $tool.getData('auth').userId + "/deck/by-search",
        cache: false,
        striped: false,
        toolbar: '#toolbar',
        sidePagination: 'server',
        sortName: 'name',
        pageSize: 20,
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
<?=bottom()?>
