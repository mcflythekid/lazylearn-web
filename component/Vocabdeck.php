<?php function Vocabdeck(){ ?>

    <div id="vocabdeck-rename-modal" class="modal fade" role="dialog" tabindex='-1'>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Rename</span></h4>
                </div>
                <div class="modal-body">
                    <form id="vocabdeck-rename-form">
                        <div class="form-group">
                            <label for="vocabdeck-rename-name">New name</label>
                            <input required class="form-control" id="vocabdeck-rename-newname" placeholder="New name">

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" form="vocabdeck-rename-form" class="btn btn-success">Rename</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <script>

        var Vocabdeck = ((e, AppApi)=>{

            e.openRename = (vocabDeckId, callback)=>{
                $('#vocabdeck-rename-form').get(0).reset();
                $('#vocabdeck-rename-form').off().submit((e)=>{
                    e.preventDefault();
                    rename(vocabDeckId, $('#vocabdeck-rename-newname').val(), ()=>{
                        $('#vocabdeck-rename-modal').modal('hide');
                        if (callback) callback();
                    })
                });
                $('#vocabdeck-rename-modal').modal('show');
                var rename = (vocabdeckId, newName, callback)=>{
                    AppApi.sync.post("/vocabdeck/rename", {
                        vocabdeckId: vocabdeckId,
                        newName: newName
                    }).then((res)=>{
                        if (callback) callback(res.data);
                    });
                };
            };

            e.create = (name, callback)=>{
                AppApi.sync.post("/vocabdeck/create", {
                    name: name
                }).then((res)=>{
                    if (callback)  callback(res.data);
                });
            };

            e.delete = (vocabDeckId, callback)=>{
                Dialog.confirm("Are you sure?", ()=>{
                    AppApi.async.post("/vocabdeck/delete/" + vocabDeckId).then((response)=>{
                        if (callback) callback(response.data);
                    });
                });
            };

            e.archive = (vocabDeckId, callback)=>{
                AppApi.async.post("/vocabdeck/archive/" + vocabDeckId).then((res)=>{
                    if (callback) callback(res.data);
                });
            };

            e.unarchive = (vocabDeckId, callback)=>{
                AppApi.async.post("/vocabdeck/unarchive/" + vocabDeckId).then((res)=>{
                    if (callback) callback(res.data);
                });
            };

            e.get = (vocabDeckId, callback)=>{
                AppApi.async.get("/vocabdeck/get/" + vocabDeckId).then((res)=>{
                    if (callback) callback(res.data);
                });
            };

            return e;
        })({}, AppApi);

    </script>

<?php } ?>