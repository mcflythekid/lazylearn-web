<?php function Card(){ ?>




<script>
    /**
     * @author McFly the Kid
     */
    var Card = ((e, AppApi, Constant, FlashMessage, Dialog, Deck)=>{

        e.deleteMsg = 'Are you sure? This card will be deleted!';

        var createCardFrontQuill;

        var createCardBackQuill;

        var editCardFrontQuill;

        var editCardBackQuill;

        var get = (cardId, callback)=>{
            AppApi.async.get("/card/get/" + cardId).then((response)=>{
                callback(response.data);
            })
        };

        e.create = (deckId, callback)=>{
            if (Editor.isBlank(createCardFrontQuill) || Editor.isBlank(createCardBackQuill)) {
                FlashMessage.warning("Please check your content");
                return;
            }
            AppApi.sync.post("/card/create", {
                front: Editor.getHtml(createCardFrontQuill),
                back: Editor.getHtml(createCardBackQuill),
                deckId: deckId
            }).then(()=>{
                Editor.clear(createCardFrontQuill);
                Editor.clear(createCardBackQuill);
                createCardFrontQuill.focus();
                callback();
            })
        };

        e.delete = (cardId, callback)=>{
            Dialog.confirm(e.deleteMsg, ()=>{
                AppApi.sync.post("/card/delete/"+ cardId).then(()=>{
                    callback();
                })
            });
        };

        e.edit = (cardId, callback)=>{
            if (Editor.isBlank(editCardFrontQuill) || Editor.isBlank(editCardBackQuill)) {
                FlashMessage.warning("Please check your content");
                return;
            }
            AppApi.sync.post("/card/edit", {
                cardId: cardId,
                front: Editor.getHtml(editCardFrontQuill),
                back: Editor.getHtml(editCardBackQuill)
            }).then(()=>{
                callback();
            })
        };

        e.openEdit = function(cardId, callback, oncloseCallback){
            get(cardId, (cardObject)=>{
                Editor.setHtml(editCardFrontQuill, cardObject.front);
                Editor.setHtml(editCardBackQuill, cardObject.back);
                $('#card__modal__edit--cardId').val(cardId);
                $('#card__modal__edit').modal('show').off('hidden.bs.modal').on('hidden.bs.modal', function(){
                    $(this).off('hidden.bs.modal');
                    if (callback) callback();
                });
            });
        };

        e.setEditCardQuills = (front, back)=>{
            editCardFrontQuill = front;
            editCardBackQuill = back;
        };

        e.setCreateCardQuills = (front, back)=>{
            createCardFrontQuill = front;
            createCardBackQuill = back;
        };

        return e;
    })({}, AppApi, Constant, FlashMessage, Dialog, Deck);
</script>


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
                <input type="hidden" id="card__modal__edit--cardId">
                <div class="modal-footer">
                    <button type="button" id="card__modal__edit--submit" class="btn btn-success">Change</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>

        </div>
    </div>
    <script>
        var backEditQuill = new Quill('#card__modal__edit--back', Editor.generateQuillConfig('Back side', ()=>{
            frontEditQuill.focus();
        }, ()=>{
            $('#card__modal__edit--submit').focus();
        }));

        var frontEditQuill = new Quill('#card__modal__edit--front', Editor.generateQuillConfig('Front side', ()=>{
        }, ()=>{
            backEditQuill.focus();
        }));

        Card.setEditCardQuills(frontEditQuill, backEditQuill);

        $(document).on('click', '#card__modal__edit--submit', function(e){
            Card.edit($('#card__modal__edit--cardId').val(), ()=>{
                $('#card__modal__edit').modal('hide');
            })
        });
    </script>



<?php } ?>
