@push('js')
    {{-- @include('classManager.validate') --}}
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
                        "url": "{{ route('PhongDaoTao.HoSoChungTu.getData') }}", // Thay đổi đường dẫn đến tệp xử lý AJAX
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
                            data.search = '';
                            $('.filter-select').each(function() {
                                if ($(this).data('name') == undefined || $(this).val() == 'all') {
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
                        },
                        {
                            data: 'name',
                            render: function(data, type, row)
                            {
                                return `<a target="_blank" href="/phieu/${row['id']}">${data}</a>`
                            }
                        },
                        {
                            data: "id",
                            render: function(data, type, row) {
                                if (row['full_name'] == null) {
                                    return "";
                                }
                                return `Họ tên: ${row['full_name']} 
                                    <br/> Mã sinh viên: ${row['student_code']}
                                    <br/> Lớp: ${row['lop_name']} `;
                            }
                        },
                        {
                            data: 'key',
                        },
                        // {
                        //     data: 'file',
                        //     render: function(data, type, row) {

                        //         if (data == null) {
                        //             return '';
                        //         }
                        //         console.log(data);
                        //         return '';
                        //         urlgqcv = "{{ route('phieu.giaQuyetCongViec') }}/" + data;
                        //         if ((row['type'] == 0 && row['status'] == 6) || (row['type'] == 1 && row['status'] == 6) || row['status'] == -99) {
                        //             return `<a href="${urlgqcv}"  target="_blank" class="ki-duotone ki-abstract-4 fs-2x cursor-pointer text-dark">
                        //                 <span class="path1"></span>
                        //                 <span class="path2"></span>
                        //             </a>`
                        //         }
                        //     }
                        // },
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


        $(document).ready(function() {
            Datatable.init();
        });
    </script>
@endpush
