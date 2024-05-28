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
                            tinhTong();
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
                                var res = '';
                                @foreach (config('doituong.statusmiengiamhp') as $index => $item)
                                    if (data == {{ $item[0] }}) {
                                        res = `<span class="text-wrap lh-sm mt-1 badge badge-<?php if ($item[0] < 0) {
                                            echo 'warning';
                                        }
                                        if ($item[0] == 0) {
                                            echo 'secondary';
                                        }
                                        if ($item[0] > 0) {
                                            echo 'success';
                                        } ?>">{{ $item[1] }}</span>`;

                                    }
                                @endforeach
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
                                if (row['phantramgiam'] == 100 || row['phantramgiam'] == 70 || row['phantramgiam'] == 50) {
                                    return `<select onchange="updateTile(this,${data})" class="form-select" name="tilegiam">
                                                <option value="100" ${row['phantramgiam'] == 100 ? 'selected': ''}>100%</option>
                                                <option value="70" ${row['phantramgiam'] == 70 ? 'selected': ''}>70%</option>
                                                <option value="50" ${row['phantramgiam'] == 50 ? 'selected': ''}>50%</option>
                                            </select>`;
                                } else {
                                    return `<select onchange="updateTile(this,${data})" class="form-select" name="tilegiam">
                                                <option value="100" ${row['type_miengiamhp'] < 5  ? 'selected': ''}>100%</option>
                                                <option value="70" ${(row['type_miengiamhp'] == 5 || row['type_miengiamhp'] == 6) ? 'selected': ''}>70%</option>
                                                <option value="50" ${row['type_miengiamhp'] == 7 ? 'selected': ''}>50%</option>
                                            </select>`;
                                }
                            }
                        },
                        {
                            data: 'id',
                            render: function(data, type, row) {
                                var phantramgiam = 0;
                                if (row['phantramgiam'] == 100 || row['phantramgiam'] == 70 || row['phantramgiam'] == 50) {
                                    var phantramgiam = row['phantramgiam'] / 100;
                                } else {
                                    var phantramgiam = row['type_miengiamhp'] < 5 ? 1 : row['type_miengiamhp'] == 7 ? 0.5 : 0.7
                                }
                                var miengiam_thang = (row['hocphi'] / 5) * phantramgiam
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
                                var phantramgiam = 0;
                                if (row['phantramgiam'] == 100 || row['phantramgiam'] == 70 || row['phantramgiam'] == 50) {
                                    var phantramgiam = row['phantramgiam'] / 100;
                                } else {
                                    var phantramgiam = row['type_miengiamhp'] < 5 ? 1 : row['type_miengiamhp'] == 7 ? 0.5 : 0.7
                                }
                                var miengiamgiam_ky = row['hocphi'] * phantramgiam
                                return `<p id="miengiamgiam_ky_${data}">${miengiamgiam_ky.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })}</p>`;
                            }
                        },
                        {
                            data: 'type_miengiamhp',
                            render: function(data, type, row) {
                                switch (data) {
                                    @foreach (config('doituong.miengiamhp') as $index => $item)
                                        case {{ $index }}:
                                            return "{{ $item[1] }}";
                                        break;
                                    @endforeach
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
                            data: 'lop_name',
                        },
                        {
                            data: 'student_code',
                        },
                        {
                            data: 'id',
                            render: function(data, type, row) {
                                role = {{ Auth::user()->role }} - 2;
                                var dataRes = `<div class="d-flex flex-row">`;
                                if (row['type'] == 1 || row['type'] == 2) {
                                    if (row['status'] == 0 || row['status'] == -1 || row['status'] == 2 || row['status'] == -2) {
                                        dataRes += `<div onClick="tiepnhanhs(${data})" class="ki-duotone ki-check-square fs-2x cursor-pointer text-primary">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </div>`;
                                    }

                                    if (row['status'] == 1) {
                                        dataRes += `
                                    <div onClick="duyethoso(${data})" class="ki-duotone ki-file-added fs-2x cursor-pointer text-primary">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </div>`;
                                    }
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
                                    <div onClick="tientrinh(${data})" class="ki-duotone ki-information-2 fs-2x cursor-pointer text-warning">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </div>
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
                const filteTableLenght = document.querySelector('#length-table select');
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
                tinhTong();
                const filterSearch = document.querySelector(
                    '[data-kt-ecommerce-product-filter="search"]');
                const filteTableLenght = document.querySelector('#length-table select');


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

            function tinhTong() {
                $.ajax({
                    url: '{{ route('MienGiamHP.tinhSoLuong') }}',
                    type: 'GET',
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        var hocphi =Number(response[0].hocphi).toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });
                        $('#tinhtong').html(`
                        <h5>Tổng số lượng sinh viên : ${response[0].tong}</h5>
                        <h5>Tổng số tiền : ${hocphi}</h5>
                        `);
                    }
                });
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
            console.log("test");
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
