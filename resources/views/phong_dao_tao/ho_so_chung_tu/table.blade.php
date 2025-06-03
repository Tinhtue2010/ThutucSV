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
                            data: 'position',
                        },
                        {
                            data: 'name',
                            render: function(data, type, row) {
                                if(row['type'] == 0){
                                    return `Hồ sơ thôi học`
                                } else if(row['type'] == 1){
                                    return `Hồ sơ miễn, giảm học phí`
                                } else if(row['type'] == 2){
                                    return `Hồ sơ trợ cấp xã hội`
                                } else if(row['type'] == 3){
                                    return `Hồ sơ hỗ trợ chi phí học tập`
                                } else if(row['type'] == 4){
                                    return `Hồ sơ chế độ chính sách`
                                }
                                return `${data}`
                            }
                        },
                        {
                            data: 'ky_hoc',
                            render: function(data, type, row) {
                                return `${data}`
                            }
                        },
                        {
                            data: 'nam_hoc',
                            render: function(data, type, row) {
                                return `${data}`
                            }
                        },
                        {
                            data: 'type',
                            render: function(data, type, row) {
                                switch (Number(data)) {
                                    case 0:
                                        return "Hồ sơ thôi học";
                                    case 1:
                                        return "Hồ sơ miễn, giảm học phí theo NĐ 81";
                                    case 2:
                                        return "Hồ sơ trợ cấp xã hội";
                                    case 3:
                                        return "Hồ sơ hỗ trợ chi phí học tập theo TT 35";
                                    case 4:
                                        return "Hồ sơ chế độ chính sách theo NQ 35";
                                    case 5:
                                        return "Hồ sơ chế độ chính sách theo NQ 35";
                                    case 6:
                                        return "Hồ sơ chế độ chính sách theo NQ 35";
                                    default:
                                        return "Không rõ";
                                }

                            }
                        },
                        {
                            data: 'name',
                            render: function(data, type, row) {
                                const ky_hoc = row['ky_hoc'] ? encodeURIComponent(row['ky_hoc']) :
                                    '';
                                const nam_hoc = row['nam_hoc'] ? encodeURIComponent(row[
                                    'nam_hoc']) : '';
                                const loai = row['type'] ? encodeURIComponent(row['type']) : '';
                                const fileQuyetDinh = row['file_quyet_dinh'] ? encodeURIComponent(
                                    row['file_quyet_dinh']) : '';

                                const quyetDinhBtn = ky_hoc && nam_hoc && loai ?
                                    `<a target="_blank" href="/download-quyet-dinh?ky_hoc=${ky_hoc}&nam_hoc=${nam_hoc}&type=${loai}"><button class="btn btn-primary">Quyết định</button></a>` :
                                    '';

                                const danhSachBtn = ky_hoc && nam_hoc && loai ?
                                    `<a target="_blank" href="/download-danh-sach?ky_hoc=${ky_hoc}&nam_hoc=${nam_hoc}&type=${loai}"><button class="btn btn-primary">Danh sách</button></a>` :
                                    '';

                                return `
                                    <div style="display: flex; gap: 8px; align-items: center;">
                                        ${quyetDinhBtn}
                                        ${danhSachBtn}
                                    </div>
                                `;
                            }

                        },

                        {
                            data: 'nam_hoc',
                            render: function(data, type, row) {
                                return `Đã duyệt`
                            }
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

        document.addEventListener('click', async function(e) {
            if (e.target.classList.contains('print-btn')) {
                const fileQuyetDinh = e.target.getAttribute('data-quyet-dinh');
                const fileList = e.target.getAttribute('data-list');

                if (fileQuyetDinh || fileList) {
                    try {
                        const {
                            PDFDocument
                        } = PDFLib;
                        const mergedPdf = await PDFDocument.create();

                        // Fetch and add fileQuyetDinh
                        if (fileQuyetDinh) {
                            const response1 = await fetch(`/storage/${fileQuyetDinh}`);
                            const arrayBuffer1 = await response1.arrayBuffer();
                            const pdf1 = await PDFDocument.load(arrayBuffer1);
                            const pages1 = await mergedPdf.copyPages(pdf1, pdf1.getPageIndices());
                            pages1.forEach((page) => mergedPdf.addPage(page));
                        }

                        // Fetch and add fileList
                        if (fileList) {
                            const response2 = await fetch(`/storage/${fileList}`);
                            const arrayBuffer2 = await response2.arrayBuffer();
                            const pdf2 = await PDFDocument.load(arrayBuffer2);
                            const pages2 = await mergedPdf.copyPages(pdf2, pdf2.getPageIndices());
                            pages2.forEach((page) => mergedPdf.addPage(page));
                        }

                        // Save the merged PDF
                        const mergedPdfBytes = await mergedPdf.save();
                        const blob = new Blob([mergedPdfBytes], {
                            type: 'application/pdf'
                        });
                        const url = URL.createObjectURL(blob);

                        // Open in a new tab and print
                        const printWindow = window.open(url);
                        printWindow.onload = function() {
                            printWindow.focus();
                            printWindow.print();
                        };

                        // Revoke URL after some time to avoid memory leaks
                        setTimeout(() => URL.revokeObjectURL(url), 1000);

                    } catch (error) {
                        console.error("Error while generating the PDF:", error);
                    }
                }
            }
        });


        $(document).ready(function() {
            Datatable.init();
        });
    </script>
@endpush
