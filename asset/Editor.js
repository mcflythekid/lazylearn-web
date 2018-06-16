/**
 * @author McFly the Kid
 */
var Editor = ((e)=>{

    e.isBlank = (quill)=>{
        var html = quill.root.innerHTML;
        if (html === '<p><br></p>' ||
            (!quill.getText().trim() && !html.includes('img'))
        ) {
            return true;
        }
        return false;
    };

    e.getHtml = (quill)=>{
        return quill.root.innerHTML;
    };

    e.setHtml = (quill, html)=>{
        quill.root.innerHTML = html;
    };

    e.clear = (quill)=>{
        quill.setText('\n');
    };

    e.generateQuillConfig = (placeHolder, previousElementCallback, nextElementCallback)=>{
        return {
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{align: ''}, {align: 'center'}, {align: 'right'}, {align: 'justify'}],
                    ['link', 'image', 'code-block']
                ],
                imageResize: {},
                magicUrl: true,
                keyboard: {
                    bindings: {
                        tab: {
                            key: 9,
                            handler: function (range, context) {
                                nextElementCallback();
                            }
                        },
                        shiftTab: {
                            key: 9,
                            shiftKey: true,
                            handler: function (range, context) {
                                previousElementCallback();
                            }
                        }
                    }
                },
            },
            placeholder: placeHolder,
            theme: 'snow'
        };
    };

    return e;
})({});