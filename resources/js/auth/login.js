import { Selector, Form } from "../main";
import { LoginValidator } from "../validators/LoginValidator.js";

const form = new Form(Selector.id('form'));
const validator = new LoginValidator();

validator.liveValidation(form.getForm(), validator);

form.getForm().addEventListener('submit', e => {
    if (!validator.validate()) {
        e.preventDefault();
    }
});