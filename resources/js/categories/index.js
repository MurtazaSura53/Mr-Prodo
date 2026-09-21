import {
    Selector,
    Request,
    Element,
    Toast,
    Prompt,
    Form,
    Paginate,
    Alert
} from "../main";
Selector.id('categoriesLink').classList.add('active');
const addCategoryBtn = Selector.id("addCategoryBtn");
let form = new Form(Selector.id("form"));


const previousBtn = Selector.id('previousBtn');
const nextBtn = Selector.id('nextBtn');
const paginationPages = Selector.id('paginationPages');
let lastPage = Selector.qs("meta[name='lastPage']").content;
const currentPage = 1;


// ################################################################
// @Section: Pagination
// ################################################################
const paginate = new Paginate(
    '/categories',
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
                        renderCategories(data.data);

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
        renderCategories(data.data);
    });

    renderPagination();
});

nextBtn.addEventListener('click', async () => {

    await paginate.next(data => {
        updatePageOverview(data.meta);
        renderCategories(data.data);
    });

    renderPagination();
});
function updatePageOverview(meta) {

    Selector.id('totalItems').textContent = meta.total;

    Selector.id('showingCategories').textContent =
        `${meta.from} - ${meta.to}`;
}
function renderCategories(data) {

    const wrapper = Selector.id('form');

    wrapper.innerHTML = '';

    data.forEach(category => {

        const categoryName = Element.make('input')
            .attributes({
                class: 'category-name',
                type: 'text',
                name: 'category_name',
                value: category.name,
                dataset: {
                    categoryId: category.id
                }
            })
            .create();

        const categoryNameWrapper = Element.make('div')
            .attributes({
                class: 'category-name-wrapper'
            })
            .children([
                categoryName
            ])
            .create();

        const productCount = Element.make('span')
            .attributes({
                class: 'product_count',
                text: `Total Products: ${category.products_count}`
            })
            .create();

        const categoryContent = Element.make('div')
            .attributes({
                class: 'category-content'
            })
            .children([
                categoryNameWrapper,
                productCount
            ])
            .create();

        const deleteButton = Element.make('button')
            .attributes({
                type: 'button',
                class: 'btn-icon error',
                dataset: {
                    deleteCategory: '',
                    categoryId: category.id
                },
                onClick: () => {
                    deleteCategory(category.id);
                }
            })
            .children([
                Element.make('i')
                    .attributes({
                        class: 'fa-solid fa-trash-can'
                    })
                    .create()
            ])
            .create();

        const categoryButtonWrapper = Element.make('div')
            .attributes({
                class: 'category-btn-wrapper'
            })
            .children([
                deleteButton
            ])
            .create();

        Element.make('div', wrapper)
            .attributes({
                class: 'category'
            })
            .children([
                categoryContent,
                categoryButtonWrapper
            ])
            .create();
    });

    // Re-bind inline update events

    bindCategoryUpdates();
    bindCategoriesDelete();
}
// ################################################################
// @Section: Store Category
// ################################################################
addCategoryBtn.addEventListener("click", async () => {
    Prompt.show(
        [
            { label: "Category name:", name: "name" },
        ],
        function (fields) {
            const requestBody = {};
            fields.forEach(input => {
                requestBody[input.name] = input.value;
            });
            requestBody["_token"] = Selector.qs("meta[name='csrf-token']").content;
            const request = new Request({
                url: "/categories",
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                },
                body: JSON.stringify(requestBody),
            });
            request.send(async (response) => {
                switch (response.status) {
                    case 201:
                        Toast.show(response.data.message);
                        await paginate.current(data => {
                            paginate.lastPage = data.meta.last_page;
                            updatePageOverview(data.meta);
                            renderCategories(data.data);
                            renderPagination();
                        });
                        break;
                    case 422:
                        Toast.show("Invalid data", "error");
                        break;
                    default:
                        Toast.show("Internal error", "error");
                        break;
                }
            });
        }, "Add");
});
// ################################################################
// @Section: Update Category
// ################################################################
bindCategoryUpdates();
function bindCategoryUpdates() {
    form = new Form(Selector.id("form"));
    form.allInputs.forEach(input => {
        input.dataset.originalValue = input.value;

        input.addEventListener('blur', () => {

            const newValue = input.value.trim();

            // Nothing changed
            if (newValue === input.dataset.originalValue) {
                return;
            }

            // Update category
            updateCategory(
                input.dataset.categoryId,
                newValue,
                input
            );
        });
    });
}
function updateCategory(categoryId, categoryName, input) {
    const requestBody = {
        name: categoryName,
        _method: "PATCH",
        _token: Selector.qs("meta[name='csrf-token']").content,
    };
    const request = new Request({
        url: `/categories/${categoryId}`,
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
                input.dataset.originalValue = input.value.trim();
                Toast.show(response.data.message);
                break;
            case 422:
                Toast.show("Invalid name", "error");
                break;
            case 403:
                Toast.show("Unauthorized", "error");
                break;
            default:
                Toast.show("Internal Error", "error");
        }
    });
}
// ################################################################
// @Section: Delete Category
// ################################################################
bindCategoriesDelete();
function bindCategoriesDelete() {
    const deleteBtns = Selector.qsa("[data-delete-category]");
    deleteBtns.forEach(deleteBtn => {

        deleteBtn.addEventListener("click", () => {
            Alert.show('Are you sure, you want to delete this category?', () => {
                const categoryId = deleteBtn.dataset.categoryId;
                const request = new Request({
                    url: `/categories/${categoryId}`,
                    method: "DELETE",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": Selector.qs('meta[name="csrf-token"]').content
                    }
                });
                request.send(async response => {
                    switch (response.status) {
                        case 204:
                            Toast.show("Deleted");
                            await paginate.current(data => {
                                paginate.lastPage = data.meta.last_page;
                                updatePageOverview(data.meta);
                                renderCategories(data.data);
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
            }, "Delete");
        })
    });
}


