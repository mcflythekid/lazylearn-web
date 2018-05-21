<?php
require_once 'core.php';
title('Lazylearn');
top_public();
?>
<!-- Jumbotron -->
<div class="jumbotron">
    <h1>Forget about forgeting!</h1>
    <p class="lead">Cras justo odio, dapibus ac facilisis in, egestas eget quam. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet.</p>
    <p><a class="btn btn-lg btn-success" href="/register.php" role="button">Get started today</a></p>
</div>

<!-- Example row of columns -->
<div class="row">
    <div class="col-lg-4">
        <h2>Safari bug warning!</h2>
        <p class="text-danger">As of v9.1.2, Safari exhibits a bug in whig errors in the justified nav that are cleared upon refreshing.</p>
        <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus comllis euis odio dui. </p>
        <!--                <p><a class="btn btn-primary" href="#" role="button">View details &raquo;</a></p>-->
    </div>
    <div class="col-lg-4">
        <h2>Heading</h2>
        <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
        <!--                <p><a class="btn btn-primary" href="#" role="button">View details &raquo;</a></p>-->
    </div>
    <div class="col-lg-4">
        <h2>Heading</h2>
        <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa.</p>
        <!--                <p><a class="btn btn-primary" href="#" role="button">View details &raquo;</a></p>-->
    </div>
</div>

<?=bottom_public()?>

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
