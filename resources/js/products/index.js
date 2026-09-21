import { Selector, Request, Alert, Toast, Paginate, Element } from '../main';
Selector.id('productsLink').classList.add('active');

const addProductBtn = Selector.id("addProductBtn");

const previousBtn = Selector.id('previousBtn');
const nextBtn = Selector.id('nextBtn');
const paginationPages = Selector.id('paginationPages');
let lastPage = Selector.qs("meta[name='lastPage']").content;
const currentPage = 1;

addProductBtn.addEventListener('click', () => {
    window.location.href = "/products/create";
});

bindDescriptionToggles();
function bindDescriptionToggles() {
    const productDescriptions = Selector.qsa('[data-product-description]');
    productDescriptions.forEach(description => {

        const text = description.querySelector('.description-text');
        const toggle = description.querySelector('.description-toggle');

        const label = toggle.querySelector('span');
        const icon = toggle.querySelector('i');

        if (text.scrollHeight > text.clientHeight) {
            toggle.style.display = 'inline-flex';
        }

        toggle.addEventListener('click', () => {

            const expanded = text.classList.toggle('expanded');

            label.textContent = expanded
                ? 'Show less'
                : 'Show more';

            icon.classList.toggle('fa-chevron-down', !expanded);
            icon.classList.toggle('fa-chevron-up', expanded);

        });

    });
}

bindUpdateBtnEvent();
function bindUpdateBtnEvent() {
    const updateBtns = Selector.qsa('[data-product-update]');
    updateBtns.forEach(updateBtn => {
        updateBtn.addEventListener('click', e => {
            const productId = updateBtn.dataset.productId;
            window.location.href = `/products/edit/${productId}`;
        })
    })
}
bindDeleteBtnEvent();
function bindDeleteBtnEvent() {
    const deleteBtns = Selector.qsa('[data-product-delete]');
    deleteBtns.forEach(deleteBtn => {
        deleteBtn.addEventListener('click', async e => {
            Alert.show("Are you sure, you want to delete this product?", () => {
                const productId = deleteBtn.dataset.productId;
                const request = new Request({
                    url: `/products/${productId}`,
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": Selector.qs('meta[name="csrf-token"]').content,
                    }
                });
                request.send(async response => {
                    switch (response.status) {
                        case 204:
                            Toast.show("Deleted");
                            await paginate.current(data => {
                                paginate.lastPage = data.meta.last_page;
                                updatePageOverview(data.meta);
                                renderProducts(data.data);
                                renderPagination();
                            });
                            break;
                        case 403:
                            Toast.show("Unauthorized", "error");
                            break;
                        default:
                            Toast.show("Internal Error", "error");
                    }
                })
            }, "Delete")
        })
    })
}

// ################################################################
// @Section: Pagination
// ################################################################
const paginate = new Paginate(
    '/products',
    lastPage,
    currentPage
);
renderPagination();

function renderPagination() {

    paginationPages.innerHTML = '';

    const currentPage = paginate.page;
    const lastPage = paginate.lastPage;

    let pages = [];

    if (lastPage <= 5) {

        for (let page = 1; page <= lastPage; page++) {
            pages.push(page);
        }

    } else if (currentPage <= 2) {

        pages = [
            1,
            2,
            '...',
            lastPage
        ];

    } else if (currentPage >= lastPage - 1) {

        pages = [
            1,
            '...',
            lastPage - 1,
            lastPage
        ];

    } else {

        pages = [
            1,
            '...',
            currentPage - 1,
            currentPage,
            currentPage + 1,
            '...',
            lastPage
        ];
    }

    pages.forEach(page => {

        if (page === '...') {

            Element.make('span', paginationPages)
                .attributes({
                    class: 'pagination-ellipsis',
                    text: '...'
                })
                .create();

            return;
        }

        const button = Element.make('button', paginationPages)
            .attributes({
                type: 'button',
                class: 'pagination-page',
                text: page,
                onClick: async () => {

                    if (page === paginate.page) {
                        return;
                    }

                    paginate.page = page;

                    await paginate.current(data => {

                        paginate.lastPage = data.meta.last_page;

                        updatePageOverview(data.meta);
                        renderProducts(data.data);

                    });

                    renderPagination();
                }
            })
            .create();

        if (page === currentPage) {
            button.classList.add('active');
        }
    });

    previousBtn.disabled = currentPage <= 1;
    nextBtn.disabled = currentPage >= lastPage;
}
previousBtn.addEventListener('click', async () => {

    await paginate.previous(data => {
        updatePageOverview(data.meta);
        renderProducts(data.data);
    });

    renderPagination();
});

nextBtn.addEventListener('click', async () => {

    await paginate.next(data => {
        updatePageOverview(data.meta);
        renderProducts(data.data);
    });

    renderPagination();
});
function updatePageOverview(meta) {

    Selector.id('totalItems').textContent = meta.total;

    Selector.id('showingProducts').textContent =
        `${meta.from} - ${meta.to}`;
}

function renderProducts(products) {
    const container = Selector.id('form');
    container.innerHTML = '';
    products.forEach(product => {
        const productCard = Element.make('div')
            .attributes({ class: `product ${product.stock > 0 ? 'in-stock' : 'out-of-stock'}` })
            .children([
                Element.make('div')
                    .attributes({ class: 'product-content' })
                    .children([
                        Element.make('div')
                            .attributes({ class: 'product-header' })
                            .children([
                                Element.make('div')
                                    .attributes({ class: 'product-title-wrapper' })
                                    .children([
                                        Element.make('h3')
                                            .attributes({
                                                class: 'product-name',
                                                text: product.name
                                            })
                                            .create(),
                                        Element.make('span')
                                            .attributes({
                                                class: 'product-category',
                                                text: product.category
                                            })
                                            .create()
                                    ])
                                    .create(),
                                Element.make('div')
                                    .attributes({ class: 'product-btn-wrapper' })
                                    .children([
                                        Element.make('button')
                                            .attributes({
                                                type: 'button',
                                                class: 'btn-icon',
                                                dataset: {
                                                    productUpdate: '',
                                                    productId: product.id
                                                }
                                            })
                                            .children([
                                                Element.make('i').attributes({ class: 'fa-solid fa-pen' }).create()
                                            ])
                                            .create(),
                                        Element.make('button')
                                            .attributes({
                                                type: 'button',
                                                class: 'btn-icon error',
                                                dataset: {
                                                    productDelete: '',
                                                    productId: product.id
                                                }
                                            })
                                            .children([
                                                Element.make('i').attributes({ class: 'fa-solid fa-trash-can' }).create()
                                            ])
                                            .create()
                                    ])
                                    .create()
                            ])
                            .create(),
                        // Description
                        Element.make('div')
                            .attributes({
                                class: 'product-description',
                                dataset: {
                                    productDescription: ''
                                }
                            })
                            .children([
                                Element.make('p')
                                    .attributes({
                                        class: 'description-text',
                                        text: product.description ?? ''
                                    })
                                    .create(),
                                Element.make('button')
                                    .attributes({
                                        type: 'button',
                                        class: 'description-toggle'
                                    })
                                    .children([
                                        Element.make('span')
                                            .attributes({ text: 'Show more' })
                                            .create(),
                                        Element.make('i')
                                            .attributes({ class: 'fa-solid fa-chevron-down' })
                                            .create()
                                    ])
                                    .create()
                            ])
                            .create(),
                        // Product Information
                        Element.make('div')
                            .attributes({ class: 'product-info' })
                            .children([
                                Element.make('div')
                                    .attributes({ class: 'info-item' })
                                    .children([
                                        Element.make('span')
                                            .attributes({
                                                class: 'info-label',
                                                text: 'Stock'
                                            })
                                            .create(),
                                        Element.make('strong')
                                            .attributes({
                                                class: 'info-value',
                                                text: `${product.stock} ${product.unit}`
                                            })
                                            .create()
                                    ])
                                    .create(),
                                Element.make('div')
                                    .attributes({ class: 'info-item' })
                                    .children([
                                        Element.make('span')
                                            .attributes({
                                                class: 'info-label',
                                                text: 'Purchase Price'
                                            })
                                            .create(),
                                        Element.make('strong')
                                            .attributes({
                                                class: 'info-value',
                                                text: `₹${product.purchase_price}`
                                            })
                                            .create()
                                    ])
                                    .create(),
                                Element.make('div')
                                    .attributes({ class: 'info-item' })
                                    .children([
                                        Element.make('span')
                                            .attributes({
                                                class: 'info-label',
                                                text: 'Selling Price'
                                            })
                                            .create(),
                                        Element.make('strong')
                                            .attributes({
                                                class: 'info-value',
                                                text: `₹${product.selling_price}`
                                            })
                                            .create()
                                    ])
                                    .create()
                            ])
                            .create()
                    ])
                    .create()
            ])
            .create();
        container.append(productCard);
    });

    //Binds Card Related Events
    bindDescriptionToggles();
    bindUpdateBtnEvent();
    bindDeleteBtnEvent();
}