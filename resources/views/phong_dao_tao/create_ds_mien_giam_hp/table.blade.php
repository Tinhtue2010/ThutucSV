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
                        "url": "{{ route('PhongDaoTao.MienGiamHP.getData') }}", // Thay đổi đường dẫn đến tệp xử lý AJAX
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
                            data: 'status',
                            render: function(data, type, row) {
                                var res ='';
                                if (data == 1) {
                                    res = `<span class="mt-1 badge badge-warning">Chưa thêm</span>`;
                                }
                                if (data == 2) {
                                    res = `<span class="mt-1 badge badge-success">Đã thêm</span>`;
                                }
                                return res;
                            }
                        },
                        {
                            data: 'full_name'
                        },
                        {
                            data: 'hocphi',
                            render: function(data, type, row) {
                                return `<p id="hocphi_${row['id']}" data-hocphi="${data}">${data.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })}</p>`;;
                            }
                        },
                        {
                            data: 'id',
                            render: function(data, type, row) {
                                return `<input value="${row['phantramgiam']}" onkeyup="updateTile(this,${data})" data-id="${data}" name="tilegiam" class="w-100 p-3" type="number" max="100" min="0">`;
                            }
                        },
                        {
                            data: 'id',
                            render: function(data, type, row) {
                                var miengiam_thang = (row['hocphi'] / 5) * (row['phantramgiam'] / 100)
                                return `<p id="miengiam_thang_${data}">${miengiam_thang.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })}</p>`;
                            }
                        },
                        {
                            data: 'id',
                            render: function(data, type, row) {
                                return `5 Tháng`;
                            }
                        },
                        {
                            data: 'id',
                            render: function(data, type, row) {
                                var miengiamgiam_ky = row['hocphi'] * (row['phantramgiam'] / 100)
                                return `<p id="miengiamgiam_ky_${data}">${miengiamgiam_ky.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })}</p>`;
                            }
                        },
                        {
                            data: 'type_miengiamhp',
                            render: function(data, type, row) {
                                switch (data) {
                                    case 1:
                                        return "Người có công với cách mạng";
                                        break;
                                    case 2:
                                        return 'Sinh viên khuyết tật';
                                        break;
                                    case 3:
                                        return 'Sinh viên mồ côi';
                                        break;
                                    case 4:
                                        return 'Hộ nghèo, cận nghèo';
                                        break;
                                    case 5:
                                        return 'SV dân tộc thiểu số ít người';
                                        break;
                                    case 6:
                                        return 'SV ngành múa, nhạc cụ truyền thống';
                                        break;
                                    case 7:
                                        return 'SV dân tộc thiểu số(không phải dân tộc ít người)';
                                        break;
                                    case 8:
                                        return 'Con của công nhân viên chức tai nạn nghề';
                                        break;
                                    default:
                                        return '';
                                        break;
                                }
                            }
                        },
                        {
                            data: 'date_of_birth',
                            render: function(data, type, row) {
                                return moment(data).format('DD/MM/YYYY');
                            }
                        },
                        {
                            data: 'id',
                            render: function(data, type, row) {
                                role = {{ Auth::user()->role }} - 2;
                                var dataRes = `<div class="d-flex flex-row">`;
                                if (row['type'] == 1) {
                                    dataRes += `
                                    <div onClick="bosunghs(${data})" class="ki-duotone ki-update-folder fs-2x cursor-pointer text-danger">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </div>`;
                                    dataRes += `<div onClick="tuchoihs(${data})" class="ki-duotone ki-minus-square fs-2x cursor-pointer text-danger">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </div>`;
                                }
                                dataRes += `
                                    <div onClick="chitiet(${data})"  class="ki-duotone ki-document fs-2x cursor-pointer text-dark">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </div>
                                </div>
                                `;
                                return dataRes;

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
                    '#length-table');
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
                    '#length-table');


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
        let timeoutId;

        function updateData(data, id) {
            axios({
                method: 'GET',
                url: `{{ route('PhongDaoTao.MienGiamHP.updatePercent') }}?id=${id}&phantramgiam=${data}`,
            }).then((response) => {}).catch(function(error) {
                mess_error("Cảnh báo",
                    "{{ __('Có lỗi xảy ra.') }}"
                )
            });
        }

        function debounce(func, delay) {
            return function() {
                const context = this;
                const args = arguments;
                clearTimeout(timeoutId);
                timeoutId = setTimeout(() => func.apply(context, args), delay);
            };
        }

        function updateTile(data, id) {
            const debouncedUpdate = debounce(updateData, 500);
            var hocphi = $("#hocphi_" + id).data('hocphi');
            var percent = data.value;
            var miengiam_thang = (hocphi / 5) * (percent / 100)
            var miengiamgiam_ky = hocphi * (percent / 100)
            $("#miengiamgiam_ky_" + id).html(miengiamgiam_ky.toLocaleString('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }))
            $("#miengiam_thang_" + id).html(miengiam_thang.toLocaleString('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }))
            debouncedUpdate(percent, id);
        }
    </script>
@endpush
