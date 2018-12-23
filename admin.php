<?php
require_once 'core.php';
$TITLE = 'User';
$HEADER = "User";
$PATHS = [
    "User"
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
        <a id="refresh-all-vocab" class="btn btn-sm btn-danger">** Refresh All Vocab</a>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div id="toolbar"></div>
        <table id="user__list"></table>
    </div>
</div>
<script>

    $(document).on('click', 'button.forcelogin__btn', function(){
        Auth.forceLogin($(this).attr('data-userid'));
    })

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
        pagination: true,
        columns: [
            {
                field: 'id',
                title: 'ID',
                sortable: true,
            },
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
            },
            {
                formatter: (obj, row)=>{
                    return "<button class='btn btn-info forcelogin__btn' data-userid='" + row.id + "'>Login</button>"
                }
            }
        ],
    });
</script>
<?=bottom_private()?>