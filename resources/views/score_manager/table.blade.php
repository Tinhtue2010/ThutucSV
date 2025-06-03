@push('js')
    {{-- @include('studentManager.validate') --}}
    <script type="text/javascript">
        var Datatable = function() {
            // Shared variables
            var table;
            var datatable;
            var dataCustom;

            // Private functions
            var initDatatable = function() {
                // Init datatable --- more info on datatables: https://datatables.net/manual/
                datatable = $(table).DataTable({
                    "info": false,
                    "serverSide": true,
                    "ajax": {
                        "url": "{{ route('scoreManager.getData') }}", // Thay đổi đường dẫn đến tệp xử lý AJAX
                        "type": "GET",
                        "data": function(data) {
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
                            {{ request('gvcn') === 'true' ? 'data.gvcn = true;' : '' }}
                            {{ request('khoa') === 'true' ? 'data.khoa = true;' : '' }}
                            data.search = '';
                            $('.filter-select').each(function() {
                                if ($(this).data('name') == undefined || $(this).val() ==
                                    'all') {
                                    return;
                                }
                                var name_filter = $(this).data('name');
                                data[name_filter] = $(this).val();
                            });
                            return $.extend({}, data,
                                dataCustom);
                        },
                        "dataSrc": function(response) {
                            renderPagination(response.page,
                                response.max_page);
                            const infoSelector = document.getElementById('datatable-info');
                            if (infoSelector) {
                                infoSelector.innerText =
                                    `Tổng số bản ghi: ${response.total_items}, Số bản ghi trang hiện tại: ${response.current_page_items_count}`;
                            }
                            return response.data;
                        },
                    },
                    columns: [{
                            data: 'id',
                            render: function(data, type, row) {
                                return '';
                            }
                        },
                        {
                            data: 'id',
                            render: function(data, type, row) {
                                return `<input value="${data}" type="checkbox" class="select-row-table"/>`;
                            }
                        },
                        {
                            data: 'student_code'
                        },
                        {
                            data: 'full_name'
                        },
                        {
                            data: 'lop_name'
                        },
                        {
                            data: 'khoa_name'
                        },
                        {
                            data: 'ky_hoc'
                        },
                        {
                            data: 'nam_hoc'
                        },
                        {
                            data: 'diem_ht'
                        },
                        {
                            data: 'xep_loai_ht'
                        },
                        {
                            data: 'diem_rl'
                        },
                        {
                            data: 'xep_loai_rl'
                        },
                        {
                            data: 'xep_loai'
                        },
                        {
                            data: 'so_tc_ht'
                        },

                    ],
                    paging: false,
                    searching: false,
                    order: [2, 'asc'],
                    columnDefs: [{
                        orderable: false,
                        targets: 0,
                        responsivePriority: 1
                    }, {
                        orderable: false,
                        targets: 1,
                        responsivePriority: 1
                    }, {
                        targets: -1,
                        orderable: false,
                        responsivePriority: 1,
                    }],
                    responsive: true,
                });
            }

            var handleSearchDatatable = () => {
                const filterSearch = document.querySelector(
                    '[data-kt-ecommerce-product-filter="search"]');
                filterSearch.addEventListener('keyup', function(e) {
                    getData();
                });
                const filteTableLenght = document.querySelector(
                    '#length-table select');
                filteTableLenght.addEventListener('change', function(e) {
                    getData();
                })
                const filterServiceGroup = document.querySelector(
                    '#status_account_name');
                $(filterServiceGroup).on('change', e => {
                    getData();
                });

                $('.filter-select').change(function() {
                    getData();
                })

            }



            var loadData = function(data = {}) {
                dataCustom = {
                    ...dataCustom,
                    ...data
                };
                datatable.ajax.reload();
            };
            // Public methods
            return {
                init: function() {
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
                    '#length-table select');


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
            }).catch(function(error) {
                mess_error("Cảnh báo",
                    "{{ __('An error has occurred.') }}"
                )
            });
        }

        $(document).ready(function() {
            Datatable.init();
        });

        function selectAll(e) {
            if (e.checked == true) {
                $('.select-row-table').prop('checked', true);
            } else
                $('.select-row-table').prop('checked', false);
        }
    </script>
@endpush
