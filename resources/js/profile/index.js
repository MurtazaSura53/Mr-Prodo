import { Selector, Form, Request, Toast } from '../main.js';
import { UpdateProfileValidator } from '../validators/UpdateProfileValidator.js';

const validator = new UpdateProfileValidator();
const form = new Form(Selector.id('form'));
const submitBtn = Selector.id('submitBtn');

validator.liveValidation(form.getForm(), validator);

submitBtn.addEventListener('click', async () => {
    if (!validator.validate()) return;

    const requestBody = {};

    const changedFields = form.getChangedFields();
    for (const key of Object.keys(changedFields)) {
        requestBody[key] = changedFields[key].value;
    }
    requestBody["_method"] = "PATCH";
    requestBody["_token"] = form._token.value;

    const request = new Request({
        url: "/profile",
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
        },
        body: JSON.stringify(requestBody),
    });
    request.send(response => {
        switch (response.status) {
            case 200:
                Toast.show(response.data.message);
                break;
            case 422:
                resetFieldsStyle(form.allInputs);
                const errors = response.data.errors;
                for (const key of Object.keys(errors)) {
                    const errorLabel = Selector.id(`${key}_error`);
                    if (!errorLabel) continue;
                    form[key].style.border = "1px solid red";
                    errorLabel.style.visibility = "visible";
                    errorLabel.innerHTML = errors[key];
                }
                Toast.show("Error!", "error");
                break;
            default:
                Toast.show("Server error!", "error");
        }
    });
});
function resetFieldsStyle(fields) {
    console.log(fields);
    fields.forEach(field => {
        const errorLabel = Selector.id(`${field.name}_error`);
        if (!errorLabel) return;
        field.style.border = "1px solid lightgray";
        errorLabel.innerHTML = "";
        errorLabel.style.visibility = "hidden";
    });
}

