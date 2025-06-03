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
                        "url": "{{ route('KeHoachTaiChinh.CheDoChinhSach.getData') }}", // Thay đổi đường dẫn đến tệp xử lý AJAX
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
                    columns: [
                        {
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
                            data: 'full_name',
                            render: function(data, type, row) {
                                return `
                                    Họ tên: ${data} <br/>
                                    Lớp: ${row['lop_name']} <br/>
                                    Mã SV: ${row['student_code']}  <br/>
                                    Ngày sinh: ${moment(row['date_of_birth']).format('DD/MM/YYYY')}  <br/>
                                `;
                            }
                        },
                        {
                            data: 'doi_tuong_chinh_sach',
                            render: function(data, type, row) {
                                let arrayDT = JSON.parse(data);
                                let result = '<div class="d-flex flex-column">';
                                arrayDT.forEach(function($item, $index) {
                                    switch ($item) {
                                        case "1":
                                            result += `<span onclick="doituong1()" class="cursor-pointer ms-0 me-auto text-wrap lh-sm mt-1 badge badge-primary">Đối tượng 1</span>`;
                                            break;
                                        case "2":
                                            result += `<span onclick="doituong2()" class="cursor-pointer ms-0 me-auto text-wrap lh-sm mt-1 badge badge-success">Đối tượng 2</span>`;
                                            break;
                                        case "3":
                                            result += `<span onclick="doituong3()" class="cursor-pointer ms-0 me-auto text-wrap lh-sm mt-1 badge badge-info">Đối tượng 3</span>`;
                                            break;
                                        case "4":
                                            result += `<span onclick="doituong4()" class="cursor-pointer ms-0 me-auto text-wrap lh-sm mt-1 badge badge-warning">Đối tượng 4</span>`;
                                            break;
                                        default:
                                            return '';
                                            break;
                                    }
                                });
                                result += '</div>'
                                return result;
                            }
                        },
                        {
                            data: "che_do_chinh_sach_data",
                            render: function(data, type, row) {
                                let array = JSON.parse(data);
                                if (array == null || array['ktx'] == null || array['ktx'] == "") {
                                    return '';
                                }
                                return `
                                    Từ: ${array['ktx']['bat_dau'] ?? ""} <br>
                                    Số tháng: ${array['ktx']['so_thang'] ?? ""} <br>
                                    Địa chỉ : ${array['ktx']['diachi'] ?? ""} <br>
                                    Khoảng cách : ${array['ktx']['km'] ?? ""} KM <br>
                                `;
                                return ''
                            }
                        },
                        {
                            data: "che_do_chinh_sach_data",
                            render: function(data, type, row) {
                                let array = JSON.parse(data);
                                if (array == null || array['mghp'] == null || array['mghp'] == "") {
                                    return '';
                                }
                                return `
                                    Tháng : ${array['mghp']['so_thang'] ?? ""} <br>
                                    % đã giảm: ${array['mghp']['phan_tram_da_giam'] ?? ""} <br>
                                    % giảm: ${array['mghp']['phan_tram_da_giam'] ?? ""} <br>
                                    Giảm/tháng: ${array['mghp']['so_tien_1_thang_giam'].toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })} <br>
                                    Giảm/kỳ: ${(array['mghp']['so_tien_1_thang_giam']*5).toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })} <br>
                                `;
                            }
                        },
                        {
                            data: 'che_do_chinh_sach_data',
                            render: function(data, type, row) {
                                let array = JSON.parse(data);
                                if (array == null || array['htta'] == null || array['htta'] == "") {
                                    return '';
                                }
                                return `
                                    Tháng : ${array['htta']['so_thang'] ?? ""} <br>
                                    Hỗ trợ/tháng: ${array['htta']['muc_ho_tro'].toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })} <br>
                                    Hỗ trợ/kỳ: ${(array['htta']['muc_ho_tro']*5).toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })} <br>
                                `;
                            }
                        },
                        {
                            data: 'che_do_chinh_sach_data',
                            render: function(data, type, row) {
                                let array = JSON.parse(data);

                                if (array == null || array['diem'] == null || array['diem'] == "") {
                                    return '';
                                }
                                return `
                                    Điểm TB : ${array['diem']['diemtb'] ?? ""} <br>
                                    Điểm rèn luyện : ${array['diem']['diemrenluyen'] ?? ""} <br>
                                `;
                            }
                        },
                        {
                            data: 'id',
                            render: function(data, type, row) {
                                role = {{ Auth::user()->role }} - 2;
                                var dataRes = `<div class="d-flex flex-row">`;
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
                    order: [1, 'desc'],
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
                url: `{{ route('PhongDaoTao.MienGiamHP.updatePercent') }}?id=${id}&muctrocapxh=${data}`,
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
