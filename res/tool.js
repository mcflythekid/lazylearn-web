var $tool = ((e)=>{

    e.quillIsBlank = (quill)=>{
        var html = quill.root.innerHTML;
        if (html === '<p><br></p>' ||
            (!quill.getText().trim() && !html.includes('img'))
        ) {
            return true;
        }
        return false;
    };

    e.quillGetHtml=(quill)=>{
        return quill.root.innerHTML;
    };

    e.quillSetHtml=(quill, html)=>{
        quill.root.innerHTML = html;
    };

    e.quillClear=(quill)=>{
        e.quillSetHtml(quill, '<p><br></p>');
    }

    e.confirm =  function(msg, cb){
        BootstrapDialog.confirm({
            title: 'Are you sure',
            message: msg,
            type: BootstrapDialog.TYPE_WARNING, // <-- Default value is BootstrapDialog.TYPE_PRIMARY
            closable: true, // <-- Default value is false
            draggable: true, // <-- Default value is false
            btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
            btnOKLabel: 'Confirm', // <-- Default value is 'OK',
            btnOKClass: 'btn-warning', // <-- If you didn't specify it, dialog type will be used,
            callback: function(result) {
                // result will be true if button was click, while it will be false if users close the dialog directly.
                if(result) {
                    cb();
                }else {
                }
            }
        });
    };

    // ui ----------------------------------------------------------
    e.flash = (type,content)=>{
        if($('#wrapperShowHide').length==0){
            var wrapper = '<div id="wrapperShowHide" style="width: 300px;top:5%; left:69%;position: fixed;z-index:10000; font-size:13px"></div>';
            $('body').before(wrapper);
        }
        var count=0;
        while($('#showHideMessage'+count).length>0){
            count++;
        }
        var messageType="alert-success";
        var header="";
        switch(type) {
            case 0:
                messageType="alert-danger";
                header="";
                break;
            case 1:
                messageType="alert-success";
                header="";
                break;
            case 2:
                messageType="alert-warning";
                header="";
                break;
        }
        var content = '<div class="alert '+messageType+'" id="showHideMessage'+count+'" style="width: 400px;"><button type="button" class="close" data-dismiss="alert">x</button><strong>'+header+'</strong>'+content+'</div>';
        $("#wrapperShowHide").append(content);

        $("#showHideMessage"+count).fadeTo(5000, 1500).fadeOut(1000, function(){
            $(this).alert('close');
            if($('#wrapperShowHide > div').length==0){
                $('#wrapperShowHide').remove();
            }
        });
    };
    e.lock= function(isDisable = true, id = "body"){
        var genId=id +"loading";
        var prefix="";
        var position="fixed";
        var measure="100%";
        if(id != "body"){
            prefix="#";
            position="absolute";
            measure="100%";
        }
        if(isDisable === true){
            var divloading = '<div class="screenlocker" id="'+genId+'" style="position:'+position+'; z-index:100000; top:0;';
            divloading += 'left:0;height:'+measure+';width: '+measure+'; background:rgba( 255, 255, 255, .8 ) ';
            divloading +='url(/res/img/ajax-loader.gif) 50% 50% no-repeat;"></div>';
            $(prefix+id).append(divloading);
        }else{
            $(".screenlocker").remove();
        }
    };
    e.unlock= function(){
        e.lock(false);
    };

    e.getData = (key)=>{
        return JSON.parse(localStorage.getItem(key));
    };
    e.setData = (key, val)=>{
        if (!val){
            localStorage.removeItem(key);
        } else {
            localStorage.setItem(key, JSON.stringify(val));
        }
    };
    e.removeData = (key)=>{
        localStorage.removeItem(key);
    };

    // url -------------------------------------
    e.param = function(name) {
        return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [null, ''])[1].replace(/\+/g, '%20')) || null;
    };

    e.info = (msg, cb)=>{
        BootstrapDialog.show({
            closable: true, // <-- Default value is false
            draggable: true, // <-- Default value is false
            message: msg,
            onhide: function(dialogRef){
                if (cb) cb();
            },
            buttons: [{
                label: 'Close',
                action: function(dialogRef) {
                    dialogRef.close();
                }
            }]
        });
    };

    return e;
})({});