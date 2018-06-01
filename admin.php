<?php
require_once 'core.php';
title('Admin');
$HEADER = "Admin";
$PATHS = [
    "Admin"
];
top_private();
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
        <div id="toolbar"></div>
        <table id="user__list"></table>
    </div>
</div>
<script>
    (()=>{

        var refresh = ()=>{
            $('#user__list').bootstrapTable('refresh',{
                silent: true
            });
        };

        $('#user__list').bootstrapTable({
            classes: 'table table-hover table-bordered table-condensed table-responsive bg-white',
            url: $app.endpoint + "/admin/user",
            cache: false,
            striped: false,
            toolbar: '#toolbar',
            sidePagination: 'server',
            sortName: 'email',
            pageSize: 20,
            pageList: [20, 50, 100],
            search: true,
            ajaxOptions: {
                headers: {
                    Authorization: 'Bearer ' + $tool.getData('auth').token
                }
            },
            pagination: true,
            columns: [
                {
                    field: 'id',
                    title: 'id',
                    sortable: true,
                },
                {
                    field: 'email',
                    title: 'email',
                    sortable: true,
                },
                {
                    field: 'decks',
                    title: 'decks',
                    sortable: true,
                },
                {
                    field: 'cards',
                    title: 'cards',
                    sortable: true,
                },
                {
                    field: 'registerIpAddress',
                    title: 'registerIpAddress',
                    sortable: true,
                },
                {
                    field: 'createdOn',
                    title: 'createdOn',
                    sortable: true,
                },
                {
                    field: 'updatedOn',
                    title: 'updatedOn',
                    sortable: true,
                }
            ],
        });

    })();
</script>
<?=bottom_private()?>