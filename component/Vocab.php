<?php function Vocab(){ ?>

    <div id="vocab-create-modal" class="modal fade" role="dialog" tabindex='-1'>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Create vocabulary</span></h4>
                </div>
                <div class="modal-body">
                    <form id="vocab-create-form">
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="vocab-create-word">Word</label>
                                    <input required class="form-control" id="vocab-create-word" placeholder="Word">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="vocab-create-phonetic">Phonetic</label>
                                    <input required class="form-control" id="vocab-create-phonetic" placeholder="Phonetic">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="vocab-create-personalconnection">Personal connection</label>
                                    <input required class="form-control" id="vocab-create-personalconnection" placeholder="Personal connection">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="vocab-create-gender">Gender</label>
                                    <input class="form-control" id="vocab-create-gender" placeholder="Gender">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="vocab-create-image">Image</label>
                                    <input type="file" accept="image/*" required class="form-control" id="vocab-create-image">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="vocab-create-audio">Audio</label>
                                    <input type="file" accept="audio/*" required class="form-control" id="vocab-create-audio">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" form="vocab-create-form" class="btn btn-success">Create</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <script>

        var Vocab = ((e, AppApi, EncodedFile)=>{

            e.openCreate = (vocabdeckId, callback)=>{
                $('#vocab-create-form').get(0).reset();
                $('#vocab-create-form').off().submit((e)=>{
                    e.preventDefault();
                    Promise.all([
                        EncodedFile.read($('#vocab-create-image')),
                        EncodedFile.read($('#vocab-create-audio'))
                    ]).then((encodedFiles)=>{
                        create(
                            vocabdeckId,
                            $('#vocab-create-word').val().trim(),
                            $('#vocab-create-phonetic').val().trim(),
                            $('#vocab-create-personalconnection').val().trim(),
                            $('#vocab-create-gender').val().trim(),
                            encodedFiles[0],
                            encodedFiles[1],
                            ()=>{
                                $('#vocab-create-modal').modal('hide');
                                if (callback) callback();
                            }
                        );
                    });
                });
                $('#vocab-create-modal').modal('show');
            };

            var create = (vocabdeckId, word, phonetic, personalConnection, gender, encodedImage, encodedAudio, callback)=>{
                AppApi.sync.post("/vocab/create", {
                    vocabdeckId: vocabdeckId,
                    word: word,
                    phonetic: phonetic,
                    personalConnection: personalConnection,
                    gender: gender,
                    encodedImage: encodedImage,
                    encodedAudio: encodedAudio
                }).then((response)=>{
                    if (callback) callback();
                });
            };

            e.get = (vocabdeckId)=>{
                AppApi.sync.get("/vocab/get/" + vocabdeckId).then((response)=>{
                    return new Promise((resolve)=>{
                        resolve(response.data);
                    })
                })
            };

            e.delete = (vocabId, callback)=>{

                Dialog.confirm("Are you sure?", ()=> {
                    AppApi.sync.post("/vocab/delete/" + vocabId).then((res) => {
                        if (callback) callback(res.data);
                    });
                });
            };

            e.archive = (vocabDeckId, callback)=>{
                AppApi.sync.post("/vocabdeck/archive/" + vocabDeckId).then((res)=>{
                    if (callback){
                        callback(res.data);
                    }
                });
            };

            e.unarchive = (vocabDeckId, callback)=>{
                AppApi.sync.post("/vocabdeck/unarchive/" + vocabDeckId).then((res)=>{
                    if (callback){
                        callback(res.data);
                    }
                });
            };

            e.get = (vocabDeckId, callback)=>{
                AppApi.sync.get("/vocabdeck/get/" + vocabDeckId).then((res)=>{
                    if (callback){
                        callback(res.data);
                    }
                });
            };

            return e;
        })({}, AppApi, EncodedFile);

    </script>

<?php } ?>