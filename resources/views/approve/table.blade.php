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
                        "url": "{{ route('approve.getData') }}", // Thay đổi đường dẫn đến tệp xử lý AJAX
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
                            data: 'id'
                        },
                        {
                            data: 'full_name'
                        },
                        {
                            data: 'lop_name'
                        },
                        {
                            data: 'note'
                        },
                        {
                            data: 'status',
                            render: function(data, type, row) {
                                if(data == -1)
                                {
                                    return "Đơn đã bị từ chối"
                                }
                                if (data == 0) {
                                    return "Chưa được xác nhận"
                                }
                                if (data == 1) {
                                    return "GV chủ nhiệm đã xác nhận";
                                }
                                if (data == 2) {
                                    return "Khoa đã xác nhận";
                                }
                                if (data == 3) {
                                    return "CTHSSV đã xác nhận";
                                }
                                if (data == 4) {
                                    return "Lãnh đạo CTHSSV đã xác nhận";
                                }
                                if (data == 5) {
                                    return "Lãnh đạo trường đã xác nhận";
                                }
                                return '';
                            }
                        },
                        {
                            data: 'id',
                            render: function(data, type, row) {
                                role = {{ Auth::user()->role }} - 2;
                                url = "{{route('approve.viewPdf')}}" + '/'+data;
                                if ((row['status'] == 0 || row['status'] == 1) && (role == 0 || role == 1)) {
                                    return `
                                        <div class="d-flex flex-column">
                                            <div onClick={xacnhan(${data})} class="btn btn-primary">Xác nhận</div>
                                            <div onClick={khongxacnhan(${data})} class="mt-2 btn btn-danger">Không xác nhận</div>
                                            <a href="${url}" class="mt-2 btn btn-secondary" target="_blank">Xem phiếu</a>
                                        </div>`;
                                }

                                if(row['status'] == role)
                                {
                                    return `
                                        <div class="d-flex flex-column">
                                            <div onClick={xacnhan(${data})} class="btn btn-primary">Xác nhận</div>
                                            <div onClick={xacnhan(${data})} class="mt-2 btn btn-primary">Xác nhận</div>
                                        </div>`;
                                }
                                return '';

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

        function xacnhan(id) {
            axios({
                method: 'GET',
                url: "{{route('approve.xacnhan')}}"+'/'+id,
            }).then((response) => {
                mess_success('Thông báo',
                    "Duyệt thành công")
                Datatable.loadData();
            }).catch(function(error) {
                mess_error("Cảnh báo",
                    "{{ __('An error has occurred.') }}"
                )
            });
        }

        
        function khongxacnhan(id) {
            axios({
                method: 'GET',
                url: "{{route('approve.khongxacnhan')}}"+'/'+id,
            }).then((response) => {
                mess_success('Thông báo',
                    "Đã hủy đơn thành công")
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

        $('#import-file input[type="file"]').change(function(e) {
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
                success: function(response) {
                    mess_success('Thông báo',
                        "Tải lên thành công")
                    Datatable.loadData();
                },
                error: function(xhr, status, error) {
                    mess_error("Cảnh báo",
                        "{{ __('Có lỗi xảy ra bạn hãy kiểm tra lại file') }}"
                    )
                }
            });
        });
    </script>
@endpush
