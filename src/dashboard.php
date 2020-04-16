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
        Deck.create($('#deck__create--name').val().trim(), deck=>{
            window.location = "/deck/view.php?id=" + deck.id;
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
                    var archived =  (row.archived == 1 ? ' <span class="archived">Archived</span>' : '');
					var link = '<a href="/deck/view.php?id=' + row.id + '">' + obj+'</a>' + archived;
					var noLink = obj + archived;
                    
                    return ["default","topic","vocab"].includes(row.type) ? link : noLink;
                }
            },
            {
                width: 50,
                title: 'Expired',
                sortable: true,
                formatter: (obj, row)=>{
                    return "<span class='learnable-count' data-deckid='"+row.id+"'><span>";
                }
            },
            {
                width: 50,
                formatter: (obj,row)=>{
                    if (row.type == 'minpair'){
                        return '<a data-deckid="' + row.id + '" class="btn btn-sm btn-success pull-left learn-go" href="/minpair/learn-redirect.php?type=learn&id=' + row.id + '">loading...</a> ';
                    } else if (row.type == 'topic'){
                        return '<a data-deckid="' + row.id + '" class="btn btn-sm btn-success pull-left learn-go" href="/article/learn-redirect.php?type=learn&id=' + row.id + '">loading...</a> ';
                    } else {
                        return '<a data-deckid="' + row.id + '" class="btn btn-sm btn-success pull-left learn-go" href="/deck/learn.php?type=learn&id=' + row.id + '">loading...</a> ';
                    }
                }
            },
            {
                width: 50,
                formatter: (obj,row)=>{
                    if (row.type == "default")
                        return '<button class="btn btn-sm context-menu-button pull-right"><span class="glyphicon glyphicon-menu-hamburger"></span></button>';
                    else
                        return '';
                }
            },
        ],
        onLoadSuccess: ()=>{
            $('a.learn-go').each(function(index){
                var $el = $(this);
                var deckId = $el.attr('data-deckid');
                AppApi.async.get("/learn/learnable-count/" + deckId).then(res=>{
                    if (res.data > 0){
                        $el.text('Learn');
                        $('span.learnable-count[data-deckid="'+deckId+'"]').text(res.data);
                    } else {
                        $el.remove();
                    }
                    
                }).catch(err=>{
                    $el.text('ERROR');
                });
            });
        },
        contextMenu: '#context-menu',
        contextMenuButton: '.context-menu-button',
        contextMenuAutoClickRow: true,
        beforeContextMenuRow: function(e,row,buttonElement){

            if (row.type != 'default'){
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
