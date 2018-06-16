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

    e.openEdit = function(cardId, callback){
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