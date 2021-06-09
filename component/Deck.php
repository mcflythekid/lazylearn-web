<?php function Deck(){ 
    global $lang;
?>

<div id="deck__modal__edit" class="modal fade" role="dialog" tabindex='-1'>
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= $lang["class.deck.form.title"] ?><span id="deck__modal__edit--title"></span></h4>
            </div>
            <div class="modal-body">
                <form id="deck__modal__edit--form">
                    <div class="form-group">
                        <label for="deck__modal__edit--name"><?= $lang["class.deck.form.email.label"] ?></label>
                        <input type="text" required class="form-control" id="deck__modal__edit--newName" placeholder="<?= $lang["class.deck.form.email.holder"] ?>">
                        <input type="hidden" id="deck__modal__edit--id">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="deck__modal__edit--form" class="btn btn-success btn-flat"><?= $lang["class.deck.form.submit"] ?></button>
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal"><?= $lang["common.cancel"] ?></button>
            </div>
        </div>

    </div>
</div>

<script>


    $('#deck__modal__edit--form').submit(function(e){
        e.preventDefault();
        Deck.edit($('#deck__modal__edit--id').val(), $('#deck__modal__edit--newName').val().trim(), ()=>{
            $('#deck__modal__edit').modal('hide');

            $('#deck__list').bootstrapTable('refresh',{
                silent: true
            });
        });
    });



    /**
     * @author McFly the Kid
     */
    var Deck = ((e, AppApi, Constant, FlashMessage, Dialog)=>{

        e.get = (deckId, callback)=>{
            AppApi.async.get("/deck/get/" + deckId).then((response)=>{
                callback(response.data);
            });
        };

        e.create = (name, callback)=>{
            AppApi.sync.post("/deck/create", {
                name: name
            }).then((res)=>{
                callback(res.data);
            })
        };

        e.delete = (deckId, callback)=>{
            Dialog.confirm('Are you sure? This deck will be deleted!', ()=>{
                AppApi.sync.post("/deck/delete/"+ deckId).then(()=>{
                    callback();
                })
            });
        };

        e.archive = (deckId, callback)=>{
            AppApi.sync.post("/deck/archive/"+ deckId).then(()=>{
                callback();
            })
        };

        e.unarchive = (deckId, callback)=>{
            AppApi.sync.post("/deck/unarchive/"+ deckId).then(()=>{
                callback();
            })
        };

        e.openEdit = function(deckId, deckName, callback){
            $('#deck__modal__edit--title').text(deckName);
            $('#deck__modal__edit--name').val(deckName);
            $('#deck__modal__edit--id').val(deckId);
            $('#deck__modal__edit--newName').val('');
            $('#deck__modal__edit').modal('show').off('hidden.bs.modal').on('hidden.bs.modal', function(){
                $(this).off('hidden.bs.modal');
                if (callback) callback();
            });
        };

        e.edit = (deckId, newName, callback)=>{
            AppApi.sync.post("/deck/edit", {
                deckId: deckId,
                newName: newName
            }).then(()=>{
                callback();
            });
        };

        return e;
    })({}, AppApi, Constant, FlashMessage, Dialog);
</script>

<?php } ?>
