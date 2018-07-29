<?php
require_once 'core.php';
$TITLE = ('Dashboard');
$HEADER = "Dashboard";

top_private();

Deck();
?>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-target="#collapseOne" href="#" class="a-no-underline display-block">
                        Overall Status
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

<div class="row u-mt-20">
    <div class="col-lg-12">

        <div id="deck__create--wrapper">
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
        <ul id="context-menu" class="dropdown-menu">
            <li data-item="archive"><a>Archive</a></li>
            <li data-item="unarchive"><a>Unarchive</a></li>
            <li data-item="edit"><a>Rename</a></li>
            <li data-item="delete"><a>Delete</a></li>
        </ul>

    </div>
</div>

<script>

    AppChart.drawCurrentUserDecks('lazychart__user');


    var refresh = ()=>{
        $('#deck__list').bootstrapTable('refresh',{
            silent: true
        });
        AppChart.drawCurrentUserDecks('lazychart__user');

    };

    $('#deck__create--form').submit((event)=> {
        event.preventDefault();
        Deck.create($('#deck__create--name').val().trim(), ()=>{
            $('#deck__create--name').val('');
            refresh();
        })
    });

    $('#deck__list').bootstrapTable({
        classes: 'table table-hover table-bordered table-condensed table-responsive bg-white',
        url: apiServer + "/deck/search",
        cache: false,
        method: 'post',
        striped: false,
        toolbar: '#deck__create--wrapper',
        sidePagination: 'server',
        sortName: 'createdDate',
        sortOrder: 'desc',
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
                field: 'name',
                title: 'Name',
                sortable: true,
                formatter: (obj,row)=>{
                    return '<a href="/deck/view.php?id=' + row.id + '">' + obj+'</a>' +
                        (row.archived == 1 ? ' <span class="archived">Archived</span>' : '');
                }
            },
            {
                width: 100,
                field: 'totalCard',
                title: 'Size',
                sortable: true,
            },
            {
                width: 100,
                field: 'totalTimeupCard',
                title: 'Today',
                sortable: true,
            },
            {
                width: 50,
                formatter: (obj,row)=>{
                    var learnHtml = '';
                    if (row.archived == 0 && row.totalTimeupCard > 0){
                        learnHtml = '<a class="btn btn-sm btn-success pull-left" href="/deck/learn.php?type=learn&id=' + row.id + '">Learn</a> ';
                    }
                    return  learnHtml;
                }
            },
            {
                width: 50,
                formatter: (obj,row)=>{
                    if (!row.vocabdeckId)
                        return '<button class="btn btn-sm context-menu-button pull-right"><span class="glyphicon glyphicon-menu-hamburger"></span></button>';
                    else
                        return '';
                }
            },
        ],
        contextMenu: '#context-menu',
        contextMenuButton: '.context-menu-button',
        contextMenuAutoClickRow: true,
        beforeContextMenuRow: function(e,row,buttonElement){

            if (row.vocabdeckId){
                return false;
            }

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
            if ($el.data("item") == "edit") {
                Deck.openEdit(row.id, row.name);
            } else if ($el.data("item") == "delete") {
                Deck.delete(row.id, ()=>{
                    refresh();
                });
            } else if ($el.data("item") == "archive") {
                Deck.archive(row.id, ()=>{
                    refresh();
                });
            } else if ($el.data("item") == "unarchive") {
                Deck.unarchive(row.id, ()=>{
                    refresh();
                });
            }
        }
    });

</script>

<?=bottom_private()?>
