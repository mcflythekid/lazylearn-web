/**
 * @author McFly the Kid
 */
var EncodedFile = ((e, FlashMessage)=>{

    // Read one file
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

    e.readAll = ($el)=>{
        const readOne = (file) => {
            const deferred = $.Deferred();
            const fileReader = new FileReader();
            fileReader.onload = function(event) {
                const data = event.target.result;
                if (data == "data:"){
                    deferred.resolve(undefined);
                }
                deferred.resolve({
                    ext: file.name.replace(/^.*\./, ''),
                    content: data.substring(data.indexOf(';base64,') + 8)
                });
            };
            fileReader.readAsDataURL(file);
            return deferred.promise();
        };

        const files = $el.get(0).files;
        var results = [];
        if (files) {
            for(var i = 0; i < files.length; i++){
                results.push(readOne(files[i]));
            }
        }
        return Promise.all(results);
    };

    return e;
})({}, FlashMessage);