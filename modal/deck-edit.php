<!-- Modal -->
<div id="deck__modal__edit" class="modal fade" role="dialog" tabindex='-1'>
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit: <span id="deck__modal__edit--title"></span></h4>
            </div>
            <div class="modal-body">
                <form id="deck__modal__edit--form">
                    <div class="form-group">
                        <label for="deck__modal__edit--name">New name</label>
                        <input type="text" required class="form-control" id="deck__modal__edit--name" placeholder="New name">
                        <input type="hidden" id="deck__modal__edit--id">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="deck__modal__edit--form" class="btn btn-success">Change</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>

    </div>
</div>
<script>
    $('#deck__modal__edit').on('show.bs.modal', function(e){
        var deckId = $(e.relatedTarget).data('deck-id');
        var deckName = $(e.relatedTarget).data('deck-name');
        $('#deck__modal__edit--title').text(deckName);
        $('#deck__modal__edit--name').val(deckName);
        $('#deck__modal__edit--id').val(deckId);
    });
    $('#deck__modal__edit--form').submit(function(e){
        e.preventDefault();
        $app.apisync.patch("/deck/" + $('#deck__modal__edit--id').val(),{
            name: $('#deck__modal__edit--name').val().trim()
        }).then(()=>{
            $('#deck__modal__edit').modal('hide');
            if ($('#deck__list').length){
                $('#deck__list').bootstrapTable('refresh',{
                    silent: true
                });
            }
        });
    });
</script>