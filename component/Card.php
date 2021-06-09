<?php function Card(){ 
    global $lang;    
?>

<div id="card__modal__edit" class="modal fade" role="dialog" tabindex='-1'>
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= $lang["class.card.form.title"] ?></h4>
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
                <button type="button" id="card__modal__edit--submit" class="btn btn-success btn-flat"><?= $lang["class.card.form.submit"] ?></button>
                <button type="button" class="btn btn-flat btn-default" data-dismiss="modal"><?= $lang["common.cancel"] ?></button>
            </div>
        </div>

    </div>
</div>

<script>

    var quillEditBack = new Quill(
        '#card__modal__edit--back',
        Editor.generateQuillConfig(
            'Back side',
            ()=>{
                quillEditFront.focus();
            },
            ()=>{
                $('#card__modal__edit--submit').focus();
            }
        )
    );

    var quillEditFront = new Quill(
        '#card__modal__edit--front',
        Editor.generateQuillConfig(
            'Front side',
            ()=>{ },
            ()=>{
                quillEditBack.focus();
            }
        )
    );

    var Card = ((e, AppApi, Constant, FlashMessage, Dialog, Deck, quillEditFront, quillEditBack)=>{

        var get = (cardId, callback)=>{
            AppApi.async.get("/card/get/" + cardId).then((response)=>{
                if (callback) callback(response.data);
            });
        };

        var edit = (cardId, front, back, successCb)=>{
            AppApi.sync.post("/card/edit", {
                cardId: cardId,
                front: front,
                back: back
            }).then((res)=>{
                if (successCb) successCb(res.data);
            })
        };

        e.create = (deckId, quillFront, quillBack, callback)=>{
            if (Editor.isBlank(quillFront) || Editor.isBlank(quillBack)) {
                FlashMessage.warning("Please check your content");
                return;
            }
            AppApi.sync.post("/card/create", {
                front: Editor.getHtml(quillFront),
                back: Editor.getHtml(quillBack),
                deckId: deckId
            }).then(()=>{
                Editor.clear(quillFront);
                Editor.clear(quillBack);
                quillFront.focus();
                if (callback) callback();
            })
        };

        e.delete = (cardId, successCb, finalCb)=>{
            Dialog.confirm(
                'Are you sure? This card will be deleted!',
                ()=> {
                    AppApi.sync.post("/card/delete/" + cardId).then(() => {
                        if (successCb) successCb();
                    });
                },
                finalCb
            );
        };

        e.openEdit = function(cardId, successCb, finalCb){
            get(cardId, (cardObject)=>{

                Editor.setHtml(quillEditFront, cardObject.front);
                Editor.setHtml(quillEditBack, cardObject.back);

                $('#card__modal__edit--submit').off().click((e)=>{
                    if (Editor.isBlank(quillEditFront) || Editor.isBlank(quillEditBack)) {
                        FlashMessage.warning("Please check your content");
                        return;
                    }
                    edit(cardId, Editor.getHtml(quillEditFront), Editor.getHtml(quillEditBack), editedCard=>{
                        if (successCb) successCb(editedCard);
                        $('#card__modal__edit').modal('hide');
                    });
                });

                $('#card__modal__edit').modal('show').off('hidden.bs.modal').on('hidden.bs.modal', function(){
                    $(this).off('hidden.bs.modal');
                    if (finalCb) finalCb();
                });

            });
        };

        return e;

    })({}, AppApi, Constant, FlashMessage, Dialog, Deck, quillEditFront, quillEditBack);
</script>

<?php } ?>