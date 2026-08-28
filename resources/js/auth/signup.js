import { Selector, Form } from "../main";
import { SignupValidator } from "../validators/SignupValidator.js";

const form = new Form(Selector.id('form'));
const validator = new SignupValidator();

validator.liveValidation(form.getForm(), validator);

form.getForm().addEventListener('submit', e => {
    if (!validator.validate()) {
        e.preventDefault();
    }
})