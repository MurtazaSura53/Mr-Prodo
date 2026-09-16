export class Selector {
    static id(id) {
        return document.getElementById(id);
    }
    static qs(query) {
        return document.querySelector(query);
    }
    static qsa(query) {
        return document.querySelectorAll(query);
    }
}



/*
 * Element Usage:
 *
 * Create a new DOM element:
 *
 *     const button = Element.make('button');
 *
 * Add attributes:
 *
 *     Element.make('button')
 *         .attributes({
 *             class: 'blue-fill',
 *             text: 'Click Me',
 *             id: 'submit',
 *             dataset: {
 *                 action: 'submit'
 *             },
 *             onClick: () => {
 *                 console.log('Clicked');
 *             }
 *         })
 *         .create();
 *
 * Append child elements:
 *
 *     const section = Element.make('section')
 *         .children([
 *             Element.make('h2')
 *                 .attributes({ text: 'Products' })
 *                 .create(),
 *
 *             Element.make('button')
 *                 .attributes({ text: 'Add Product' })
 *                 .create()
 *         ])
 *         .create();
 *
 * Append to an existing parent:
 *
 *     Element.make('div', container)
 *         .attributes({ class: 'box' })
 *         .create();
 *
 * Methods:
 *
 *     make(element, parent)
 *         → Creates the element.
 *
 *     attributes({...})
 *         → Sets attributes, properties, dataset and events.
 *
 *     children([...])
 *         → Adds text, numbers or DOM nodes as children.
 *
 *     create()
 *         → Appends the element to parent (if provided)
 *           and returns the actual DOM element.
 *
 * Important:
 *
 *     make() / attributes() / children()
 *         → return Element instance
 *
 *     create()
 *         → returns actual DOM element
 *
 * Common pattern:
 *
 *     Element.make('div', parent)
 *         .attributes({...})
 *         .children([...])
 *         .create();
 */
export class Element {
    static make(element, parent = null) {
        const instance = new Element();
        instance.parent = parent;
        instance.element = document.createElement(element);
        return instance;
    }
    attributes(attributes) {
        Object.keys(attributes).forEach(key => {
            const value = attributes[key];
            switch (key) {
                case "class": this.element.className = value; break;
                case "text": this.element.textContent = value; break;
                case "html": this.element.innerHTML = value; break;
                default:
                    if (key === "dataset") {
                        Object.keys(value).forEach(dataKey => {
                            const dataValue = value[dataKey];
                            this.element.dataset[dataKey] = dataValue;
                        });
                    } else if (key.startsWith("on")) {
                        const event = key.slice(2).toLowerCase();
                        this.element.addEventListener(event, value);
                    } else if (key === "list") {
                        this.element.setAttribute("list", value);
                    } else if (key in this.element) {
                        this.element[key] = value;
                    } else {
                        this.element.setAttribute(key, value);
                    }
            }
        });
        return this;
    }

    children(children) {
        // console.log(children);
        children.forEach(child => {
            if (typeof child === "string" || typeof child === "number") {
                this.element.appendChild(document.createTextNode(child));
            } else if (child instanceof Node) {
                this.element.appendChild(child);
            }
        });
        return this;
    }

    create() {
        if (this.parent instanceof Node) {
            this.parent.append(this.element);
        }
        return this.element;
    }
}

export class Paginate {
    constructor(url, lastPage, startFrom = 1) {
        this.lastPage = lastPage;
        this.url = url;
        this.page = startFrom;
    }
    async next(closure) {
        if (this.page >= this.lastPage) return;

        const response = await fetch(`${this.url}?page=${this.page + 1}`, {
            headers: {
                'Accept': 'application/json'
            }
        });
        const data = await response.json();

        this.page = this.page + 1;
        return closure(data);
    }
    async previous(closure) {
        if (this.page <= 1) return;

        const response = await fetch(`${this.url}?page=${this.page - 1}`, {
            headers: {
                'Accept': 'application/json'
            }
        });
        const data = await response.json();

        this.page = this.page - 1;
        return closure(data);
    }
    async current(closure) {
        const response = await fetch(`${this.url}?page=${this.page}`, {
            headers: {
                'Accept': 'application/json'
            }
        });
        const data = await response.json();
        return closure(data);
    }
}

export class Request {

    constructor(request) {
        this.url = request.url;
        this.method = request.method ?? "GET";
        this.headers = request.headers ?? {};
        this.body = request.body ?? {};

        this.response = {};
    }

    async send(closure = null) {

        const response = await fetch(this.url, {
            method: this.method,
            headers: this.headers,
            body: (this.body && Object.keys(this.body).length > 0)
                ? this.body
                : null,
        });

        // this.response = await response.json();
        if (response.status !== 204)
            this.response.data = await response.json();
        this.response.ok = response.ok;
        this.response.status = response.status;

        if (closure) {
            return closure(this.response);
        }
        return this;
    }
}



/*
 * Validator Usage:
 *
 * 1. Extend this class:
 *
 *      class ProductValidator extends Validator {}
 *
 * 2. Define validation rules in constraints():
 *
 *      constraints() {
 *          return {
 *              name: ['required', 'alphas', { max: 255 }],
 *              price: ['required', 'number', { minValue: 1 }]
 *          };
 *      }
 *
 * 3. Define error messages in messages():
 *
 *      messages() {
 *          return {
 *              name: {
 *                  required: 'This field is required',
 *                  alphas: 'Only alphabets and spaces allowed',
 *                  max: 'Maximum 255 characters allowed'
 *              },
 *              price: {
 *                  required: 'Price is required',
 *                  number: 'Price must be a number',
 *                  minValue: 'Price must be at least 1'
 *              }
 *          };
 *      }
 *
 * 4. Create the validator:
 *
 *      const validator = new ProductValidator();
 *
 * 5. Validate the entire form:
 *
 *      if (!validator.validate()) {
 *          console.log(validator.errors);
 *          return;
 *      }
 *
 * 6. Validate a single field:
 *
 *      validator.validateOnly(field);
 *
 *      // Returns:
 *      // true  → valid
 *      // false → invalid
 *
 * Notes:
 * - constraints() defines WHAT rules to apply.
 * - messages() defines WHAT message to show when a rule fails.
 * - validate() validates the entire form.
 * - validateOnly(field) validates one field.
 * - validator.errors contains the current validation errors.
 * - Server-side validation is still required.
 */
export class Validator {
    constructor() {
        this.errors = [];
        this.validated = [];
    }
    validate() {
        this.errors = [];
        this.validated = [];

        const constraints = this.constraints();
        const messages = this.messages();

        Object.keys(constraints).forEach(inputName => {

            const field = Selector.qs(`input[name="${inputName}"]`);
            if (!field) return;

            let valid = true;
            let errorObj = {};
            constraints[inputName].forEach(constraint => {
                if (!valid) return;

                if (typeof constraint === "string") {
                    if (constraint === 'optional') {
                        if (this.optional(field)) {
                            return;
                        }
                    }
                    else if (this[constraint](field)) {
                        valid = false;

                        errorObj.field = field;
                        errorObj.constraint = constraint;
                        errorObj.message = messages[inputName][constraint];
                    }
                } else if (typeof constraint === "object") {
                    Object.keys(constraint).forEach(key => {
                        if (!valid && this[key]) return;

                        if (this[key](field, constraint[key])) {
                            valid = false;

                            errorObj.field = field;
                            errorObj.constraint = key;
                            errorObj.message = messages[inputName][key];
                        }
                    })
                }
            })

            if (valid) {
                this.validated.push(field);
            } else {
                this.errors.push(errorObj);
            }
        })
        return this.errors.length === 0;
    }

    validateOnly(field) {
        const constraints = this.constraints();
        const messages = this.messages();

        this.errors = this.errors.filter(
            error => error.field !== field
        );

        for (const constraint of constraints[field.name]) {
            if (typeof constraint === "string") {
                if (constraint === 'optional') {
                    if (this.optional(field)) {
                        return true;
                    }
                    continue;
                }
                else if (this[constraint](field)) {
                    this.errors.push({
                        field: field,
                        constraint: constraint,
                        message: messages[field.name][constraint]
                    });
                    return false;
                }
            } else if (typeof constraint === "object") {
                for (const key of Object.keys(constraint)) {
                    if (this[key](field, constraint[key])) {
                        this.errors.push({
                            field: field,
                            constraint: key,
                            message: messages[field.name][key]
                        });
                        return false;
                    }
                }
            }
        }
        return true;
    }

    /*
    * LIVE VALIDATION METHOD:
    * for using this method you must have a label/p tag with id like:
    * id format: inputName_error this is the format where 'inputName' is name
    * of your input following '_error' means this is the error label of this input
    * so before using this method make sure u have _error labels for each input
    * fields.
    * 
    * Example usage:
    *   const validator = new ChildClassValidator();
    *   const form = document.querySelector('form');
    *   validator.liveValidation(form, validator);
    * 
    * That's it. Live Validation on🔥
    */
    liveValidation(form, validatorInstance) {
        const fields = form.querySelectorAll('input, textarea');
        fields.forEach(field => {
            field.addEventListener('blur', () => {
                let errorLabel;
                if (!validatorInstance.validateOnly(field)) {
                    const error = validatorInstance.errors.filter(
                        error => error.field === field
                    )[0];
                    errorLabel = Selector.id(`${field.name}_error`);
                    errorLabel.style.visibility = "visible";
                    errorLabel.textContent = error.message;
                    field.style.border = "1px solid red";
                } else {
                    errorLabel = Selector.id(`${field.name}_error`);
                    errorLabel.style.visibility = "hidden";
                    errorLabel.textContent = "";
                    field.style.border = "1px solid lightgray";
                }
            })
        });
    }

    //Methods to overridden by child classes
    constraints() {
        return {};
    }
    messages() {
        return {};
    }

    //Methods use for validation
    //Validation Rules
    optional(field) { return field.value.trim() === ""; } //Filed that is optional
    required(field) { return (field.value === "") } //Field must contain value
    integer(field) { return !/^-?\d+$/.test(field.value); } //Number without decimals '.'
    number(field) { return !/^-?\d+(\.\d+)?$/.test(field.value); } //Number with or without decimals
    decimal(field) { return !(/^-?\d+\.\d+$/.test(field.value)); } //Number with decimal
    alpha(field) { return !(/^[a-zA-Z]+$/.test(field.value)); } //Only Alphabets without spaces
    alphaNumeric(field) { return !(/^[a-zA-Z\d]+$/.test(field.value)); } //Only Numbers and Alphabets without spaces
    alphas(field) { return !(/^[a-zA-Z\s]+$/).test(field.value) }; //Only Alphabets and spaces
    alphaNumerics(field) { return !(/^[a-zA-Z\s\d]+$/).test(field.value) }; //Only Numbers, Alphabets and spaces
    max(field, length) { return field.value.length > length; } //Maximum character
    min(field, length) { return field.value.length < length; } //Minimum character
    minValue(field, value) { return Number(field.value) < value; } //Minimum value
    maxValue(field, value) { return Number(field.value) > value; } //Maximum value
    email(field) { return !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value); } //Validate Email
    url(field) {
        try {
            new URL(field.value);
            return false;
        } catch {
            return true;
        }
    } //Validate Url
    positive(field) { return Number(field.value) <= 0; } //Only positive numbers
    negative(field) { return Number(field.value) > 0; } //Only negative numbers
    pattern(field, pattern) { return !pattern.test(field.value); } //Custom regex patterns
    between(field, range) {
        const min = range[0];
        const max = range[1];
        if (field.value < min || field.value > max) {
            return true;
        }
    }

    same(field, value) { return field.value !== value; } // Compare two values

    gt(field, value) { return Number(field.value) <= Number(value); } // Greater than value

    gte(field, value) { return Number(field.value) < Number(value); } // Greater than or equal to value

    st(field, value) { return Number(field.value) >= Number(value); } // Smaller than value

    ste(field, value) { return Number(field.value) > Number(value); } // Smaller than or equal to value

    sameField(field, otherField) {
        return field.value !== Selector.qs(`input[name="${otherField}"]`).value;
    } // Compare two field values

    gtField(field, otherField) {
        return Number(field.value) <= Number(Selector.qs(`input[name="${otherField}"]`).value);
    } // Greater than field value

    gteField(field, otherField) {
        return Number(field.value) < Number(Selector.qs(`input[name="${otherField}"]`).value);
    } // Greater than or equal to field value

    stField(field, otherField) {
        return Number(field.value) >= Number(Selector.qs(`input[name="${otherField}"]`).value);
    } // Smaller than field value

    steField(field, otherField) {
        return Number(field.value) > Number(Selector.qs(`input[name="${otherField}"]`).value);
    } // Smaller than or equal to field value
}


export class Form {
    constructor(form) {
        const inputs = form.querySelectorAll('input, textarea, select');
        this.original = {};
        this.allInputs = [];
        this.form = form;
        inputs.forEach(input => {
            this[input.name] = input;
            this.original[input.name] = input.value;
            this.allInputs.push(input);
        });
    }
    getChangedFields() {
        const changedFields = {};
        for (const ogKey of Object.keys(this.original)) {
            if (this[ogKey].value !== this.original[ogKey]) {
                changedFields[ogKey] = this[ogKey];
            }
        }
        return changedFields;
    }
    getFormData() {
        return new FormData(this.form);
    }
    getForm() {
        return this.form;
    }
    getJson() {
        const obj = {};
        console.log(this.allInputs);
        this.allInputs.forEach(input => {
            obj[input.name] = input.value;
        });
        return JSON.stringify(obj);
    }
}
export function createInput(label, name, attributes = {}) {
    return Element.make('div').attributes({ class: 'input-wrapper' }).children([
        Element.make('label').attributes({ for: name, text: label }).create(),
        Element.make('input').attributes({ name: name, ...attributes }).create(),
        Element.make('span').attributes({ id: `${name}_error` }).create(),
    ]).create();
}

export class Toast {

    static icons = {
        success: 'fa-circle-check',
        error: 'fa-circle-xmark',
        warning: 'fa-triangle-exclamation',
        info: 'fa-circle-info'
    };

    static show(message, type = 'success', duration = 3000) {

        const container = document.querySelector('#toast-container');

        const icon = this.icons[type] ?? this.icons.info;

        const toast = document.createElement('div');

        toast.className = `toast ${type}`;

        toast.innerHTML = `
            <i class="fa-solid ${icon}"></i>

            <span class="toast-message">
                ${message}
            </span>

            <button class="toast-close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        `;

        container.appendChild(toast);

        const close = () => {

            if (toast.classList.contains('hide')) {
                return;
            }

            toast.classList.add('hide');

            toast.addEventListener('animationend', () => {
                toast.remove();
            });
        };

        toast
            .querySelector('.toast-close')
            .addEventListener('click', close);

        setTimeout(close, duration);
    }
}

export class Alert {

    static show(message, onOk, okBtnText = "OK") {

        const container = document.querySelector('#alert-container');

        container.innerHTML = `
            <div class="alert-box">

                <div class="alert-message">
                    ${message}
                </div>

                <div class="alert-actions">

                    <button class="alert-btn alert-cancel">
                        Cancel
                    </button>

                    <button class="alert-btn alert-ok">
                        ${okBtnText}
                    </button>

                </div>

            </div>
        `;

        container.classList.add('active');

        const cancelBtn = container.querySelector('.alert-cancel');
        const okBtn = container.querySelector('.alert-ok');

        const close = () => {
            container.classList.remove('active');
            container.innerHTML = '';
        };

        cancelBtn.addEventListener('click', close);

        okBtn.addEventListener('click', () => {

            close();

            if (typeof onOk === 'function') {
                return onOk();
            }

        });
    }
}
export class Inform {

    static show(message, onOk, okBtnText = "OK") {

        const container = document.querySelector('#inform-container');

        container.innerHTML = `
            <div class="inform-box">

                <div class="inform-message">
                    ${message}
                </div>

                <div class="inform-actions">

                    <button class="inform-btn inform-ok">
                        ${okBtnText}
                    </button>

                </div>

            </div>
        `;

        container.classList.add('active');
        const okBtn = container.querySelector('.inform-ok');

        const close = () => {
            container.classList.remove('active');
            container.innerHTML = '';
        };

        okBtn.addEventListener('click', () => {

            close();

            if (typeof onOk === 'function') {
                return onOk();
            }

        });
    }
}
export class Prompt {
    static show(fieldsData, onOk, okBtnText = "OK") {

        const container = Selector.id("prompt-container");
        const inputWrappers = [];

        fieldsData.forEach(field => {
            const inputWrapper = Element.make('div')
                .attributes({ class: "input-wrapper" })
                .children([
                    Element.make('label')
                        .attributes({
                            text: field.label,
                            for: field.name,
                        }).create(),

                    Element.make('input')
                        .attributes({
                            type: "text",
                            name: field.name
                        }).create(),

                    Element.make('span')
                        .attributes({
                            id: `${field.name}_error`,
                        }).create(),
                ]).create();
            inputWrappers.push(inputWrapper);
        })

        const close = () => {
            container.classList.remove('active');
            container.innerHTML = '';
        };
        const generatedFields = () => {
            const fields = [];
            fieldsData.forEach(field => {
                fields.push(Selector.qs(`input[name="${field.name}"]`));
            });
            return fields;
        }

        const promptBox = Element.make('form', container)
            .attributes({ class: "prompt-box" })
            .children([...inputWrappers, ...[
                Element.make("div")
                    .attributes({ class: "prompt-actions" })
                    .children([
                        Element.make("button")
                            .attributes({
                                type: "button",
                                class: "prompt-btn prompt-cancel",
                                text: "Cancel",
                                onClick: close
                            }).create(),

                        Element.make("button")
                            .attributes({
                                type: "button",
                                class: "prompt-btn prompt-ok",
                                text: okBtnText,
                                onClick: () => {
                                    if (typeof onOk === 'function') {
                                        onOk(generatedFields(), promptBox);
                                        close();
                                    }
                                }
                            }).create(),
                    ]).create(),
            ]]).create();
        container.classList.add('active');
    }
}