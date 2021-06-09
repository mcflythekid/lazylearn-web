<?php function Minpair(){ 
    global $lang;
?>

    <div id="minpair__modal__create" class="modal fade" role="dialog" tabindex='-1'>
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?= $lang["class.minpair.form.title"] ?></span></h4>
                </div>
                <div class="modal-body">
                    <form id="minpair__modal__create--form">
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="minpair__modal__create--word1"><?= $lang["class.minpair.form.input.word1.label"] ?></label>
                                    <input type="text" required class="form-control" id="minpair__modal__create--word1" placeholder="<?= $lang["class.minpair.form.input.word1.holder"] ?>" value="">
                                </div>
                                <div class="form-group">
                                    <label for="minpair__modal__create--phonetic1"><?= $lang["class.minpair.form.input.phonetic1.label"] ?></label>
                                    <input type="text" required class="form-control" id="minpair__modal__create--phonetic1" placeholder="<?= $lang["class.minpair.form.input.phonetic1.holder"] ?>" value="">
                                </div>
                                <div class="form-group">
                                    <label for="minpair__modal__create--phonetic1"><?= $lang["class.minpair.form.input.audio1.label"] ?></label>
                                    <input type="file" accept="audio/*" multiple required accept=".mp3" class="form-control" id="minpair__modal__create--audio1" >
                                </div>
								<div class="form-group">
                                    <label for="minpair__modal__create--language"><?= $lang["class.minpair.form.input.language.label"] ?></label>
                                    <input type="text" required class="form-control" id="minpair__modal__create--language" placeholder="<?= $lang["class.minpair.form.input.language.holder"] ?>" value="">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="minpair__modal__create--word1"><?= $lang["class.minpair.form.input.word2.label"] ?></label>
                                    <input type="text" required class="form-control" id="minpair__modal__create--word2" placeholder="<?= $lang["class.minpair.form.input.word2.holder"] ?>" value="">
                                </div>
                                <div class="form-group">
                                    <label for="minpair__modal__create--phonetic1"><?= $lang["class.minpair.form.input.phonetic2.label"] ?></label>
                                    <input type="text" required class="form-control" id="minpair__modal__create--phonetic2" placeholder="<?= $lang["class.minpair.form.input.phonetic2.holder"] ?>" value="">
                                </div>
                                <div class="form-group">
                                    <label for="minpair__modal__create--phonetic1"><?= $lang["class.minpair.form.input.audio2.label"] ?></label>
                                    <input type="file" accept="audio/*" multiple required accept=".mp3" class="form-control" id="minpair__modal__create--audio2" >
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" form="minpair__modal__create--form" class="btn btn-success btn-flat"><?= $lang["class.minpair.form.submit"] ?></button>
                    <button type="button" class="btn btn-default btn-flat" data-dismiss="modal"><?= $lang["common.cancel"] ?></button>
                </div>
            </div>

        </div>
    </div>

    <script>

        $('#minpair__modal__create--form').submit(function(e){
            e.preventDefault();

            Promise.all([
                EncodedFile.readAll($('#minpair__modal__create--audio1')),
                EncodedFile.readAll($('#minpair__modal__create--audio2'))
            ]).then((results)=>{
                //debugger;
                Minpair.create(
                    $('#minpair__modal__create--word1').val(),
                    $('#minpair__modal__create--word2').val(),
                    $('#minpair__modal__create--phonetic1').val(),
                    $('#minpair__modal__create--phonetic2').val(),
                    $('#minpair__modal__create--language').val(),
                    results[0],
                    results[1],
                    ()=>{
                        $('#minpair__modal__create--form')[0].reset();
                        $('#minpair__modal__create').modal('hide');

                        // TODO concac
                        $('#minpair__list').bootstrapTable('refresh',{
                            silent: true
                        });
                    }
                )
            });
        });
    </script>



<script>
    /**
     * @author McFly the Kid
     */
    var Minpair = ((e, AppApi, Constant, FlashMessage, Dialog)=>{

        e.get = (minpairId, callback, errCb)=>{
            AppApi.async.get("/minpair/get/" + minpairId).then((response)=>{
                callback(response.data);
            }).catch(err=>{
				if (errCb) {
					errCb(err);
				}
			});
        };

        e.getLearn = (minpairId, callback, errCb)=>{
            AppApi.async.get("/minpair/get-learn/" + minpairId).then((response)=>{
                callback(response.data);
            }).catch(err=>{
				if (errCb) {
					errCb(err);
				}
			});
        };

        e.create = (word1, word2, phonetic1, phonetic2, language, audioFiles1, audioFiles2, callback)=>{
            AppApi.sync.post("/minpair/create", {
                word1,
                word2,
                phonetic1,
                phonetic2,
                audioFiles1,
                audioFiles2,
				language
            }).then(()=>{
                callback();
            });
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
		
		e.sendToDeck = (minpairId, callback)=>{
            AppApi.sync.post("/minpair/send-to-deck/"+ minpairId).then((res)=>{
                callback(res.data);
            })
        };

        e.openCreate = ()=>{
            $('#minpair__modal__create').modal('show');
        };

        return e;
    })({}, AppApi, Constant, FlashMessage, Dialog);
</script>

<?php } ?>
