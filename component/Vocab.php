<?php function Vocab(){
    global $lang;
?>

    <div id="vocab-create-modal" class="modal fade" role="dialog" tabindex='-1'>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?= $lang["class.vocab.form.title"] ?></span></h4>
                </div>
                <div class="modal-body">
                    <form id="vocab-create-form">
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="vocab-create-word"><?= $lang["class.vocab.form.input.word.label"] ?></label>
                                    <input required class="form-control" id="vocab-create-word" placeholder="<?= $lang["class.vocab.form.input.word.holder"] ?>">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="vocab-create-phonetic"><?= $lang["class.vocab.form.input.phonetic.label"] ?></label>
                                    <input required class="form-control" id="vocab-create-phonetic" placeholder="<?= $lang["class.vocab.form.input.phonetic.holder"] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="vocab-create-phrase"><?= $lang["class.vocab.form.input.phrase.label"] ?></label>
                                    <input class="form-control" id="vocab-create-phrase" placeholder="<?= $lang["class.vocab.form.input.phrase.holder"] ?>">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="vocab-create-personalconnection"><?= $lang["class.vocab.form.input.connection.label"] ?></label>
                                    <input required class="form-control" id="vocab-create-personalconnection" placeholder="<?= $lang["class.vocab.form.input.connection.holder"] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="vocab-create-image"><?= $lang["class.vocab.form.input.image.label"] ?></label>
                                    <input type="file" accept="image/*" class="form-control" id="vocab-create-image">
                                    <input type="text" class="form-control" id="vocab-create-image-paste" placeholder="<?= $lang["class.vocab.formall.input.paste_image.holder"] ?>">
                                    <input type="hidden"                                              id="vocab-create-image-encoded">
                                    <img style="max-height: 100px; display: block"                    id="vocab-create-image-preview">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="vocab-create-audio"><?= $lang["class.vocab.form.input.audio.label"] ?></label>
                                    <input type="file" accept="audio/*" class="form-control" id="vocab-create-audio">
                                    <input type="hidden"                                              id="vocab-create-audio-encoded">
                                    <audio controls style="width: 100%; display: block"               id="vocab-create-audio-preview">
                                        <?= $lang["class.vocab.form.input.audio_error.label"] ?>
                                    </audio>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="vocab-create-audiohint"><?= $lang["class.vocab.form.input.audiohint.label"] ?></label>
                                    <input class="form-control" id="vocab-create-audiohint" placeholder="<?= $lang["class.vocab.form.input.audiohint.holder"] ?>">
                                </div>
                            </div>
                        </div>                         
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="crawl" class="btn btn-info btn-flat"><?= $lang["class.vocab.form.crawl"] ?></button>
                    <button type="submit" form="vocab-create-form" class="btn btn-success btn-flat"><?= $lang["class.vocab.form.submit"] ?></button>
                    <button type="button" class="btn btn-default btn-flat" data-dismiss="modal"><?= $lang["common.cancel"] ?></button>
                </div>
            </div>
        </div>
    </div>

    <div id="vocab-edit-modal" class="modal fade" role="dialog" tabindex='-1'>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?= $lang["class.vocab.formupdate.title"] ?></span></h4>
                </div>
                <div class="modal-body">
                    <form id="vocab-edit-form">
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="vocab-edit-word"><?= $lang["class.vocab.form.input.word.label"] ?></label>
                                    <input required class="form-control" id="vocab-edit-word" placeholder="<?= $lang["class.vocab.form.input.word.holder"] ?>">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="vocab-edit-phonetic"><?= $lang["class.vocab.form.input.phonetic.label"] ?></label>
                                    <input required class="form-control" id="vocab-edit-phonetic" placeholder="<?= $lang["class.vocab.form.input.phonetic.holder"] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="vocab-edit-phrase"><?= $lang["class.vocab.form.input.phrase.label"] ?></label>
                                    <input class="form-control" id="vocab-edit-phrase" placeholder="<?= $lang["class.vocab.form.input.phrase.holder"] ?>">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="vocab-edit-personalconnection"><?= $lang["class.vocab.form.input.connection.label"] ?></label>
                                    <input required class="form-control" id="vocab-edit-personalconnection" placeholder="<?= $lang["class.vocab.form.input.connection.holder"] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label><?= $lang["class.vocab.formupdate.input.image_old.label"] ?></label>
                                    <img id="vocab-edit-image-old" style="max-height: 100px; display: block">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label><?= $lang["class.vocab.formupdate.input.audio_old.label"] ?></label>
                                    <audio controls id="vocab-edit-audio-old" style="width: 100%; display: block">
                                        <?= $lang["class.vocab.form.input.audio_error.label"] ?>
                                    </audio>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="vocab-edit-image"><?= $lang["class.vocab.formupdate.input.image_new.label"] ?></label>
                                    <input type="file" accept="image/*" class="form-control" id="vocab-edit-image">
                                    <input type="text" class="form-control" id="vocab-edit-image-paste" placeholder="<?= $lang["class.vocab.formall.input.paste_image.holder"] ?>">
                                    <input type="hidden"                                              id="vocab-edit-image-encoded">
                                    <img style="max-height: 100px; display: block"                    id="vocab-edit-image-preview">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="vocab-edit-audio"><?= $lang["class.vocab.formupdate.input.audio_new.label"] ?></label>
                                    <input type="file" accept="audio/*" class="form-control" id="vocab-edit-audio">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="vocab-edit-audiohint"><?= $lang["class.vocab.form.input.audiohint.label"] ?></label>
                                    <input class="form-control" id="vocab-edit-audiohint" placeholder="<?= $lang["class.vocab.form.input.audiohint.holder"] ?>">
                                </div>
                            </div>
                        </div>                        
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" form="vocab-edit-form" class="btn btn-success btn-flat"><?= $lang["class.vocab.formupdate.submit"] ?></button>
                    <button type="button" class="btn btn-default btn-flat" data-dismiss="modal"><?= $lang["common.cancel"] ?></button>
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

            e.resetEditForm = ()=>{
                $('#vocab-edit-form').get(0).reset();
                $('#vocab-edit-image-encoded').val('');
                $('#vocab-edit-image-preview').attr('src', '');
            };


            e.crawl = ()=>{
                const word = $('#vocab-create-word').val().trim();
                if (!word){
                    FlashMessage.error('Word is required');
                    return;
                }
                AppApi.sync.get("/vocab/get-vocab-data/" + word).then((response)=>{
                    const data = response.data;
                    e.resetCreateForm();
                    $('#vocab-create-word').val(data.word);
                    $('#vocab-create-phonetic').val(data.phonetic);
                    $('#vocab-create-phrase').val(data.phrase);

                    $('#vocab-create-audio-encoded').val(JSON.stringify({  ext: "mp3", content: data.audio64 }));
                    $('#vocab-create-audio-preview').attr('src', 'data:audio/mp3;base64,' + data.audio64);

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
                        FlashMessage.error("<?= $lang["class.vocab.error.require_audio_image"] ?>");
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
                        $('#vocab-create-audiohint').val().trim(),
                        ()=>{
                            $('#vocab-create-modal').modal('hide');
                            if (callback) callback();
                        }
                    );
                });
                $('#vocab-create-modal').modal('show');
            };

            e.openEdit = (vocabId, successCallback, oncloseCallback)=>{
                e.resetEditForm();
                get(vocabId, (vocab)=>{
                    $('#vocab-edit-form').get(0).reset();
                    $('#vocab-edit-word').val(vocab.word);
                    $('#vocab-edit-phonetic').val(vocab.phonetic);
                    $('#vocab-edit-personalconnection').val(vocab.personalConnection);
                    $('#vocab-edit-phrase').val(vocab.phrase);
                    $('#vocab-edit-audiohint').val(vocab.audioHint);
                    $('#vocab-edit-audio-old').attr('src', AppApi.fileServer + vocab.audioPath);
                    $('#vocab-edit-image-old').attr('src', AppApi.fileServer + vocab.imagePath);

                    $('#vocab-edit-form').off().submit((e)=>{
                        e.preventDefault();
                        const newImageJsonString = $('#vocab-edit-image-encoded').val();
                        const newImageJsonObject = null;
                        if (newImageJsonString){
                            newImageJsonObject = JSON.parse(newImageJsonString);
                        }

                        Promise.all([
                            //EncodedFile.read($('#vocab-edit-image')), // removeable
                            EncodedFile.read($('#vocab-edit-audio'))
                        ]).then((encodedFiles)=>{
                            edit(
                                vocabId,
                                $('#vocab-edit-word').val().trim(),
                                $('#vocab-edit-phonetic').val().trim(),
                                $('#vocab-edit-personalconnection').val().trim(),
                                $('#vocab-edit-phrase').val().trim(),
                                newImageJsonObject,
                                encodedFiles[1],
                                $('#vocab-edit-audiohint').val().trim(),
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

            var create = (vocabdeckId, word, phonetic, personalConnection, phrase, encodedImage, encodedAudio, audioHint, callback)=>{
                AppApi.sync.post("/vocab/create", {
                    vocabdeckId: vocabdeckId,
                    word: word,
                    phonetic: phonetic,
                    personalConnection: personalConnection,
                    phrase: phrase,
                    encodedImage: encodedImage,
                    encodedAudio: encodedAudio,
                    audioHint: audioHint
                }).then((response)=>{
                    if (callback) callback();
                });
            };

            var edit = (vocabId, word, phonetic, personalConnection, phrase, encodedImage, encodedAudio, audioHint, callback)=>{
                AppApi.sync.post("/vocab/edit", {
                    vocabId: vocabId,
                    word: word,
                    phonetic: phonetic,
                    personalConnection: personalConnection,
                    phrase: phrase,
                    encodedImage: encodedImage,
                    encodedAudio: encodedAudio,
                    audioHint: audioHint
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

                Dialog.confirm("<?= $lang["common.delete_confirm.childs"] ?>", ()=> {
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
                FlashMessage.error("<?= $lang["class.vocab.error.cannot_read_image"] ?>");
            });

            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#vocab-create-image-preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]); // convert to base64 string
            }
        });
        $("#vocab-edit-image").change(function(){
            EncodedFile.read($(this)).then(res=>{
                console.log("Encoded image", res);
                $("#vocab-edit-image-encoded").val(JSON.stringify(res));
            }).catch(err=>{
                FlashMessage.error("<?= $lang["class.vocab.error.cannot_read_image"] ?>");
            });

            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#vocab-edit-image-preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]); // convert to base64 string
            }
        });
        $("#vocab-create-audio").change(function(){
            EncodedFile.read($(this)).then(res=>{
                console.log("Encoded audio", res);
                $("#vocab-create-audio-encoded").val(JSON.stringify(res));
            }).catch(err=>{
                FlashMessage.error("<?= $lang["class.vocab.error.cannot_read_audio"] ?>");
            });

            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#vocab-create-audio-preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]); // convert to base64 string
            }
        });

        document.getElementById('vocab-create-image-paste').onpaste = function (event) {
            // use event.originalEvent.clipboard for newer chrome versions
            var items = (event.clipboardData  || event.originalEvent.clipboardData).items;
            //console.log(JSON.stringify(items)); // will give you the mime types
            // find pasted image among pasted items
            var blob = null;
            for (var i = 0; i < items.length; i++) {
                if (items[i].type.indexOf("image") === 0) {
                    blob = items[i].getAsFile();
                }
            }
            // load image if there is a pasted image
            if (blob !== null) {
                var reader = new FileReader();
                reader.onload = function(event) {
                    console.log(event.target.result); // data url!
                    document.getElementById("vocab-create-image-preview").src = event.target.result;
                    $("#vocab-create-image-encoded").val(JSON.stringify(EncodedFile.fromBase64Image(event.target.result)));
                };
                reader.readAsDataURL(blob);
            } else {
                event.preventDefault();
                FlashMessage.error("<?= $lang["class.vocab.error.clipbard_image_not_found"] ?>");
            }
        }
        document.getElementById('vocab-edit-image-paste').onpaste = function (event) {
            // use event.originalEvent.clipboard for newer chrome versions
            var items = (event.clipboardData  || event.originalEvent.clipboardData).items;
            console.log(JSON.stringify(items)); // will give you the mime types
            // find pasted image among pasted items
            var blob = null;
            for (var i = 0; i < items.length; i++) {
                if (items[i].type.indexOf("image") === 0) {
                blob = items[i].getAsFile();
                }
            }
            // load image if there is a pasted image
            if (blob !== null) {
                var reader = new FileReader();
                reader.onload = function(event) {
                    console.log(event.target.result); // data url!
                    document.getElementById("vocab-edit-image-preview").src = event.target.result;
                    $("#vocab-edit-image-encoded").val(JSON.stringify(EncodedFile.fromBase64Image(event.target.result)));
                };
                reader.readAsDataURL(blob);
            } else {
                event.preventDefault();
                FlashMessage.error("<?= $lang["class.vocab.error.clipbard_image_not_found"] ?>");
            }
        }

    </script>

<?php } ?>