/**
 * @author McFly the Kid
 */
var Storage = ((e, jQuery)=>{
    e.get = (key, defaultVal)=>{
        return jQuery.jStorage.get(key, defaultVal);
    };
    e.set = (key, val)=>{
        jQuery.jStorage.set(key, val);
    };
    e.delete = (key)=>{
        jQuery.jStorage.deleteKey(key);
    };
    e.flush = ()=>{
        jQuery.jStorage.flush();
    };
    return e;
})({}, $);