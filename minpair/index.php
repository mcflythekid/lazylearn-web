<?php
require_once '../core.php';
$TITLE = ('Minimum Pair');
$HEADER = "Minimum Pair";
$PATHS = [
    "Minimum Pair"
];
top_private();
Minpair();
?>

<div class="row u-mt-20">
    <div class="col-lg-12">

        <div id="mminpair__create--wrapper">
            <button class="btn btn-primary" id="mminpair__create--btn" type="submit">Create</button>
        </div>

        <table id="minpair__list"></table>
        <ul id="context-menu" class="dropdown-menu">
            <li data-item="delete"><a>Delete</a></li>
        </ul>

    </div>
</div>

<script>
    var refresh = ()=>{
        $('#minpair__list').bootstrapTable('refresh',{
            silent: true
        });
    };

    $('#mminpair__create--btn').click((event)=> {
        Minpair.openCreate();
    });

    $('#minpair__list').bootstrapTable({
        classes: 'table table-hover table-bordered table-condensed table-responsive bg-white',
        url: apiServer + "/minpair/search",
        cache: false,
        method: 'post',
        striped: false,
        toolbar: '#mminpair__create--wrapper',
        sidePagination: 'server',
        sortName: 'learnedCount',
        sortOrder: 'asc',
        pageSize: 20,
        pageList: [20, 50, 100],
        search: true,
        ajaxOptions: {
            headers: {
                Authorization: AppApi.getAuthorization()
            }
        },
        pagination: true,
        columns: [
            {
                field: 'word1',
                title: 'Word 1',
                sortable: true
            },
            {
                field: 'word2',
                title: 'Word 2',
                sortable: true
            },
            {
                width: 100,
                field: 'learnedCount',
                title: 'Progress',
                sortable: true,
                formatter: (obj, row)=>{
                    if (obj < 2){
                        return obj + '/' + Constant.minpairCount;
                    }
                    return '<strong style="color: green">' + obj + '/' + Constant.minpairCount +  '</strong>';
                }
            },
            {
                width: 100,
                formatter: (obj,row)=>{
                    var learnHtml = '';

                    learnHtml = '<a class="btn btn-sm btn-success pull-left" href="/minpair/learn.php?id=' + row.id + '">Learn</a> ';

                    return  learnHtml +
                        '<button class="btn btn-sm context-menu-button pull-right"><span class="glyphicon glyphicon-menu-hamburger"></span></button>';
                }
            },
        ],
        contextMenu: '#context-menu',
        contextMenuButton: '.context-menu-button',
        contextMenuAutoClickRow: true,
        beforeContextMenuRow: function(e,row,buttonElement){
            if (row.archived == 0){
                $('#context-menu li[data-item="archive"]').show();
                $('#context-menu li[data-item="unarchive"]').hide();
            } else {
                $('#context-menu li[data-item="archive"]').hide();
                $('#context-menu li[data-item="unarchive"]').show();
            }
            return true;
        },
        onContextMenuItem: function(row, $el) {
             if ($el.data("item") == "delete") {
                Minpair.delete(row.id, ()=>{
                    refresh();
                });
            }
        }
    });

</script>

<?=bottom_private()?>