/**
 * @author McFly the Kid
 */
var EncodedFile = ((e, FlashMessage)=>{

    e.read = ($el)=>{
        var deferred = $.Deferred();
        var files = $el.get(0).files;
        if (files && files[0]) {
            var fr= new FileReader();
            fr.onload = function(e) {
                var data = e.target.result;
                if (data == "data:"){
                    $el.get(0).value = '';
                    deferred.resolve(undefined);
                }
                deferred.resolve({
                    ext: files[0].name.replace(/^.*\./, ''),
                    content: data.substring(data.indexOf(';base64,') + 8)
                });
            };
            fr.readAsDataURL( files[0] );
        } else {
            deferred.resolve(undefined);
        }
        return deferred.promise();
    };

    return e;
})({}, FlashMessage);