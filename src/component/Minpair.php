<?php function Minpair(){ ?>

    <div id="minpair__modal__create" class="modal fade" role="dialog" tabindex='-1'>
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Create a Minpair</span></h4>
                </div>
                <div class="modal-body">
                    <form id="minpair__modal__create--form">
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="minpair__modal__create--word1">Word 1</label>
                                    <input type="text" required class="form-control" id="minpair__modal__create--word1" placeholder="Word 1" value="sss">
                                </div>
                                <div class="form-group">
                                    <label for="minpair__modal__create--phonetic1">Phonetic 1</label>
                                    <input type="text" required class="form-control" id="minpair__modal__create--phonetic1" placeholder="Phonetic 1" value="sss">
                                </div>
                                <div class="form-group">
                                    <label for="minpair__modal__create--phonetic1">Audio 1</label>
                                    <input type="file" multiple required accept=".mp3" class="form-control" id="minpair__modal__create--audio1" >
                                </div>
								<div class="form-group">
                                    <label for="minpair__modal__create--language">Language</label>
                                    <input type="text" required class="form-control" id="minpair__modal__create--language" placeholder="english, chinese, ..." value="sss">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="minpair__modal__create--word1">Word 2</label>
                                    <input type="text" required class="form-control" id="minpair__modal__create--word2" placeholder="Word 2" value="sss">
                                </div>
                                <div class="form-group">
                                    <label for="minpair__modal__create--phonetic1">Phonetic 2</label>
                                    <input type="text" required class="form-control" id="minpair__modal__create--phonetic2" placeholder="Phonetic 2" value="sss">
                                </div>
                                <div class="form-group">
                                    <label for="minpair__modal__create--phonetic1">Audio 2</label>
                                    <input type="file" multiple required accept=".mp3" class="form-control" id="minpair__modal__create--audio2" >
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" form="minpair__modal__create--form" class="btn btn-success">Create</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
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
