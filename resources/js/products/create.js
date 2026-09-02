import { Selector, Form, Request, Inform, Toast } from '../main';
import { StoreProductValidator } from '../validators/StoreProductValidator';

const storeProductBtn = Selector.id("storeProductBtn");
const form = new Form(Selector.id("form"));
// const csrfToken = Selector.qs("meta[name='csrf-token']").content;
const validator = new StoreProductValidator();

validator.liveValidation(form.getForm(), validator);

storeProductBtn.addEventListener('click', async () => {
    if (!validator.validate()) return;

    const request = new Request({
        url: "/products",
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
        },
        body: form.getJson(),
    });
    request.send(async response => {
        switch (response.status) {
            case 201:
                Inform.show(response.data.message, () => {
                    window.location.href = "/products";
                });
                break;
            case 422:
                resetFieldsStyle(form.allInputs);
                displayErrors(response.data.errors);
                Toast.show("Invalid Data", "error");
                break;
            default:
                Toast.show("Server Error", "error");
        }
    })
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