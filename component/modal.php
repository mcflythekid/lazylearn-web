<?php function modal(){ ?>
<!----------------------------------------------------------------------------------------------------------------------------------->
<div id="deck__modal__edit" class="modal fade" role="dialog" tabindex='-1'>
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit deck <span id="deck__modal__edit--title"></span></h4>
            </div>
            <div class="modal-body">
                <form id="deck__modal__edit--form">
                    <div class="form-group">
                        <label for="deck__modal__edit--name">New name</label>
                        <input type="text" required class="form-control" id="deck__modal__edit--newName" placeholder="New name">
                        <input type="hidden" id="deck__modal__edit--id">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="deck__modal__edit--form" class="btn btn-success">Rename</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
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
</script>
<!----------------------------------------------------------------------------------------------------------------------------------->




<!-- Modal -->
<div id="card__modal__edit" class="modal fade" role="dialog" tabindex='-1'>
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit card</h4>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div id="card__modal__edit--front"></div>
                </div>
                <div class="col-xs-6">
                    <div id="card__modal__edit--back"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="card__modal__edit--submit" class="btn btn-success">Change</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>

    </div>
</div>
<script>
    // var $card__modal__edit = ((e)=>{
    //     var cardId = null;
    //     var successCb = null;
    //     var closeCb = null;
    //
    //     var quillFront = new Quill('#card__modal__edit--front', $app.quillFrontConf);
    //     var quillBack = new Quill('#card__modal__edit--back', $app.quillBackConf);
    //     $(document).on('click', '#card__modal__edit--submit', function(e){
    //         if ($tool.quillIsBlank(quillFront) || $tool.quillIsBlank(quillBack)){
    //             return;
    //         }
    //         $app.apisync.patch("/card/" + cardId, {
    //             front: $tool.quillGetHtml(quillFront),
    //             back: $tool.quillGetHtml(quillBack)
    //         }).then((res)=>{
    //             $('#card__modal__edit').off('hidden.bs.modal').on('hidden.bs.modal', function(){
    //                 $(this).off('hidden.bs.modal');
    //                 successCb($tool.quillGetHtml(quillFront), $tool.quillGetHtml(quillBack));
    //                 if (closeCb) closeCb();
    //             }).modal('hide');
    //         }).catch((err)=>{
    //             if (closeCb) closeCb();
    //         });
    //     });
    //
    //     e.edit = (argCardId, argSuccessCb, argCloseCb)=>{
    //         cardId = argCardId;
    //         successCb = argSuccessCb;
    //         closeCb = argCloseCb;
    //         $app.apisync.get("/card/" + cardId).then((res)=>{
    //             $tool.quillSetHtml(quillFront, res.data.front);
    //             $tool.quillSetHtml(quillBack, res.data.back);
    //             $('#card__modal__edit').modal('show').off('hidden.bs.modal').on('hidden.bs.modal', function(){
    //                 $(this).off('hidden.bs.modal');
    //                 if (closeCb) closeCb();
    //             });
    //         }).catch((err)=>{
    //             if (argCloseCb) argCloseCb();
    //         });
    //     };
    //
    //     return e;
    // })({});
</script>

<?php } ?>