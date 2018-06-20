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