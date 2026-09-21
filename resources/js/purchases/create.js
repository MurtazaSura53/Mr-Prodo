import { Selector, Element, Toast, Inform, Request, createInput } from "../main";
Selector.id('purchasesLink').classList.add('active');

const form = Selector.id("form");
const productList = Selector.id("productList");
const addPurchaseItemBtn = Selector.id("addPurchaseItemBtn");

const purchaseItems = Selector.id("purchaseItems");
const totalAmount = Selector.id("totalAmount");
let purchaseItemsCount = 0;

const supplierName = Selector.id("supplierName");
const purchaseDate = Selector.id("purchaseDate");
const storePurchaseBtn = Selector.id("storePurchaseBtn");

addPurchaseItemBtn.addEventListener('click', renderPurchaseItem);
function renderPurchaseItem() {

    const productInput = Element.make('div').attributes({
        class: 'input-wrapper product-name'
    }).children([
        Element.make('label').attributes({ for: 'product', text: 'Product' }).create(),
        Element.make('input').attributes({
            type: 'text',
            name: `purchase_items[${purchaseItemsCount}]product_id`,
            list: 'productList',
            autocomplete: 'off',
            required: 'required',
            dataset: { productId: '' },
            onInput: fillData
        }).create(),
        Element.make('span').attributes({ id: `purchase_items[${purchaseItemsCount}]product_id_error` }).create(),
    ]).create();

    const unit = Element.make('span').attributes({ class: "unit", text: '--' }).create();
    const unitInput = Element.make('input').attributes({
        type: "hidden",
        value: "",
        name: `purchase_items[${purchaseItemsCount}]unit`,
        dataset: { unit: '' },
    }).create();
    const qtyInput = Element.make('div').attributes({ class: 'input-wrapper' }).children([
        Element.make('label').attributes({ for: 'qty', text: 'Qty' }).create(),
        Element.make('div').attributes({ class: 'flex ai-end gap-thin' }).children([
            Element.make('input').attributes({
                type: "number",
                name: `purchase_items[${purchaseItemsCount}]qty`,
                value: "0",
                required: 'required',
                dataset: { qty: '' },
                onInput: calculateSubtotal,
            }).create(),
            unit
        ]).create(),
        Element.make('span').attributes({ id: `purchase_items[${purchaseItemsCount}]qty_error` }).create(),
    ]).create();

    const priceInput = createInput('Price', `purchase_items[${purchaseItemsCount}]price`, {
        type: 'number',
        value: 0,
        required: 'required',
        dataset: { price: '' },
        onInput: calculateSubtotal,
    });

    const subtotalInput = createInput('Subtotal', `purchase_items[${purchaseItemsCount}]subtotal`, {
        type: 'number',
        class: 'subtotal',
        value: 0,
        required: 'required',
        readonly: 'true',
        dataset: { subtotal: '' },
    });

    const removeBtn = Element.make('button').attributes({
        type: 'button',
        class: 'btn-icon x-icon',
        html: '<i class="fa-solid fa-xmark"></i>',
        onClick: removePurchaseItem,
    }).create();

    const removeBtnWrapper = Element.make('div').attributes({ class: 'x-icon-wrapper' })
        .children([removeBtn]).create();

    const purchaseItem = Element.make('div', purchaseItems).attributes({
        class: "purchase-item-row",
        dataset: {
            count: purchaseItemsCount,
        }
    }).children([
        productInput, qtyInput, unitInput, priceInput, subtotalInput, removeBtnWrapper,
    ]).create();

    function fillData() {
        const productField = productInput.querySelector(`input[data-product-id]`);
        const priceField = priceInput.querySelector(`input[data-price]`);
        const qtyField = qtyInput.querySelector(`input[data-qty]`);

        const productName = productField.value;

        const product = productList.querySelector(`option[value='${productName}']`);
        productField.dataset.id = product?.dataset.id ?? '';
        unit.textContent = product?.dataset.unit ?? '--';
        qtyField.value = 1;
        priceField.value = product?.dataset.purchasePrice ?? 0;
        calculateSubtotal();
    }
    function calculateSubtotal() {
        if (!purchaseItem) return;
        const priceField = priceInput.querySelector(`input[data-price]`);
        const qtyField = qtyInput.querySelector(`input[data-qty]`);
        const subtotalField = subtotalInput.querySelector(`input[data-subtotal]`);

        subtotalField.value = (Number(priceField?.value ?? 0) * Number(qtyField?.value ?? 0)).toFixed(2);
        calculateTotal();
    }
    function removePurchaseItem() {
        purchaseItem.remove();
        calculateTotal();
        resetInputCounts();
    }
    purchaseItemsCount++;
}
function calculateTotal() {
    let grandTotal = 0;
    const subtotals = purchaseItems.querySelectorAll("input[data-subtotal]");
    subtotals?.forEach(subtotal => {
        grandTotal += Number(subtotal?.value ?? 0);
    });
    totalAmount.textContent = `₹${grandTotal.toFixed(2)}`;
}
function resetInputCounts() {
    purchaseItemsCount = 0;
    const purchaseItemRows = purchaseItems.querySelectorAll('.purchase-item-row');

    purchaseItemRows.forEach(purchaseItemRow => {
        const productId = purchaseItemRow.querySelector(`input[data-product-id]`);
        const qty = purchaseItemRow.querySelector(`input[data-qty]`);
        const price = purchaseItemRow.querySelector(`input[data-price]`);
        const unit = purchaseItemRow.querySelector(`input[data-unit]`);
        const subtotal = purchaseItemRow.querySelector(`input[data-subtotal]`);

        productId.name = `purchase_items[${purchaseItemsCount}]product_id`;
        qty.name = `purchase_items[${purchaseItemsCount}]qty`;
        price.name = `purchase_items[${purchaseItemsCount}]price`;
        unit.name = `purchase_items[${purchaseItemsCount}]unit`;
        subtotal.name = `purchase_items[${purchaseItemsCount}]subtotal`;

        purchaseItemsCount++;
    });
    console.log("input count reset to: " + purchaseItemsCount);
}

storePurchaseBtn.addEventListener('click', async () => {
    const purchaseItemRows = purchaseItems.querySelectorAll('.purchase-item-row');
    if (purchaseItemRows.length <= 0) {
        Toast.show('Add Purchase Items First', "error");
        return;
    };

    const requestBody = {};
    requestBody.supplier_name = supplierName.value;
    requestBody.purchase_date = purchaseDate.value;
    requestBody.purchase_items = [];

    purchaseItemRows.forEach(purchaseItemRow => {
        const productId = purchaseItemRow.querySelector(`input[data-product-id]`);
        const qty = purchaseItemRow.querySelector(`input[data-qty]`);
        const price = purchaseItemRow.querySelector(`input[data-price]`);

        requestBody.purchase_items.push({
            product_id: productId.dataset.id,
            qty: qty.value,
            price: price.value,
        });
    })

    const request = new Request({
        url: '/purchases',
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': Selector.qs("meta[name='csrf-token']").content,
        },
        body: JSON.stringify(requestBody),
    });
    request.send(async response => {
        switch (response.status) {
            case 201:
                resetForm();
                totalAmount.textContent = `₹0.00`;
                resetProductList();
                Toast.show(response.data.message);
                break;
            case 422:
                showValidationErrors(response.data.errors);
                Toast.show("Invalid Data", "error");
                break;
            case 403:
                resetForm();
                Toast.show("Unauthorized", "error");
                break;
            default:
                Toast.show("Internal Error", "error");
        }
    })
})
function errorKeyToName(key) {
    return key.replace(/\.(\d+)\./g, '[$1]')
        .replace(/\.([^.]+)$/, '$1');
}
function resetForm() {
    form.reset();
    purchaseItems.innerHTML = '';
}
function resetErrorFields() {
    const main = Selector.id("main");
    const inputs = main.querySelectorAll("input");
    inputs.forEach(input => {
        const errorLabel = Selector.id(`${input.name}_error`);
        if (!errorLabel) return;

        input.style.border = '1px solid lightgray';
        errorLabel.textContent = "";
        errorLabel.style.visibility = 'hidden';
    })
}
function showValidationErrors(errors) {
    resetErrorFields();
    Object.entries(errors).forEach(([key, messages]) => {

        const name = errorKeyToName(key);

        console.log(name);
        const input = document.querySelector(
            `[name="${name}"]`
        );

        if (!input) {
            return;
        }

        const errorLabel = Selector.id(`${name}_error`);
        if (!errorLabel) return;
        input.style.border = '1px solid red';
        errorLabel.style.visibility = 'visible';
        errorLabel.textContent = messages[0];
    });
}
function resetProductList() {
    const request = new Request({
        url: '/products/options',
        method: 'GET',
        headers: {
            'Accept': 'application/json',
        }
    });
    request.send(response => {
        if (response.status === 200) {
            productList.innerHTML = "";
            const products = response.data;
            products.forEach(product => {
                Element.make('option', productList).attributes({
                    value: product.name,
                    dataset: {
                        id: product.id,
                        unit: product.unit,
                        purchasePrice: product.purchase_price,
                    }
                }).create();
            });
        } else {
            Toast.show('Unable to fetch product options', 'error');
        }
    })
}