<?php
require_once '../core.php';
$TITLE = 'Admin: User';
$HEADER = "Admin: User";
$PATHS = [
    "Admin: User"
];
top_private();
?>

<div class="row">
    <div class="col-lg-12">
        <table id="user__list"></table>
        <ul id="context-menu" class="dropdown-menu">
            <li data-item="login"><a>Login</a></li>
        </ul>
    </div>
</div>

<script>

    var refreshAllVocab = ()=>{
        AppApi.sync.post("/admin/refresh-all-vocab").then((response)=>{
            FlashMessage.success(response.data.msg);
        });
    };
    $("#refresh-all-vocab").click(refreshAllVocab);

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
        contextMenu: '#context-menu',
        contextMenuButton: '.context-menu-button',
        contextMenuAutoClickRow: true,
        onContextMenuItem: function(row, $el) {
             if ($el.data("item") == "login") {
                Auth.forceLogin(row.id);
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
                field: 'fullName',
                title: 'Name',
                sortable: true,
            },
            {
                field: 'createdDate',
                title: 'Joined Date',
                sortable: true,
            },
            {
                width: 50,
                formatter: ()=>{
                    return '<button class="btn btn-sm context-menu-button pull-right"><span class="glyphicon glyphicon-menu-hamburger"></span></button>';
                }
            },
        ],
    });
</script>
<?=bottom_private()?>