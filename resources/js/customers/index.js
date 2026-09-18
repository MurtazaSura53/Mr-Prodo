import { Selector, Request, Alert, Toast, Prompt, Paginate, Element } from '../main';
Selector.id('customersLink').classList.add('active');

const container = Selector.id("cardContainer");

const previousBtn = Selector.id('previousBtn');
const nextBtn = Selector.id('nextBtn');
const paginationPages = Selector.id('paginationPages');
let lastPage = Selector.qs("meta[name='lastPage']").content;
const currentPage = 1;

// ################################################################
// @Section: Bind Edit Event
// ################################################################
bindEditEvent();
function bindEditEvent() {
    const cards = container.querySelectorAll('[data-customer-card]');
    cards.forEach(card => {
        const customer = {}
        customer.id = card.querySelector('[data-id]').dataset.id;
        customer.name = card.querySelector('[data-name]').dataset.name;
        customer.email = card.querySelector('[data-email]').dataset.email;
        customer.phone = card.querySelector('[data-phone]').dataset.phone;

        const editBtn = card.querySelector('[data-edit]');
        editBtn.addEventListener('click', e => {
            showEdit(customer);
        })
    })
}
// ################################################################
// @Section: Bind Delete Event
// ################################################################
bindDeleteEvent();
function bindDeleteEvent() {
    const cards = container.querySelectorAll('[data-customer-card]');
    cards.forEach(card => {
        const deleteBtn = card.querySelector('[data-delete]');
        const customerId = deleteBtn.dataset.id;

        deleteBtn.addEventListener('click', e => {
            deleteAction(customerId);
        })
    })
}
// ################################################################
// @Section: Pagination
// ################################################################
const paginate = new Paginate(
    '/customers',
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
                        renderCustomers(data.data);

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
        renderCustomers(data.data);
    });

    renderPagination();
});

nextBtn.addEventListener('click', async () => {

    await paginate.next(data => {
        updatePageOverview(data.meta);
        renderCustomers(data.data);
    });

    renderPagination();
});

function updatePageOverview(meta) {

    Selector.id('totalItems').textContent = meta.total;

    Selector.id('showingCustomers').textContent =
        `${meta.from ?? 0} - ${meta.to ?? 0}`;
}

// ################################################################
// @Section: Render Customer
// ################################################################
function renderCustomers(customers) {
    container.innerHTML = "";
    customers.forEach(customer => {
        //Render Cards
        const card = Element.make('div', container).attributes({
            class: 'customer-card',
            dataset: { customerCard: '' }
        }).children([
            //Header
            Element.make('div').attributes({ class: 'customer-card-header' }).children([
                Element.make('span').attributes({
                    class: 'customer-card-id',
                    text: `#${customer.id}`,
                    dataset: { id: customer.id }
                }).create(),
            ]).create(),

            //Customer
            Element.make('div').attributes({ class: 'customer-info' }).children([
                Element.make('div').attributes({
                    class: 'customer-name',
                    dataset: { name: customer.name },
                    text: customer.name,
                }).create(),

                Element.make('div').attributes({
                    class: 'customer-detail',
                    dataset: { email: customer.email },
                    html: `
                    <i class="fa-solid fa-envelope"></i>${customer.email}
                    `,
                }).create(),

                Element.make('div').attributes({
                    class: 'customer-detail',
                    dataset: { phone: customer.phone ?? '' },
                    html: `
                    <i class="fa-solid fa-phone"></i>${customer.phone ?? '---'}
                    `,
                }).create(),
            ]).create(),

            //Sales Summary
            Element.make('div').attributes({ class: 'customer-summary' }).children([

                Element.make('div').attributes({
                    class: 'customer-summary-item'
                }).children([
                    Element.make('span').attributes({
                        class: 'customer-summary-label',
                        text: 'Total Sales'
                    }).create(),

                    Element.make('span').attributes({
                        class: 'customer-summary-value',
                        dataset: { totalSaleAmount: customer.sales_sum_total_amount },
                        text: `₹${customer.sales_sum_total_amount}`,
                    }).create(),
                ]).create(),

                Element.make('div').attributes({
                    class: 'customer-summary-item'
                }).children([
                    Element.make('span').attributes({
                        class: 'customer-summary-label',
                        text: 'Sales Made'
                    }).create(),

                    Element.make('span').attributes({
                        class: 'customer-summary-value',
                        dataset: { saleCount: customer.sales_count },
                        text: `${customer.sales_count}`,
                    }).create(),
                ]).create(),

            ]).create(),

            //Actions
            Element.make('div').attributes({ class: 'flex jc-end' }).children([
                Element.make('button').attributes({
                    class: 'btn-fluid primary',
                    dataset: { edit: '' },
                    html: `
                    <i class="fa-solid fa-pen"></i>Edit
                    `,
                    onClick: () => {
                        showEdit(customer);
                    }
                }).create(),

                Element.make('button').attributes({
                    class: 'btn-fluid error',
                    dataset: {
                        delete: '',
                        id: customer.id,
                    },
                    html: `
                    <i class="fa-solid fa-trash"></i>Delete
                    `,
                    onClick: () => {
                        deleteAction(customer.id);
                    }
                }).create(),
            ]).create(),

        ]).create();
    });

    //Binds Card Related Events
}
function showEdit(customer) {
    Prompt.show([
        { name: 'name', label: 'Customer Name', value: customer.name },
        { name: 'email', label: 'Email', value: customer.email },
        { name: 'phone', label: 'Phone.no', value: customer.phone },
    ], fields => {
        const requestBody = {};
        fields.forEach(field => {
            requestBody[field.name] = field.value;
        });
        requestBody._token = Selector.qs("meta[name='csrf-token']").content;

        const request = new Request({
            url: `/customers/${customer.id}`,
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify(requestBody),
        });
        request.send(response => {
            switch (response.status) {
                case 200:
                    paginate.current(data => {
                        paginate.lastPage = data.meta.last_page;
                        updatePageOverview(data.meta);
                        renderCustomers(data.data);
                    });
                    renderPagination();

                    Toast.show(response.data.message);
                    break;
                case 422:
                    Toast.show('Invalid or Incomplete Data', 'error');
                    break;
                case 403:
                    Toast.show('Unauthorized', 'error');
                    break;
                default:
                    Toast.show('Internal Error', 'error');
            }
        });
    }, 'Save');
}
function deleteAction(id) {
    Alert.show(
        'Are you sure, you want to delete this customer?',
        () => {
            const request = new Request({
                url: `/customers/${id}`,
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': Selector.qs("meta[name='csrf-token']").content,
                },
            });
            request.send(response => {
                switch (response.status) {
                    case 204:
                        Toast.show('Customer Deleted');
                        paginate.current(data => {
                            paginate.lastPage = data.meta.last_page;
                            updatePageOverview(data.meta);
                            renderCustomers(data.data);
                        });
                        renderPagination();
                        break;
                    case 403:
                        Toast.show("Unauthorized", "error");
                        break;
                    default:
                        Toast.show('Internal Error', 'error');
                }
            })
        }
    )
}