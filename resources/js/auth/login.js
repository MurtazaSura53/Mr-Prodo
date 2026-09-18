import { Selector, Form, Request, Toast } from "../main";
import { LoginValidator } from "../validators/LoginValidator.js";

const storeLoginBtn = Selector.id("storeLoginBtn");
const form = new Form(Selector.id('form'));
const validator = new LoginValidator();

validator.liveValidation(form.getForm(), validator);

storeLoginBtn.addEventListener('click', e => {
    if (!validator.validate()) return;

    const request = new Request({
        url: '/login',
        method: 'POST',
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
        },
        body: form.getJson(),
    });
    request.send(response => {
        switch (response.status) {
            case 201:
                window.location.href = "/";
                break;
            case 422:
                resetFieldsStyle(form.allInputs);
                displayErrors(response.data.errors);
                Toast.show("Invalid Data", "error");
                break;
            case 401:
                resetFieldsStyle(form.allInputs);
                displayErrors(response.data.errors);
                Toast.show(response.data.message, "error");
                break;
            default:
                Toast.show("Server Error", "error");
        }
    });
})
function resetFieldsStyle(fields) {
    fields.forEach(field => {
        const errorLabel = Selector.id(`${field.name}_error`);
        if (!errorLabel) return;
        field.style.border = "1px solid lightgray";
        errorLabel.innerHTML = "";
        errorLabel.style.visibility = "hidden";
    });
}
function displayErrors(errors) {
    for (const key of Object.keys(errors)) {
        const errorLabel = Selector.id(`${key}_error`);
        if (!errorLabel) continue;
        form[key].style.border = "1px solid red";
        errorLabel.style.visibility = "visible";
        errorLabel.innerHTML = errors[key];
    }
}