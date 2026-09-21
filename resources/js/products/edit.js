import { Selector, Form, Request, Inform, Toast } from '../main';
import { UpdateProductValidator } from '../validators/UpdateProductValidator';

Selector.id('productsLink').classList.add('active');

const productId = Selector.qs("meta[name='product']").content;
const editProductBtn = Selector.id("editProductBtn");
const form = new Form(Selector.id("form"));
const validator = new UpdateProductValidator();

validator.liveValidation(form.getForm(), validator);

editProductBtn.addEventListener('click', async () => {
    if (!validator.validate()) return;

    const changedFields = form.getChangedFields();
    if (!changedFields || Object.keys(changedFields).length === 0) {
        Toast.show("Nothing to change!", "warning");
        return;
    }

    const requestBody = {};
    for (const inputName of Object.keys(changedFields)) {
        requestBody[inputName] = changedFields[inputName].value;
    }
    requestBody['_token'] = form._token.value;

    const request = new Request({
        url: `/products/${productId}`,
        method: "PATCH",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
        },
        body: JSON.stringify(requestBody),
    });
    request.send(async response => {
        switch (response.status) {
            case 200:
                Inform.show(response.data.message, () => {
                    window.location.href = "/products";
                });
                break;
            case 422:
                resetFieldsStyle(form.allInputs);
                displayErrors(response.data.errors);
                Toast.show("Invalid Data", "error");
                break;
            case 403:
                Toast.show("Unauthorized", "error");
                break;
            default:
                Toast.show("Server Error", "error");
        }
    })
});
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