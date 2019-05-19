<?php function Article(){ ?>

<div id="article__modal__create" class="modal fade" role="dialog" tabindex='-1'>
    <div class="modal-dialog  modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create a Article</span></h4>
            </div>
            <div class="modal-body">
                <form id="article__modal__create--form">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="article__modal__create--name">Name</label>
                                <input type="text" value="" required class="form-control" id="article__modal__create--name" placeholder="Name">
                            </div>
                            <div class="form-group">
                                <label for="article__modal__create--content">Content</label>
                                <div id="article__modal__create--content"></div>
                            </div>
                            <div class="form-group">
                                <label for="article__modal__create--url">URL</label>
                                <input type="text" value="" class="form-control" id="article__modal__create--url" placeholder="URL">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="article__modal__create--id-to-delete">
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="article__modal__create--form" class="btn btn-success">Create</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>

    </div>
</div>

<script>

    var quillContent = new Quill(
        '#article__modal__create--content',
        Editor.generateQuillConfig('Content',()=>{
            console.log("");
        },()=>{
            console.log("");
        })
    );

    $('#article__modal__create--form').submit(function(e){
        e.preventDefault();
        if (Editor.isBlank(quillContent)) {
            FlashMessage.warning("Please check your content");
            return;
        }
        var idToDelete = $('#article__modal__create--id-to-delete').val();
        Article.create(
            $('#article__modal__create--name').val(),
            Editor.getHtml(quillContent),
            $('#article__modal__create--url').val(),
            ()=>{
                $('#article__modal__create--form')[0].reset();
                $('#article__modal__create').modal('hide');
                // TODO concac
                $('#article__list').bootstrapTable('refresh',{
                    silent: true
                });
                if (idToDelete){
                    AppApi.sync.post("/article/delete/"+ idToDelete);
                }
            }
        );
    });
</script>



<script>
/**
 * @author McFly the Kid
 */
var Article = ((e, AppApi, Constant, FlashMessage, Dialog)=>{

    e.get = (articleId, callback, errCb)=>{
        AppApi.async.get("/article/get/" + articleId).then((response)=>{
            callback(response.data);
        }).catch(err=>{
            if (errCb) {
                errCb(err);
            }
        });
    };

    e.create = (name, content, url, callback)=>{
        AppApi.sync.post("/article/create", {
            name: name,
            content: content,
            url: url
        }).then(()=>{
            callback();
        });
    };

    e.delete = (articleId, callback)=>{
        Dialog.confirm('Are you sure? This article will be deleted!', ()=>{
            AppApi.sync.post("/article/delete/"+ articleId).then(()=>{
                callback();
            })
        });
    };
        
    e.openCreate = ()=>{
        $('#article__modal__create--id-to-delete').val('');
        $('#article__modal__create').modal('show');
    };

    e.openEdit = (name, content, url, idToDelete)=>{
        Editor.setHtml(quillContent, content);
        $('#article__modal__create--name').val(name);
        $('#article__modal__create--url').val(url);
        $('#article__modal__create--id-to-delete').val(idToDelete);
        $('#article__modal__create').modal('show');
    };

    return e;
})({}, AppApi, Constant, FlashMessage, Dialog);
</script>

<?php } ?>
