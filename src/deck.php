<?php
require_once 'core.php';
require_once 'lang/core.php';
$TITLE = ('My Decks');
 $HEADER = "My Decks";

top_private();

Deck();
?>

<div class="row u-mt-0">
    <div class="col-lg-12">

        <div id="deck__create--wrapper">
            <form id="deck__create--form">
                <div class="input-group" >
                    <input type="text" class="form-control" id="deck__create--name" required placeholder="Deck name...">
                    <span class="input-group-btn">
						<button class="btn btn-info btn-flat" type="submit">Create Deck</button>
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

    var refresh = ()=>{
        $('#deck__list').bootstrapTable('refresh',{
            silent: true
        });
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
                field: 'createdDate',
                title: 'Created',
                sortable: true
            },
            {
                width: 50,
                formatter: (obj,row)=>{
                    if (row.type == 'minpair'){
                        return '<a data-deckid="' + row.id + '" class="btn btn-xs btn-flat btn-info pull-left learn-go" href="/minpair/learn-redirect.php?type=learn&id=' + row.id + '">loading...</a> ';
                    } else if (row.type == 'topic'){
                        return '<a data-deckid="' + row.id + '" class="btn btn-xs btn-flat btn-info pull-left learn-go" href="/article/learn-redirect.php?type=learn&id=' + row.id + '">loading...</a> ';
                    } else {
                        return '<a data-deckid="' + row.id + '" class="btn btn-xs btn-flat btn-info pull-left learn-go" href="/deck/learn.php?type=learn&id=' + row.id + '">loading...</a> ';
                    }
                }
            },
            {
                width: 250,
                formatter: (obj,row)=>{
                    if (row.type == "default")
                        return '<span class="deck-menu">' + 
                            '<button data-id="' + row.id + '" class="action-rename btn btn-xs btn-info btn-flat">Rename</button>' +
                            (row.archived == 0 ? '<button data-id="' + row.id + '" class="action-archive btn btn-xs btn-info btn-flat u-ml-5">Archive</button>' : '' ) +
                            (row.archived == 1 ? '<button data-id="' + row.id + '" class="action-unarchive btn btn-xs btn-info btn-flat u-ml-5">Unarchive</button>' : '' ) +
                            '<button data-id="' + row.id + '" class="action-delete btn btn-xs btn-danger btn-flat u-ml-5">Delete</button>' + 
                        '</span>';
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
        }
    });

    $(document).on('click', 'span.deck-menu button.action-rename', function(event){
        const id = $(this).attr("data-id");
        Deck.openEdit(id, "");
    });
    $(document).on('click', 'span.deck-menu button.action-archive', function(event){
        const id = $(this).attr("data-id");
        Deck.archive(id, ()=>{
            refresh();
        });
    });
    $(document).on('click', 'span.deck-menu button.action-unarchive', function(event){
        const id = $(this).attr("data-id");
        Deck.unarchive(id, ()=>{
            refresh();
        });
    });
    $(document).on('click', 'span.deck-menu button.action-delete', function(event){
        const id = $(this).attr("data-id");
        Deck.delete(id, ()=>{
            refresh();
        });
    });

</script>

<?=bottom_private()?>
