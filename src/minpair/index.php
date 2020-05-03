<?php
require_once '../core.php';
require_once '../lang/core.php';
$TITLE = ('Minpair');
$HEADER = "Minpair";
$PATHS = [
    "Minpair"
];
top_private();
Minpair();
?>

<div class="row u-mt-20">
    <div class="col-lg-12">

        <div id="mminpair__create--wrapper">
            <button class="btn btn-info btn-flat" id="mminpair__create--btn" type="submit">Create Minpair</button>
        </div>

        <table id="minpair__list"></table>

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
	
	$(document).on('click', 'a.act-send-to-deck', function(e){
		Minpair.sendToDeck($(this).attr('data-deck-id'), deckObj=>{
			FlashMessage.success("Get success");
			refresh();
		});
	});

    $('#minpair__list').bootstrapTable({
        classes: 'table table-hover table-bordered table-condensed table-responsive bg-white',
        url: apiServer + "/minpair/search",
        cache: false,
        method: 'post',
        striped: false,
        toolbar: '#mminpair__create--wrapper',
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
                field: 'language',
                title: 'Language',
                sortable: true
            },
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
                width: 70,
                formatter: (obj,row)=>{
                    return '<span class="minpair-menu">' + 
                        '<button data-id="' + row.id + '" class="action-delete btn btn-danger btn-flat u-ml-5">Delete</button>' + 
                    '</span>';
                }
            },
        ]
    });
    $(document).on('click', 'span.minpair-menu button.action-delete', function(event){
        const id = $(this).attr("data-id");
        Minpair.delete(id, ()=>{
            refresh();
        });
    });

</script>

<?=bottom_private()?>