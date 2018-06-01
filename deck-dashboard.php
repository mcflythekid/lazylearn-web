<?php
require_once 'core.php';
title('Deck');
$HEADER = "Deck";
$PATHS = [
    "Deck"
];

top_private();
require 'modal/deck-edit.php';

?>
<style>
    #toolbar {
        width: 300px;
    }
    .panel-heading a:after {
        font-family:'Glyphicons Halflings';
        content:"\e114";
        float: right;
        color: grey;
    }
    .panel-heading a.collapsed:after {
        content:"\e080";
    }
</style>


<div class="row">
    <div class="col-lg-12">
        <div id="toolbar">
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
        <!-- context menu -->
        <ul id="context-menu" class="dropdown-menu">
            <li data-item="archive"><a>Archive</a></li>
            <li data-item="unarchive"><a>Unarchive</a></li>
            <li data-item="rename"><a>Rename</a></li>
            <li data-item="delete"><a>Delete</a></li>
        </ul>
    </div>
</div>
<script>
    (()=>{

        var refresh = ()=>{
            $('#deck__list').bootstrapTable('refresh',{
                silent: true
            });
        };

        $('#deck__create--form').submit((event)=> {
            event.preventDefault();
            $app.apisync.post("/user/" + $tool.getData('auth').userId + "/deck", {
                name : $('#deck__create--name').val().trim()
            }).then(()=>{
                $('#deck__create--name').val('');
                refresh();
            });
        });

        $('#deck__list').bootstrapTable({
            classes: 'table table-hover table-bordered table-condensed table-responsive bg-white',
            url: $app.endpoint + "/user/" + $tool.getData('auth').userId + "/deck/by-search",
            cache: false,
            striped: false,
            toolbar: '#toolbar',
            sidePagination: 'server',
            sortName: 'name',
            pageSize: 20,
            pageList: [20, 50, 100],
            search: true,
            ajaxOptions: {
                headers: {
                    Authorization: 'Bearer ' + $tool.getData('auth').token
                }
            },
            pagination: true,
            columns: [
                {
                    field: 'name',
                    title: 'Deck',
                    sortable: true,
                    formatter: (obj,row)=>{
                        return '<a href="'+ctx+'/deck.php?id='+row.id+'">'+obj+'</a>' +
                            (row.archived == 1 ? ' <span class="archived">Archived</span>' : '');
                    }
                },
                {
                    width: 100,
                    field: 'totalCard',
                    title: 'Cards',
                    sortable: true,
                },
                /*{
                    field: 'totalTimeupCard',
                    title: 'Timeups',
                    sortable: true,
                },*/
                {
                    width: 100,
                    formatter: (obj,row)=>{
                        return "<a class='btn btn-sm btn-info pull-left' href='/learn.php?type=learn&id=" + row.id + "'>Learn</a>" +
                         " <button class='btn btn-sm context-menu-button pull-right'><span class='glyphicon glyphicon-menu-hamburger'></span></button>";
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
                if ($el.data("item") == "rename") {
                    deckrename(row.id, row.name);
                } else if ($el.data("item") == "delete") {
                    $tool.confirm("This will remove this deck and cannot be undone!!!", function () {
                        $app.apisync.delete("/deck/" + row.id).then(() => {
                            refresh();
                        });
                    });
                } else if ($el.data("item") == "archive") {
                    $app.apisync.post("/deck/archive/" + row.id).then(() => {
                        refresh();
                    });
                } else if ($el.data("item") == "unarchive") {
                    $app.apisync.post("/deck/unarchive/" + row.id).then(() => {
                        refresh();
                    });
                }
            }
        });

    })();
</script>
<?=bottom_private()?>


<!--+-->
<!--'<button data-deck-id="'+obj+'" data-deck-name="'+row.name+'" data-toggle="modal" data-target="#deck__modal__edit" class="btn btn-sm btn-success">Rename</button>'+-->

