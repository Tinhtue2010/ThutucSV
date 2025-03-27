@extends('layout.main_layout')

@section('content')
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar pt-7 pt-lg-10">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
            <!--begin::Toolbar wrapper-->
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <!--begin::Actions-->
                <div></div>
                @if (count($data['data']) > 0)
                    <div class="d-flex align-items-center gap-2 gap-lg-3">
                        <a href="{{ route('notification.readAll') }}" class="btn btn-flex btn-secondary h-40px fs-7 fw-bold">Đánh đã đọc</a>
                    </div>
                @endif
                <!--end::Actions-->
            </div>
            <!--end::Toolbar wrapper-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                @if (count($data['data']) == 0)
                    <h4>Hiện không có thông báo nào</h4>
                @endif
                @foreach ($data['data'] as $item)
                    @php
                        if ($item['status'] == 0) {
                            $className = 'warning';
                        } else {
                            $className = 'secondary';
                        }
                    @endphp
                    <div class="shadow d-flex align-items-center bg-light-{{ $className }} rounded p-5 mb-7">
                        <i class="ki-outline ki-snapchat text-{{ $className }} fs-1 me-5"></i>
                        <!--begin::Title-->
                        @if (Auth::user()->role == 1)
                            <a href="{{ route('notification.viewNotifi', ['type' => $item['type']]) }}" class="flex-grow-1 me-2">
                                <div class="text-black  fw-bold text-gray-800  fs-4">Thông báo</div>
                                <span class="text-black fs-5 fw-semibold d-block">{{ $item['notification'] }}</span>
                                @if ($item['file_name'] != null)
                                    <div class="d-flex justify-content-end">
                                        <a target="_bank" href="/storage/{{ $item['file_name'] }}" class="btn btn-flex btn-success fs-7 fw-bold">Xem</a>
                                    </div>
                                @endif
                            </a>
                        @else
                            <a href="{{ route('notification.viewNotifi', ['type' => $item['type']]) }}" class="flex-grow-1 me-2">
                                <div class="text-black  fw-bold text-gray-800  fs-4">Thông báo</div>
                                <span class="text-black fs-5 fw-semibold d-block">{{ $item['notification'] }}</span>

                            </a>
                        @endif

                        <!--end::Title-->
                    </div>
                @endforeach
            </div>
            <!--end::Content container-->
            <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                <div class="dataTables_paginate paging_simple_numbers" id="kt_ecommerce_products_table_paginate">
                    <ul class="pagination">
                        <li class="paginate_button page-item previous disabled" id="kt_ecommerce_products_table_previous">
                            <a href="#" aria-controls="kt_ecommerce_products_table" data-dt-idx="0" tabindex="0" class="page-link"><i class="previous"></i></a>
                        </li>
                        <li class="paginate_button page-item active">
                            <a href="#" aria-controls="kt_ecommerce_products_table" data-dt-idx="1" tabindex="0" class="page-link">1</a>
                        </li>
                        <li class="paginate_button page-item next" id="kt_ecommerce_products_table_next">
                            <a href="#" aria-controls="kt_ecommerce_products_table" data-dt-idx="6" tabindex="0" class="page-link">
                                <i class="next"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!--end::Content-->
    </div>

    <script>
        renderPagination({{ $data['page'] }}, {{ $data['max_page'] }});

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
                        window.location.href = `{{ route('notification.index') }}?page=${Number(idActive) - 1}`;
                    else if (id == '+1')
                        window.location.href = `{{ route('notification.index') }}?page=${Number(idActive) + 1}`;
                    else
                        window.location.href = `{{ route('notification.index') }}?page=${id}`;
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
            if (max_page > 4) {

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
            } else {
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
    </script>
@endsection
