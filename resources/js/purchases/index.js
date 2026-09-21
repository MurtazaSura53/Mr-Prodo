import { Selector, Request, Alert, Toast, Paginate, Element } from '../main';
Selector.id('purchasesLink').classList.add('active');

const addPurchaseBtn = Selector.id("addPurchaseBtn");

const previousBtn = Selector.id('previousBtn');
const nextBtn = Selector.id('nextBtn');
const paginationPages = Selector.id('paginationPages');
let lastPage = Selector.qs("meta[name='lastPage']").content;
const currentPage = 1;

addPurchaseBtn.addEventListener('click', () => {
    window.location.href = "/purchases/create";
});

// ################################################################
// @Section: Pagination
// ################################################################
const paginate = new Paginate(
    '/purchases',
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
                        renderPurchases(data.data);

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
        renderPurchases(data.data);
    });

    renderPagination();
});

nextBtn.addEventListener('click', async () => {

    await paginate.next(data => {
        updatePageOverview(data.meta);
        renderPurchases(data.data);
    });

    renderPagination();
});
function updatePageOverview(meta) {

    Selector.id('totalItems').textContent = meta.total;

    Selector.id('showingPurchases').textContent =
        `${meta.from} - ${meta.to}`;
}
function renderPurchases(purchases) {
    const container = Selector.id('cardContainer');
    container.innerHTML = '';
    purchases.forEach(purchase => {
        Element.make('div', container).attributes({
            class: 'purchase-card'
        }).children([

            Element.make('div').attributes({ class: 'purchase-card-header' }).children([
                Element.make('span').attributes({
                    class: 'purchase-id',
                    text: `Purchase #${purchase.id}`,
                }).create(),
                Element.make('span').attributes({
                    class: 'purchase-total',
                    text: `₹${purchase.total_amount}`,
                }).create(),
            ]).create(),

            Element.make('div').attributes({ class: 'purchase-card-body' }).children([

                Element.make('div').attributes({ class: 'purchase-info' }).children([
                    Element.make('span').attributes({
                        class: 'purchase-label',
                        text: 'Supplier',
                    }).create(),
                    Element.make('span').attributes({
                        class: 'purchase-value',
                        text: purchase.supplier_name,
                    }).create(),
                ]).create(),

                Element.make('div').attributes({ class: 'purchase-info' }).children([
                    Element.make('span').attributes({
                        class: 'purchase-label',
                        text: 'Purchase Date',
                    }).create(),
                    Element.make('span').attributes({
                        class: 'purchase-value',
                        text: purchase.purchase_date,
                    }).create(),
                ]).create(),

            ]).create(),

            Element.make('div').attributes({ class: 'purchase-card-footer' }).children([
                Element.make('a').attributes({
                    class: 'purchase-action',
                    href: `/purchases/edit/${purchase.id}`,
                    html: `View Purchase 
                            <i class='fa-solid fa-arrow-right'></i>`,
                }).create(),
            ]).create(),

        ]).create();
    });
    //Bind Event attached with card
}