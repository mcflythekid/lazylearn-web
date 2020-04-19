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
                                    <label for="vocab-create-phrase">Phrase</label>
                                    <input class="form-control" id="vocab-create-phrase" placeholder="Phrase">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="vocab-create-personalconnection">Personal connection</label>
                                    <input required class="form-control" id="vocab-create-personalconnection" placeholder="Personal connection">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="vocab-create-image">Image</label>
                                    <input type="file" accept="image/*" class="form-control" id="vocab-create-image">
                                    <input type="hidden"                                              id="vocab-create-image-encoded">
                                    <img style="max-height: 100px; display: block"                    id="vocab-create-image-preview">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="vocab-create-audio">Audio</label>
                                    <input type="file" accept="audio/*" class="form-control" id="vocab-create-audio">
                                    <input type="hidden"                                              id="vocab-create-audio-encoded">
                                    <audio controls style="width: 100%; display: block"               id="vocab-create-audio-preview">
                                        Your browser does not support the audio element.
                                    </audio>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="crawl" class="btn btn-info">Crawl</button>
                    <button type="submit" form="vocab-create-form" class="btn btn-success">Create</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div id="vocab-edit-modal" class="modal fade" role="dialog" tabindex='-1'>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit vocabulary</span></h4>
                </div>
                <div class="modal-body">
                    <form id="vocab-edit-form">
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="vocab-edit-word">Word</label>
                                    <input required class="form-control" id="vocab-edit-word" placeholder="Word">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="vocab-edit-phonetic">Phonetic</label>
                                    <input required class="form-control" id="vocab-edit-phonetic" placeholder="Phonetic">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="vocab-edit-phrase">Phrase</label>
                                    <input class="form-control" id="vocab-edit-phrase" placeholder="Phrase">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="vocab-edit-personalconnection">Personal connection</label>
                                    <input required class="form-control" id="vocab-edit-personalconnection" placeholder="Personal connection">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label>Old Image</label>
                                    <img id="vocab-edit-image-old" style="max-height: 100px; display: block">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label>Old Audio</label>
                                    <audio controls id="vocab-edit-audio-old" style="width: 100%; display: block">
                                        Your browser does not support the audio element.
                                    </audio>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="vocab-edit-image">New Image</label>
                                    <input type="file" accept="image/*" class="form-control" id="vocab-edit-image">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="vocab-edit-audio">New Audio</label>
                                    <input type="file" accept="audio/*" class="form-control" id="vocab-edit-audio">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" form="vocab-edit-form" class="btn btn-success">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <script>

        var Vocab = ((e, AppApi, EncodedFile)=>{

            e.resetCreateForm = ()=>{
                $('#vocab-create-form').get(0).reset();
                $('#vocab-create-audio-encoded').val('');
                $('#vocab-create-audio-preview').attr('src', '');
                $('#vocab-create-image-encoded').val('');
                $('#vocab-create-image-preview').attr('src', '');
            };


            e.crawl = ()=>{
                const word = $('#vocab-create-word').val().trim();
                if (!word){
                    FlashMessage.error('Word is required');
                    return;
                }
                AppApi.sync.get("/vocab/get-oxford/" + word).then((response)=>{
                    const data = response.data;
                    e.resetCreateForm();
                    $('#vocab-create-word').val(data.word);
                    $('#vocab-create-phonetic').val(data.phonetic);
                    $('#vocab-create-phrase').val(data.phrase);

                    $('#vocab-create-audio-encoded').val(JSON.stringify({  ext: "mp3", content: data.audio64 }));
                    $('#vocab-create-audio-preview').attr('src', data.audio);

                });
                
            };

            e.openCreate = (vocabdeckId, callback)=>{
                e.resetCreateForm();
                $('#vocab-create-form').off().submit((e)=>{
                    e.preventDefault();

                    if (
                        !$('#vocab-create-image-encoded').val()
                        || !$('#vocab-create-audio-encoded').val()
                    ) {
                        FlashMessage.error("Check your audio / image!");
                        return;
                    }

                    create(
                        vocabdeckId,
                        $('#vocab-create-word').val().trim(),
                        $('#vocab-create-phonetic').val().trim(),
                        $('#vocab-create-personalconnection').val().trim(),
                        $('#vocab-create-phrase').val().trim(),
                        JSON.parse($('#vocab-create-image-encoded').val()),
                        JSON.parse($('#vocab-create-audio-encoded').val()),
                        ()=>{
                            $('#vocab-create-modal').modal('hide');
                            if (callback) callback();
                        }
                    );
                });
                $('#vocab-create-modal').modal('show');
            };

            e.openEdit = (vocabId, successCallback, oncloseCallback)=>{
                get(vocabId, (vocab)=>{
                    $('#vocab-edit-form').get(0).reset();
                    $('#vocab-edit-word').val(vocab.word);
                    $('#vocab-edit-phonetic').val(vocab.phonetic);
                    $('#vocab-edit-personalconnection').val(vocab.personalConnection);
                    $('#vocab-edit-phrase').val(vocab.phrase);
                    $('#vocab-edit-audio-old').attr('src', AppApi.fileServer + vocab.audioPath);
                    $('#vocab-edit-image-old').attr('src', AppApi.fileServer + vocab.imagePath);

                    $('#vocab-edit-form').off().submit((e)=>{
                        e.preventDefault();
                        Promise.all([
                            EncodedFile.read($('#vocab-edit-image')),
                            EncodedFile.read($('#vocab-edit-audio'))
                        ]).then((encodedFiles)=>{
                            edit(
                                vocabId,
                                $('#vocab-edit-word').val().trim(),
                                $('#vocab-edit-phonetic').val().trim(),
                                $('#vocab-edit-personalconnection').val().trim(),
                                $('#vocab-edit-phrase').val().trim(),
                                encodedFiles[0],
                                encodedFiles[1],
                                (editData)=>{
                                    if (successCallback) successCallback(editData);
                                    $('#vocab-edit-modal').modal('hide');
                                }
                            );
                        });
                    });
                    $('#vocab-edit-modal').modal('show').off('hidden.bs.modal').on('hidden.bs.modal', function(){
                        $(this).off('hidden.bs.modal');
                        if(oncloseCallback) oncloseCallback();
                    });
                });
            };

            var create = (vocabdeckId, word, phonetic, personalConnection, phrase, encodedImage, encodedAudio, callback)=>{
                AppApi.sync.post("/vocab/create", {
                    vocabdeckId: vocabdeckId,
                    word: word,
                    phonetic: phonetic,
                    personalConnection: personalConnection,
                    phrase: phrase,
                    encodedImage: encodedImage,
                    encodedAudio: encodedAudio
                }).then((response)=>{
                    if (callback) callback();
                });
            };

            var edit = (vocabId, word, phonetic, personalConnection, phrase, encodedImage, encodedAudio, callback)=>{
                AppApi.sync.post("/vocab/edit", {
                    vocabId: vocabId,
                    word: word,
                    phonetic: phonetic,
                    personalConnection: personalConnection,
                    phrase: phrase,
                    encodedImage: encodedImage,
                    encodedAudio: encodedAudio
                }).then((response)=>{
                    if (callback) callback(response.data);
                });
            };

            e.get = (vocabdeckId)=>{
                AppApi.sync.get("/vocab/get/" + vocabdeckId).then((response)=>{
                    return new Promise((resolve)=>{
                        resolve(response.data);
                    })
                })
            };

            e.delete = (vocabId, callback, closeDialogCallback)=>{

                Dialog.confirm("This vocab and all card belong to it will be deleted?", ()=> {
                    AppApi.sync.post("/vocab/delete/" + vocabId).then((res) => {
                        if (callback) callback(res.data);
                    });
                }, ()=>{
                    if (closeDialogCallback) closeDialogCallback();
                });
            };

            var get = (vocabId, callback)=>{
                AppApi.sync.get("/vocab/get/" + vocabId).then((res)=>{
                    if (callback) callback(res.data);
                });
            };

            e.get = get;

            return e;
        })({}, AppApi, EncodedFile);



        $("#vocab-create-modal button#crawl").click(()=>{
            Vocab.crawl();
        });
        $("#vocab-create-image").change(function(){
            EncodedFile.read($(this)).then(res=>{
                console.log("Encoded image", res);
                $("#vocab-create-image-encoded").val(JSON.stringify(res));
            }).catch(err=>{
                FlashMessage.error("Cannot read image");
            });

            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#vocab-create-image-preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]); // convert to base64 string
            }
        });
        $("#vocab-create-audio").change(function(){
            EncodedFile.read($(this)).then(res=>{
                console.log("Encoded audio", res);
                $("#vocab-create-audio-encoded").val(JSON.stringify(res));
            }).catch(err=>{
                FlashMessage.error("Cannot read audio");
            });

            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#vocab-create-audio-preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]); // convert to base64 string
            }
        });


    </script>

<?php } ?>