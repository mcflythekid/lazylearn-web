<?php function Article(){ ?>

<div id="article__modal__create" class="modal fade" role="dialog" tabindex='-1'>
    <div class="modal-dialog">

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
                                <input type="text" value="xxx" required class="form-control" id="article__modal__create--name" placeholder="Name">
                            </div>
                            <div class="form-group">
                                <label for="article__modal__create--content">Content</label>
                                <input type="text" value="xxx" required class="form-control" id="article__modal__create--content" placeholder="Content">
                            </div>
                            <div class="form-group">
                                <label for="article__modal__create--url">Content</label>
                                <input type="text" value="xxx" class="form-control" id="article__modal__create--url" placeholder="URL">
                            </div>
                            <div class="form-group">
                                <label for="article__modal__create--category">Category</label>
                                <input type="text" value="xxx" required class="form-control" id="article__modal__create--category" placeholder="Category">
                            </div>
                        </div>
                    </div>
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

    $('#article__modal__create--form').submit(function(e){
        e.preventDefault();
        Article.create(
            $('#article__modal__create--name').val(),
            $('#article__modal__create--content').val(),
            $('#article__modal__create--url').val(),
            $('#article__modal__create--category').val(),
            ()=>{
                $('#article__modal__create--form')[0].reset();
                $('#article__modal__create').modal('hide');

                // TODO concac
                $('#article__list').bootstrapTable('refresh',{
                    silent: true
                });
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

    e.create = (name, content, url, category, callback)=>{
        AppApi.sync.post("/article/create", {
            name: name,
            content: content,
            url: url,
            category: category
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
        
    e.sendToDeck = (articleId, callback)=>{
        AppApi.sync.post("/article/send-to-deck/"+ articleId).then((res)=>{
            callback(res.data);
        })
    };

    e.openCreate = ()=>{
        $('#article__modal__create').modal('show');
    };

    return e;
})({}, AppApi, Constant, FlashMessage, Dialog);
</script>

<?php } ?>
