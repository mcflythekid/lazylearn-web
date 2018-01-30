<?php
require_once 'core.php';
title('Deck...');
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
            <form id="deck__create--form">
                <div class="input-group" >
                    <input type="text" class="form-control" id="deck__create--name" required placeholder="Deck name...">
                    <span class="input-group-btn">
						<button class="btn btn-primary" type="submit">Create</button>
					</span>
                </div>
            </form>
        </div>
        <table id="data"></table>
    </div>
</div>
<script>
    (()=>{
        $('#deck__create--form').submit((event)=> {
            event.preventDefault();
            $app.apisync.post("/user/" + $tool.getData('auth').userId + "/deck", {
                name : $('#deck__create--name').val().trim()
            }).then(()=>{
                $('#deck__create--name').val('');
                $('#data').bootstrapTable('refresh',{
                    silent: true
                });
            });
        });
    })();

    $('#data').bootstrapTable({
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
                field: 'createdOn',
                title: 'Create',
                sortable: true,
            }
        ]

    });

</script>
<?=bottom()?>
