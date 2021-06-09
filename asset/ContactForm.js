/**
 * @author McFly the Kid
 */
const ContactForm = ((e, AppApi, Constant, FlashMessage)=>{

    e.submitContact = ({ firstName, lastName, subject, email, content }) => {
        return AppApi.sync.post("/crud/contact/create", { firstName, lastName, subject, email, content });
    };

    e.submitSubscribe = ({ email }) => {
        return AppApi.sync.post("/crud/subscribe/create", { email });
    };

    return e;
})({}, AppApi, Constant, FlashMessage);