import { Selector, Element, Toast, Inform, Request, createInput } from "../main";
Selector.id('salesLink').classList.add('active');

const form = Selector.id("form");
const productList = Selector.id("productList");
const addSaleItemBtn = Selector.id("addSaleItemBtn");

const saleItems = Selector.id("saleItems");
const totalAmount = Selector.id("totalAmount");
let saleItemsCount = 0;

const customerName = Selector.id("customerName");
const customerEmail = Selector.id("customerEmail");
const customerPhone = Selector.id("customerPhone");
const saleDate = Selector.id("saleDate");
const storeSaleBtn = Selector.id("storeSaleBtn");

addSaleItemBtn.addEventListener('click', renderSaleItem);
function renderSaleItem() {

    const productInput = Element.make('div').attributes({
        class: 'input-wrapper product-name'
    }).children([
        Element.make('label').attributes({ for: 'product', text: 'Product' }).create(),
        Element.make('input').attributes({
            type: 'text',
            name: `sale_items[${saleItemsCount}]product_id`,
            list: 'productList',
            autocomplete: 'off',
            required: 'required',
            dataset: { productId: '' },
            onInput: fillData
        }).create(),
        Element.make('span').attributes({ id: `sale_items[${saleItemsCount}]product_id_error` }).create(),
    ]).create();

    const unit = Element.make('span').attributes({ class: "unit", text: '--' }).create();
    const qtyInput = Element.make('div').attributes({ class: 'input-wrapper' }).children([
        Element.make('label').attributes({ for: 'qty', text: 'Qty' }).create(),
        Element.make('div').attributes({ class: 'flex ai-end gap-thin' }).children([
            Element.make('input').attributes({
                type: "number",
                name: `sale_items[${saleItemsCount}]qty`,
                value: "0",
                required: 'required',
                dataset: { qty: '' },
                onInput: () => {
                    calculateSubtotal();
                    calculateProfit();
                },
            }).create(),
            unit
        ]).create(),
        Element.make('span').attributes({ id: `sale_items[${saleItemsCount}]qty_error` }).create(),
    ]).create();

    const priceInput = createInput('Price', `sale_items[${saleItemsCount}]price`, {
        type: 'number',
        value: 0,
        required: 'required',
        dataset: { price: '' },
        onInput: () => {
            calculateSubtotal();
            calculateProfit();
        },
    });

    const purchasePriceField = Element.make('input').attributes({
        type: 'hidden',
        value: 0,
        dataset: { purchasePrice: '' },
    });

    const profitInput = createInput('Profit', `sale_items[${saleItemsCount}]profit`, {
        type: 'number',
        class: 'profit',
        value: 0,
        required: 'required',
        readonly: 'true',
        dataset: { profit: '' },
    });

    const subtotalInput = createInput('Subtotal', `sale_items[${saleItemsCount}]subtotal`, {
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
        onClick: removeSaleItem,
    }).create();

    const removeBtnWrapper = Element.make('div').attributes({ class: 'x-icon-wrapper' })
        .children([removeBtn]).create();

    const saleItem = Element.make('div', saleItems).attributes({
        class: "sale-item-row",
        dataset: {
            count: saleItemsCount,
        }
    }).children([
        productInput, qtyInput, priceInput, purchasePriceField, profitInput, subtotalInput, removeBtnWrapper,
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
        priceField.value = product?.dataset.salePrice ?? 0;
        purchasePriceField.value = product?.dataset.purchasePrice ?? 0;

        calculateSubtotal();
        calculateProfit();
    }

    function calculateSubtotal() {
        if (!saleItem) return;
        const priceField = priceInput.querySelector(`input[data-price]`);
        const qtyField = qtyInput.querySelector(`input[data-qty]`);
        const subtotalField = subtotalInput.querySelector(`input[data-subtotal]`);

        subtotalField.value = (Number(priceField?.value ?? 0) * Number(qtyField?.value ?? 0)).toFixed(2);
        calculateTotal();
    }
    function calculateProfit() {
        if (!saleItem) return;

        const qtyField = qtyInput.querySelector(`input[data-qty]`);
        const subtotalField = subtotalInput.querySelector(`input[data-subtotal]`);
        const profitField = profitInput.querySelector(`input[data-profit]`);

        const purchasePrice = purchasePriceField.value;
        const qty = qtyField.value;
        const subtotal = subtotalField.value;
        const subtotalCost = purchasePrice * qty;
        const profit = subtotal - subtotalCost;

        profitField.value = profit;
        calculateTotalProfit();
    }

    function removeSaleItem() {
        saleItem.remove();
        calculateTotal();
        calculateTotalProfit();
        resetInputCounts();
    }
    saleItemsCount++;
}
function calculateTotal() {
    let grandTotal = 0;
    const subtotals = saleItems.querySelectorAll("input[data-subtotal]");
    subtotals?.forEach(subtotal => {
        grandTotal += Number(subtotal?.value ?? 0);
    });
    totalAmount.textContent = `₹${grandTotal.toFixed(2)}`;
}

function calculateTotalProfit() {
    let grandTotalProfit = 0;
    const profitSubtotals = saleItems.querySelectorAll("input[data-profit]");
    profitSubtotals?.forEach(subtotal => {
        grandTotalProfit += Number(subtotal?.value ?? 0);
    });
    totalProfit.textContent = `₹${grandTotalProfit.toFixed(2)}`;
}

function resetInputCounts() {
    saleItemsCount = 0;
    const saleItemRows = saleItems.querySelectorAll('.sale-item-row');

    saleItemRows.forEach(saleItemRow => {
        const productId = saleItemRow.querySelector(`input[data-product-id]`);
        const qty = saleItemRow.querySelector(`input[data-qty]`);
        const price = saleItemRow.querySelector(`input[data-price]`);
        const subtotal = saleItemRow.querySelector(`input[data-subtotal]`);

        productId.name = `sale_items[${saleItemsCount}]product_id`;
        qty.name = `sale_items[${saleItemsCount}]qty`;
        price.name = `sale_items[${saleItemsCount}]price`;
        subtotal.name = `sale_items[${saleItemsCount}]subtotal`;

        saleItemsCount++;
    });
}
storeSaleBtn.addEventListener('click', async () => {
    const saleItemRows = saleItems.querySelectorAll('.sale-item-row');
    if (saleItemRows.length <= 0) {
        Toast.show('Add Sale Items First', "error");
        return;
    };

    const requestBody = {};
    requestBody.customer_email = customerEmail.value;
    requestBody.customer_name = customerName.value;
    requestBody.customer_phone = customerPhone.value;

    requestBody.sale_date = saleDate.value;
    requestBody.sale_items = [];

    saleItemRows.forEach(saleItemRow => {
        const productId = saleItemRow.querySelector(`input[data-product-id]`);
        const qty = saleItemRow.querySelector(`input[data-qty]`);
        const price = saleItemRow.querySelector(`input[data-price]`);

        requestBody.sale_items.push({
            product_id: productId.dataset.id,
            qty: qty.value,
            price: price.value,
        });
    })

    const request = new Request({
        url: '/sales',
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
                totalProfit.textContent = `₹0.00`;
                resetProductList();
                Toast.show(response.data.message);
                break;
            case 422:
                showValidationErrors(response.data.errors);
                Toast.show("Invalid Data", "error");
                break;
            case 409:
                Toast.show(response.data.message, "error");
                break;
            case 403:
                resetForm();
                Toast.show("Unauthorized", "error");
                break;
            default:
                Toast.show("Internal Error", "error");
        }
    });
});

function errorKeyToName(key) {
    return key.replace(/\.(\d+)\./g, '[$1]')
        .replace(/\.([^.]+)$/, '$1');
}
function resetForm() {
    form.reset();
    saleItems.innerHTML = '';
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
                        salePrice: product.selling_price,
                        purchasePrice: product.purchase_price,
                    }
                }).create();
            });
        } else {
            Toast.show('Unable to fetch product options', 'error');
        }
    })
}