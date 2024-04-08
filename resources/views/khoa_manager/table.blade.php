@push('js')
    {{-- @include('classManager.validate') --}}
    <script type="text/javascript">
        var Datatable = function () {
            // Shared variables
            var table;
            var datatable;
            var dataCustom;

            // Private functions
            var initDatatable = function () {
                // Init datatable --- more info on datatables: https://datatables.net/manual/
                datatable = $(table).DataTable({
                    "info": false,
                    "serverSide": true,
                    "ajax": {
                        "url": "{{ route('classManager.getData') }}", // Thay đổi đường dẫn đến tệp xử lý AJAX
                        "type": "GET",
                        "data": function (data) {
                            var name_order = document
                                .querySelectorAll(
                                    'table thead tr th')[data
                                .order[0]['column']]
                                .getAttribute('data-name');
                            var order_by = data.order[0]['dir']
                            data.order_name = name_order;
                            data.order_by = order_by;
                            data.columns = undefined;
                            data.order = undefined;
                            data.search = '';
                            return $.extend({}, data,
                                dataCustom);
                        },
                        "dataSrc": function (response) {
                            renderPagination(response.page,
                                response.max_page);
                            return response.data;
                        },
                    },
                    columns: [{
                        data: 'id',
                        render: function (data, type, row) {
                            return '';
                        }
                    },
                        {
                            data: 'id'
                        },
                        {
                            data: 'name'
                        },
                        {
                            data: 'created_at',
                            render: function (data, type, row) {
                                return moment(data).format("DD/MM/YYYY");
                            }
                        },
                        {
                            data: 'updated_at',
                            render: function (data, type, row) {
                                return moment(data).format("DD/MM/YYYY");
                            }
                        },

                        {
                            data: 'id',
                            render: function (data, type, row) {
                                const url =
                                    "{{ route('classManager.detele') }}/" +
                                    data
                                return `
                            <td>
                                <div class="d-flex">
                                    <div onClick={btnEdit(${data})} class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 svg-icon bg-hover-secondary">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "/>
                                                <rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"/>
                                            </g>
                                        </svg>
                                    </div>
                                    <div onClick="btnDelete('${url}')" class="ml-2 btn btn-icon btn-bg-light btn-active-color-primary btn-sm svg-icon bg-hover-secondary">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <path d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z" fill="#000000" fill-rule="nonzero"/>
                                                <path d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>
                                            </g>
                                        </svg>
                                    </div>
                                </div>
                            </td>`;
                            }
                        }
                    ],
                    paging: false,
                    searching: false,
                    order: [1, 'asc'],
                    columnDefs: [{
                        orderable: false,
                        targets: 0,
                        responsivePriority: 1
                    }, {
                        targets: -1,
                        orderable: false,
                        responsivePriority: 1,
                    }, {
                        targets: 4,
                        orderable: false
                    }],
                    responsive: true,
                });
            }

            var handleSearchDatatable = () => {
                const filterSearch = document.querySelector(
                    '[data-kt-ecommerce-product-filter="search"]');
                filterSearch.addEventListener('keyup', function (e) {
                    getData();
                });
                const filteTableLenght = document.querySelector(
                    '#length-table');
                filteTableLenght.addEventListener('change', function (e) {
                    getData();
                })
                const filterServiceGroup = document.querySelector(
                    '#status_account_name');
                $(filterServiceGroup).on('change', e => {
                    getData();
                });


            }


            var loadData = function (data = {}) {
                dataCustom = {
                    ...dataCustom,
                    ...data
                };
                datatable.ajax.reload();
            };
            // Public methods
            return {
                init: function () {
                    table = document.querySelector('#frm-table');
                    if (!table) {
                        return;
                    }
                    initDatatable();
                    handleSearchDatatable();
                },
                loadData: loadData
            };

            function getData(propsPage) {
                const filterSearch = document.querySelector(
                    '[data-kt-ecommerce-product-filter="search"]');
                const filteTableLenght = document.querySelector(
                    '#length-table');

                const filterStatusAccount = document.querySelector(
                    '#status_account_name');

                const arrangeRow = table.querySelector('[aria-sort]');
                var data = {};
                if (propsPage) {
                    data.page = propsPage;
                } else {
                    var paginationActive = document.querySelector(
                        '.paginate_button.page-item.active');
                    if (paginationActive) {
                        data.page = paginationActive.querySelector(
                            '[aria-controls="kt_ecommerce_pagination_table"]'
                        )
                            .getAttribute('data-dt-idx');
                    }
                }
                if (filterSearch.value != '') {
                    data.search = filterSearch.value;
                }
                data.status = filterStatusAccount.value;
                data.per_page = filteTableLenght.value;
                dataCustom = data;
                datatable.ajax.reload();
            }

            @include('layout.render_pagination')
        }();

        function btnDelete(url) {
            axios({
                method: 'GET',
                url: url,
            }).then((response) => {
                mess_success('Thông báo',
                    "Xóa thành công")
                Datatable.loadData();
            }).catch(function (error) {
                mess_error("Cảnh báo",
                    "{{ __('An error has occurred.') }}"
                )
            });
        }

        $(document).ready(function () {
            Datatable.init();
        });

        $('#import-file input[type="file"]').change(function (e) {
            var file = e.target.files[0];
            var fileName = file.name;
            var formData = new FormData();

            formData.append('csv_file', file);
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: '#',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    mess_success('Thông báo',
                        "Tải lên thành công")
                    Datatable.loadData();
                },
                error: function (xhr, status, error) {
                    mess_error("Cảnh báo",
                        "{{ __('Có lỗi xảy ra bạn hãy kiểm tra lại file') }}"
                    )
                }
            });
        });
    </script>
@endpush
