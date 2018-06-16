/**
 * @author McFly the Kid
 */
var Dialog = ((e, BootstrapDialog)=>{

    e.confirm = (msg, callback, finallyCallback)=>{
        var dialog = BootstrapDialog.confirm({
            title: 'WARNING',
            message: msg,
            type: BootstrapDialog.TYPE_WARNING, // <-- Default value is BootstrapDialog.TYPE_PRIMARY
            closable: true, // <-- Default value is false
            draggable: true, // <-- Default value is false
            btnOKClass: 'btn-warning', // <-- If you didn't specify it, dialog type will be used,
            callback: function(result) {
                // result will be true if button was click, while it will be false if users close the dialog directly.
                if(result) {
                    if (callback) callback();
                }
            }
        });
        if (finallyCallback) dialog.options.onhidden =  finallyCallback;
    };

    return e;
})({}, BootstrapDialog);