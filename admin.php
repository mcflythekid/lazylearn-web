<?php
require_once 'core.php';
$TITLE = 'Admin';
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
</style>


<div class="row">
    <div class="col-lg-12">
        <div id="toolbar"></div>
        <table id="user__list"></table>
    </div>
</div>
<script>

    var refresh = ()=>{
        $('#user__list').bootstrapTable('refresh',{
            silent: true
        });
    };

    $('#user__list').bootstrapTable({
        classes: 'table table-hover table-bordered table-condensed table-responsive bg-white',
        url: apiServer + "/admin/search-user",
        cache: false,
        striped: false,
        sidePagination: 'server',
        sortName: 'createdDate',
        method: 'post',
        sortOrder: 'desc',
        pageSize: 100,
        pageList: [100, 200],
        search: true,
        ajaxOptions: {
            headers: {
                Authorization: AppApi.getAuthorization()
            }
        },
        pagination: true,
        columns: [

            {
                field: 'email',
                title: 'Email address',
                sortable: true,
            },
            {
                field: 'facebookId',
                title: 'Facebook ID',
                sortable: true,
            },
            {
                field: 'fullName',
                title: 'Full name',
                sortable: true,
            },
            {
                field: 'decks',
                title: 'Deck',
                sortable: true,
            },
            {
                field: 'cards',
                title: 'Card',
                sortable: true,
            },
            {
                field: 'ipAddress',
                title: 'IP Address',
                sortable: true,
            },
            {
                field: 'createdDate',
                title: 'Joined Date',
                sortable: true,
            },
            {
                field: 'updatedDate',
                title: 'Updated Date',
                sortable: true,
            }
        ],
    });
</script>
<?=bottom_private()?>