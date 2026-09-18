import { Selector, Request, Alert, Toast, Paginate, Element } from '../main';
Selector.id('salesLink').classList.add('active');

const addSalesBtn = Selector.id("addSalesBtn");

const previousBtn = Selector.id('previousBtn');
const nextBtn = Selector.id('nextBtn');
const paginationPages = Selector.id('paginationPages');
let lastPage = Selector.qs("meta[name='lastPage']").content;
const currentPage = 1;

addSalesBtn.addEventListener('click', () => {
    window.location.href = "/sales/create";
});

// ################################################################
// @Section: Pagination
// ################################################################
const paginate = new Paginate(
    '/sales',
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
                        renderSales(data.data);

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
        renderSales(data.data);
    });

    renderPagination();
});

nextBtn.addEventListener('click', async () => {

    await paginate.next(data => {
        updatePageOverview(data.meta);
        renderSales(data.data);
    });

    renderPagination();
});

function updatePageOverview(meta) {

    Selector.id('totalItems').textContent = meta.total;

    Selector.id('showingSales').textContent =
        `${meta.from} - ${meta.to}`;
}

function renderSales(sales) {
    const container = Selector.id('cardContainer');
    container.innerHTML = '';
    sales.forEach(sale => {
        Element.make('div', container).attributes({ class: 'sale-card' }).children([
            Element.make('div').attributes({ class: 'sale-card-header' }).children([
                Element.make('span').attributes({ class: 'sale-card-id', text: `#${sale.id}` }).create(),
            ]).create(),

            Element.make('div').attributes({ class: 'sale-customer' }).children([
                Element.make('div')
                    .attributes({
                        class: 'sale-customer-name ' + (!sale.customer) ? 'sale-walk-in' : '',
                        text: (sale.customer) ? sale.customer.name : 'Walk-in Customer',
                    }).create(),

                Element.make('div').attributes({
                    class: 'sale-customer-detail',
                    html: `<i class="fa-solid fa-envelope"></i>${sale.customer?.email ?? '---'}`,
                }).create(),

                Element.make('div').attributes({
                    class: 'sale-customer-detail',
                    html: `<i class="fa-solid fa-phone"></i>${sale.customer?.phone ?? '---'}`,
                }).create(),
            ]).create(),

            Element.make('div').attributes({
                class: 'sale-info',
            }).children([
                Element.make('span').attributes({
                    class: 'sale-info-label',
                    text: 'Sale Date',
                }).create(),

                Element.make('span').attributes({
                    class: 'sale-info-value',
                    text: new Date(sale.sale_date).toLocaleDateString('en-GB', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    }),
                }).create(),
            ]).create(),

            Element.make('div').attributes({
                class: 'sale-summary'
            }).children([
                Element.make('div').attributes({
                    class: 'sale-summary-item',
                }).children([
                    Element.make('span').attributes({
                        class: 'sale-summary-label',
                        text: 'Total Amount'
                    }).create(),

                    Element.make('span').attributes({
                        class: 'sale-summary-value',
                        text: `₹${sale.total_amount}`,
                    }).create(),
                ]).create(),

                Element.make('div').attributes({
                    class: 'sale-summary-item',
                }).children([
                    Element.make('span').attributes({
                        class: 'sale-summary-label',
                        text: 'Total Profit',
                    }).create(),

                    Element.make('span').attributes({
                        class: 'sale-summary-value sale-profit',
                        text: `₹${sale.total_profit}`
                    }).create(),
                ]).create(),
            ]).create(),

            Element.make('div').attributes({
                class: 'flex jc-end',
            }).children([
                Element.make('a').attributes({
                    href: `/sales/edit/${sale.id}`,
                    html: `<button type="button" class="btn primary">
                                View Sale
                            </button>`
                }).create(),
            ]).create(),
        ]).create();
    });
}