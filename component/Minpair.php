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
                                    <input type="text" required class="form-control" id="minpair__modal__create--word1" placeholder="Word 1">
                                </div>
                                <div class="form-group">
                                    <label for="minpair__modal__create--phonetic1">Phonetic 1</label>
                                    <input type="text" required class="form-control" id="minpair__modal__create--phonetic1" placeholder="Phonetic 1">
                                </div>
                                <div class="form-group">
                                    <label for="minpair__modal__create--phonetic1">Audio 1</label>
                                    <input type="file" required accept=".mp3" class="form-control" id="minpair__modal__create--audio1" >
                                </div>
								<div class="form-group">
                                    <label for="minpair__modal__create--language">Language</label>
                                    <input type="text" required class="form-control" id="minpair__modal__create--language" placeholder="english, chinese, ...">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="minpair__modal__create--word1">Word 2</label>
                                    <input type="text" required class="form-control" id="minpair__modal__create--word2" placeholder="Word 2">
                                </div>
                                <div class="form-group">
                                    <label for="minpair__modal__create--phonetic1">Phonetic 2</label>
                                    <input type="text" required class="form-control" id="minpair__modal__create--phonetic2" placeholder="Phonetic 2">
                                </div>
                                <div class="form-group">
                                    <label for="minpair__modal__create--phonetic1">Audio 2</label>
                                    <input type="file" required accept=".mp3" class="form-control" id="minpair__modal__create--audio2" >
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
            var readImage = (inputElement)=>{
                var deferred = $.Deferred();

                var files = inputElement.get(0).files;
                if (files && files[0]) {
                    var fr= new FileReader();
                    fr.onload = function(e) {
                        deferred.resolve(e.target.result.substring(22));
                    };
                    fr.readAsDataURL( files[0] );
                } else {
                    deferred.resolve(undefined);
                }
                return deferred.promise();
            };

            Promise.all([
                readImage($('#minpair__modal__create--audio1')),
                readImage($('#minpair__modal__create--audio2'))
            ]).then((base64List)=>{
                Minpair.create(
                    $('#minpair__modal__create--word1').val(),
                    $('#minpair__modal__create--word2').val(),
                    $('#minpair__modal__create--phonetic1').val(),
                    $('#minpair__modal__create--phonetic2').val(),
                    $('#minpair__modal__create--language').val(),
                    base64List[0],
                    base64List[1],
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

        e.get = (minpairId, callback)=>{
            AppApi.async.get("/minpair/get/" + minpairId).then((response)=>{
                callback(response.data);
            });
        };

        e.create = (word1, word2, phonetic1, phonetic2, language, audio1, audio2, callback)=>{
            AppApi.sync.post("/minpair/create", {
                word1: word1,
                word2: word2,
                phonetic1: phonetic1,
                phonetic2: phonetic2,
                audio1: audio1,
                audio2: audio2,
				language: language
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
