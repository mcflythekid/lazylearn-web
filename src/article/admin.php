<?php
require_once '../core.php';
$TITLE = ('Admin: Topic');
$HEADER = "Admin: Topic";
$PATHS = [
    "Admin: Topic"
];
top_private();
Article();
?>

<div class="row u-mt-20">
    <div class="col-lg-12">
        <div id="article__create--wrapper"></div>
        <table id="article__list"></table>
    </div>
</div>

<script>
    var refresh = ()=>{
        $('#article__list').bootstrapTable('refresh',{
            silent: true
        });
    };

    $('#article__list').bootstrapTable({
        classes: 'table table-hover table-bordered table-condensed table-responsive bg-white',
        url: apiServer + "/article/admin/search",
        cache: false,
        method: 'post',
        striped: false,
        toolbar: '#article__create--wrapper',
        sidePagination: 'server',
        sortName: 'createdDate',
        sortOrder: 'desc',
        pageSize: 100,
        pageList: [100, 150, 200],
        search: true,
        ajaxOptions: {
            headers: {
                Authorization: AppApi.getAuthorization()
            }
        },
        pagination: true,
        columns: [
            {
                field: 'name',
                title: 'Name',
                sortable: true,
                formatter: (o, row)=>{
                    return '<a href="/article/view.php?id=' + row.id + '">' + o + '</a>';
                }
            },
            {
                field: 'url',
                title: 'URL',
                sortable: true
            },
            {
                field: 'createdDate',
                title: 'Created',
                sortable: true
            },
            {
                field: 'user.fullName',
                title: 'User Name',
                sortable: true
            },
            {
                field: 'user.id',
                title: 'User Id',
                sortable: true
            },
            {
                field: 'user.email',
                title: 'User Email',
                sortable: true
            },
            {
                width: 70,
                formatter: (obj,row)=>{
                    return '<span class="article-menu">' + 
                        '<button data-id="' + row.id + '" class="action-delete btn btn-danger btn-flat u-ml-5">Delete</button>' + 
                    '</span>';
                }
            },
        ]
    });
    $(document).on('click', 'span.article-menu button.action-delete', function(event){
        const id = $(this).attr("data-id");
        Article.delete(id, ()=>{
            refresh();
        });
    });
</script>

<?=bottom_private()?>