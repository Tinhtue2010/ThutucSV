function Pagination() {
    const pagination = document.querySelectorAll('[aria-controls="kt_ecommerce_pagination_table"]');
    pagination.forEach(pageItem => {
        pageItem.addEventListener('click', (e) => {
                e.preventDefault();
                var idActive = document.querySelector('.paginate_button.page-item.active')
                    .querySelector('[aria-controls="kt_ecommerce_pagination_table"]')
                    .getAttribute('data-dt-idx');
                var id = pageItem.getAttribute('data-dt-idx');
                if (id == '-1')
                    getData(Number(idActive) - 1);
                else if (id == '+1')
                    getData(Number(idActive) + 1);
                else
                    getData(id);
        });
    });
}
function renderPagination(page, max_page) {
    const pagination = document.querySelector('#kt_ecommerce_products_table_paginate');
    if (max_page < 2) {
        pagination.innerHTML = '';
        Pagination();
        return;
    }
    var html = `<ul class="pagination">`;
    var Previous = page > 1;
    var Next = page < max_page;
    html += `<li class="paginate_button page-item ${!Previous ? 'disabled' : ''}">
                <a href="#"
                    aria-controls="kt_ecommerce_pagination_table"
                    data-dt-idx="1" tabindex="1"
                    class="page-link">
                    <i class="previous"></i>
                    <i class="previous"></i>
                    </a>
            </li>`;
    html += `<li class="paginate_button page-item ${!Previous ? 'disabled' : ''}">
            <a href="#"
               aria-controls="kt_ecommerce_pagination_table"
               data-dt-idx="-1" tabindex="-1"
               class="page-link"><i
                    class="previous"></i></a></li>`;
if(max_page > 4)
{

    if (page <= 3) {
        for (var i = 1; i <= 5; i++) {
            html += `<li class="paginate_button page-item ${i == page ? 'active' : ''} ">
                        <a href="#"
                           aria-controls="kt_ecommerce_pagination_table"
                           data-dt-idx="${i}" tabindex="${i}"
                           class="page-link">${i}</a>
                    </li>`;
        }
        html += `<li class="paginate_button page-item disabled ">
                        <span
                           aria-controls=""kt_ecommerce_pagination_table"
                           class="page-link">...</span>
                    </li>`;
    } else if (page > 3 && page < max_page - 2) {
        html += `<li class="paginate_button page-item disabled ">
                        <span
                           aria-controls="kt_ecommerce_pagination_table"
                           class="page-link">...</span>
                    </li>`;
        for (var i = Number(page) - 2; i <= Number(page) + 2; i++) {
            html += `<li class="paginate_button page-item ${i == page ? 'active' : ''} ">
                        <a href="#"
                           aria-controls="kt_ecommerce_pagination_table"
                           data-dt-idx="${i}" tabindex="${i}"
                           class="page-link">${i}</a>
                    </li>`;
        }
        html += `<li class="paginate_button page-item disabled ">
                        <span
                           aria-controls="kt_ecommerce_pagination_table"
                           class="page-link">...</span>
                    </li>`;
    } else if (page >= max_page - 2) {
        html += `<li class="paginate_button page-item disabled ">
                        <span
                           aria-controls="kt_ecommerce_pagination_table"
                           class="page-link">...</span>
                    </li>`;
        for (var i = Number(page) - 2; i <= max_page; i++) {
            html += `<li class="paginate_button page-item ${i == page ? 'active' : ''} ">
                        <a href="#"
                           aria-controls="kt_ecommerce_pagination_table"
                           data-dt-idx="${i}" tabindex="${i}"
                           class="page-link">${i}</a>
                    </li>`;
        }

    }
}
else
{
    for (var i = 1; i <= max_page; i++) {
        html += `<li class="paginate_button page-item ${i == page ? 'active' : ''} ">
                    <a href="#"
                       aria-controls="kt_ecommerce_pagination_table"
                       data-dt-idx="${i}" tabindex="${i}"
                       class="page-link">${i}</a>
                </li>`;
    }
}

    html += `<li class="paginate_button page-item ${!Next ? 'disabled' : ''}">
                <a href="#"
                   aria-controls="kt_ecommerce_pagination_table"
                   data-dt-idx="+1" tabindex="+1"
                   class="page-link">
                    <i class="next"></i></a></li>`;
    html += `<li class="paginate_button page-item ${!Next ? 'disabled' : ''}">
                <a href="#"
                    aria-controls="kt_ecommerce_pagination_table"
                    data-dt-idx="${max_page}" tabindex="${max_page}"
                    class="page-link">
                    <i class="next"></i>
                    <i class="next"></i>
                    </a>
            </li>`;
    html += `</ul>`;
    pagination.innerHTML = html;
    Pagination();
}
