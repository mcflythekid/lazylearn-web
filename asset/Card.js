/**
 * @author McFly the Kid
 */
var Card = ((e, AppApi, Constant, FlashMessage, Dialog, Deck)=>{

    e.create = (front, back, deckId, callback)=>{
        AppApi.sync.post("/card/create", {
            front: front,
            back: back,
            deckId: deckId
        }).then(()=>{
            callback();
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

    e.openEdit = function(deckId, deckName){
        $('#deck__modal__edit--title').text(deckName);
        $('#deck__modal__edit--name').val(deckName);
        $('#deck__modal__edit--id').val(deckId);
        $('#deck__modal__edit--newName').val('');
        $('#deck__modal__edit').modal('show');
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
})({}, AppApi, Constant, FlashMessage, Dialog, Deck);