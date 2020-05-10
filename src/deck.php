<?php
require_once 'core.php';
require_once 'lang/core.php';
$TITLE = ($lang["page.deck.title"]);
$HEADER = $lang["page.deck.header"];

top_private();

Deck();
?>

<div class="row u-mt-0">
    <div class="col-lg-12">

        <div id="deck__create--wrapper">
            <form id="deck__create--form">
                <div class="input-group" >
                    <input type="text" class="form-control" id="deck__create--name" required placeholder='<?= $lang["page.deck.input.create.holder"] ?>'>
                    <span class="input-group-btn">
						<button class="btn btn-info btn-flat" type="submit"><?= $lang["page.deck.btn.create"] ?></button>
					</span>
                </div>
            </form>
        </div>

        <table id="deck__list"></table>
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
        formatSearch: ()=> { return '<?= $lang["page.deck.input.search.holder"] ?>' },
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
                title: '<?= $lang["page.deck.column.name"] ?>',
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
                title: '<?= $lang["page.deck.column.expired_card"] ?>',
                sortable: false,
                formatter: (obj, row)=>{
                    return "<span class='learnable-count' data-deckid='"+row.id+"'><span>";
                }
            },
            {
                width: 50,
                formatter: (obj,row)=>{
                    if (row.type == 'minpair'){
                        return '<a data-deckid="' + row.id + '" class="btn btn-xs btn-flat btn-info pull-left learn-go" href="/minpair/learn-redirect.php?type=learn&id=' + row.id + '"><?= $lang["common.loading"] ?></a> ';
                    } else {
                        return '<a data-deckid="' + row.id + '" class="btn btn-xs btn-flat btn-info pull-left learn-go" href="/deck/learn.php?type=learn&id=' + row.id + '"><?= $lang["common.loading"] ?></a> ';
                    }
                }
            },
            {
                field: 'createdDate',
                title: '<?= $lang["page.deck.column.created"] ?>',
                sortable: true
            },
            {
                width: 250,
                formatter: (obj,row)=>{
                    if (row.type == "default")
                        return '<span class="deck-menu">' + 
                            '<button data-id="' + row.id + '" class="action-rename btn btn-xs btn-info btn-flat"><?= $lang["common.rename"] ?></button>' +
                            (row.archived == 0 ? '<button data-id="' + row.id + '" class="action-archive btn btn-xs btn-info btn-flat u-ml-5"><?= $lang["common.archive"] ?></button>' : '' ) +
                            (row.archived == 1 ? '<button data-id="' + row.id + '" class="action-unarchive btn btn-xs btn-info btn-flat u-ml-5"><?= $lang["common.unarchive"] ?></button>' : '' ) +
                            '<button data-id="' + row.id + '" class="action-delete btn btn-xs btn-danger btn-flat u-ml-5"><?= $lang["common.delete"] ?></button>' + 
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
                        $el.text('<?= $lang["page.deck.btn.learn"] ?>');
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
