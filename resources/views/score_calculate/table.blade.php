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
                        "url": "{{ route('scoreCalculate.getData') }}", // Thay đổi đường dẫn đến tệp xử lý AJAX
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
                            data.nam_hoc_goc = @json($nam_hoc_goc);
                            data.ky_hoc_goc = {{ $ky_hoc_goc }};
                            data.topPercent = {{ $topPercent }};
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
                        {
                            data: 'stop_study_types',
                            render: function(data, type, row) {
                                switch (Number(data)) {
                                    case 1:
                                        return "Hồ sơ miễn, giảm học phí theo NĐ 81";
                                    case 2:
                                        return "Hồ sơ trợ cấp xã hội";
                                    case 3:
                                        return "Hồ sơ hỗ trợ chi phí học tập theo TT 35";
                                    case 4:
                                        return "";
                                    default:
                                        return "";
                                }

                            }
                        },
                        {
                            data: 'id',
                            render: function(data, type, row) {
                                return `
                                <div class="d-flex justify-content-center flex-shrink-0">
                                    <button type="button" class="btn btn-sm btn-danger btn-xoa" data-id="${row.student_id}">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                </div>`;
                            }
                        },

                    ],
                    paging: false,
                    searching: false,
                    order: [8, 'desc'],
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
                $(document).on('click', '.btn-xoa', function() {
                    const studentId = $(this).data('id');
                    addToHiddenInput(studentId);
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
                $('#id_da_xoa').on('change', e => {
                    getData();
                });
                $('#btn-add').on('click', e => {
                    getData();
                });

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
                data.id_da_xoa = document.getElementById('id_da_xoa').value;
                data.so_luong_sv_them = document.getElementById('so_luong_sv_them').value;
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

        function addToHiddenInput(studentId) {
            const input = document.getElementById('id_da_xoa');
            let current = input.value ? input.value.split(',') : [];

            if (!current.includes(studentId)) {
                current.push(studentId);
                input.value = current.join(',');
            }
        }

        function addToHiddenInsert(studentId) {
            const input = document.getElementById('so_luong_sv_them');
            let current = parseInt(input.value) || 0;
            input.value = current + 1;

            axios({
                method: 'GET',
                url: "{{ route('scoreCalculate.findNewAddedStudent') }}",
                params: {
                    nam_hoc: @json($nam_hoc_goc),
                    ky_hoc: {{ $ky_hoc_goc }},
                    topPercent: {{ $topPercent }},
                    so_luong_sv_them: input.value
                }
            }).then((response) => {
                const data = response.data;
                const student = data.student;
                const message = `Thêm thành công sinh viên:<br>${student.full_name} (${student.student_code})<br>Điểm trung bình: ${student.diem_ht}<br>Điểm rèn luyện: ${student.diem_rl}`;

                mess_success('Thông báo', message);
                Datatable.loadData();
            }).catch(function(error) {
                mess_error("Cảnh báo", "{{ __('An error has occurred.') }}");
            });
        }


        function handleDownload(event) {
            event.preventDefault();

            const link = event.target;
            const baseUrl = link.getAttribute('data-base-url');
            const idDaXoa = document.getElementById('id_da_xoa').value;
            const soLuongSvThem = document.getElementById('so_luong_sv_them').value;

            let params = new URLSearchParams();

            if (idDaXoa) {
                params.append('id_da_xoa', idDaXoa);
            }

            if (soLuongSvThem) {
                params.append('so_luong_sv_them', soLuongSvThem);
            }

            let finalUrl = baseUrl;
            if (params.toString()) {
                const separator = baseUrl.includes('?') ? '&' : '?';
                finalUrl = baseUrl + separator + params.toString();
            }

            window.open(finalUrl, '_blank');
        }


        function handleUpdate(event) {
            event.preventDefault();

            const link = event.target;
            const baseUrl = link.getAttribute('data-base-url');
            const idDaXoa = document.getElementById('id_da_xoa').value;
            const soLuongSvThem = document.getElementById('so_luong_sv_them').value;

            // Build query parameters
            let params = new URLSearchParams();
            if (idDaXoa) {
                params.append('id_da_xoa', idDaXoa);
            }
            if (soLuongSvThem) {
                params.append('so_luong_sv_them', soLuongSvThem);
            }

            let finalUrl = baseUrl;
            if (params.toString()) {
                const separator = baseUrl.includes('?') ? '&' : '?';
                finalUrl = baseUrl + separator + params.toString();
            }

            axios({
                method: 'GET',
                url: finalUrl,
            }).then((response) => {
                mess_success('Thông báo', "Cập nhật thành công");
                Datatable.loadData();
            }).catch(function(error) {
                mess_error("Cảnh báo", "{{ __('An error has occurred.') }}");
            });
        }
    </script>
@endpush
