<div class="modal fade" id="kt_modal_{{ $target }}_target" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quyết định và danh sách</h5>
                <div class="btn btn-sm btn-icon btn-active-color-primary close" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <div class="modal-body">
                <form class="form" id="form_{{ $target }}">
                    <div class="card-body">
                        <div class="w-100 pb-3">

                        </div>
                        <div class="d-flex fw-medium w-100">
                            <table class="w-100 table-bordered">
                                <tr>
                                    <th class="col-6 text-center p-3">Quyết đinh</th>
                                    <th class="col-6 text-center p-3">Danh sách</th>
                                </tr>
                                <tr>
                                    <th class="p-3">
                                        <a target="_bank"
                                            href="{{ route('downloadQuyetDinh', ['nam_hoc' => '2024-2025', 'ky_hoc' => 2, 'type' => 4]) }}">
                                            Quyết định sinh viên được hưởng hỗ trợ tiền ăn (Điều f,g, khoản 3, điều 1)
                                        </a>
                                    </th>
                                    <th class="p-3">
                                        <a target="_bank"
                                            href="{{ route('downloadDanhSach', ['nam_hoc' => '2024-2025', 'ky_hoc' => 2, 'type' => 4]) }}">
                                            Danh sách sinh viên được hưởng hỗ trợ tiền ăn (Điều f,g, khoản 3, điều 1)
                                        </a>
                                    </th>
                                </tr>
                                <tr>
                                    <th class="p-3">
                                        <a target="_bank"
                                            href="{{ route('downloadQuyetDinh', ['nam_hoc' => '2024-2025', 'ky_hoc' => 2, 'type' => 3]) }}">
                                            Quyết định sinh viên được hưởng hỗ trợ học phí (Điều c, g, khoản 3, điều 1)'
                                        </a>
                                    </th>
                                    <th class="p-3">
                                        <a target="_bank"
                                            href="{{ route('downloadDanhSach', ['nam_hoc' => '2024-2025', 'ky_hoc' => 2, 'type' => 3]) }}">
                                            Danh sách sinh viên được hưởng hỗ trợ học phí (Điều c, g, khoản 3, điều 1)'
                                        </a>
                                    </th>
                                </tr>
                                <tr>
                                    <th class="p-3">
                                        <a target="_bank"
                                            href="{{ route('downloadQuyetDinh', ['nam_hoc' => '2024-2025', 'ky_hoc' => 2, 'type' => 4]) }}">
                                            Quyết định sinh viên được hỗ trợ chỗ ở (Điểm g, khoản 3, điều 1)
                                        </a>
                                    </th>
                                    <th class="p-3">
                                        <a target="_bank"
                                            href="{{ route('downloadDanhSach', ['nam_hoc' => '2024-2025', 'ky_hoc' => 2, 'type' => 5]) }}">
                                            Danh sách sinh viên được hỗ trợ chỗ ở (Điểm g, khoản 3, điều 1)
                                        </a>
                                    </th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script type="text/javascript">
        function quyet_dinh_danh_sach() {
            modalEl = document.querySelector('#kt_modal_{{ $target }}_target');
            if (!modalEl) {
                return;
            }
            model{{ $target }} = new bootstrap.Modal(modalEl);
            model{{ $target }}.show();
        }
    </script>
@endpush
