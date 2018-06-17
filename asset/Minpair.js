/**
 * @author McFly the Kid
 */
var Minpair = ((e, AppApi, Constant, FlashMessage, Dialog)=>{

    e.get = (minpairId, callback)=>{
        AppApi.async.get("/minpair/get/" + minpairId).then((response)=>{
            callback(response.data);
        });
    };

    e.create = (word1, word2, phonetic1, phonetic2, audio1, audio2, callback)=>{
        AppApi.sync.post("/minpair/create", {
            word1: word1,
            word2: word2,
            phonetic1: phonetic1,
            phonetic2: phonetic2,
            audio1: audio1,
            audio2: audio2
        }).then(()=>{
            callback();
        })
    };

    e.delete = (minpairId, callback)=>{
        Dialog.confirm('Are you sure? This minpair will be deleted!', ()=>{
            AppApi.sync.post("/minpair/delete/"+ minpairId).then(()=>{
                callback();
            })
        });
    };

    e.learned = (minpairId, callback)=>{
        AppApi.sync.post("/minpair/learned/"+ minpairId).then(()=>{
            callback();
        })
    };

    e.openCreate = ()=>{
        $('#minpair__modal__create').modal('show');
    };

    return e;
})({}, AppApi, Constant, FlashMessage, Dialog);