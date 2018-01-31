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
    var $card__modal__edit = ((e)=>{
        var cardId = null;
        var cb = null;
        var quillFront = new Quill('#card__modal__edit--front', $app.quillFrontConf);
        var quillBack = new Quill('#card__modal__edit--back', $app.quillBackConf);
        $(document).on('click', '#card__modal__edit--submit', function(e){
            if ($tool.quillIsBlank(quillFront) || $tool.quillIsBlank(quillBack)){
                return;
            }
            $app.apisync.put("/card/" + cardId, {
                front: $tool.quillGetHtml(quillFront),
                back: $tool.quillGetHtml(quillBack)
            }).then((res)=>{
                $('#card__modal__edit').modal('hide');
                cb();
            });
        });

        e.edit = (argCardId, argCb)=>{
            cardId = argCardId;
            cb = argCb;
            $app.apisync.get("/card/" + cardId).then((res)=>{
                $tool.quillSetHtml(quillFront, res.data.front);
                $tool.quillSetHtml(quillBack, res.data.back);
                $('#card__modal__edit').modal('show');
            });
        };

        return e;
    })({});
</script>