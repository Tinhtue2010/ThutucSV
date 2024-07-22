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
                            <a class="w-100 py-2" target="_bank" href="{{ route('phieu.index', ['id' => 'PTQT40']) }}">
                                Phiếu trình
                            </a>
                        </div>
                        <div class="d-flex fw-medium w-100">
                            <table class="w-100 table-bordered">
                                <tr>
                                    <th class="col-6 text-center p-3">Quyết đinh</th>
                                    <th class="col-6 text-center p-3">Danh sách</th>
                                </tr>
                                <tr>
                                    <th class="p-3">
                                        <a target="_bank" href="{{ route('phieu.index', ['id' => 'QDCDTA0']) }}">
                                            Quyết định sinh viên được hưởng hỗ trợ tiền ăn (Điều f,g, khoản 3, điều 1)
                                        </a>
                                    </th>
                                    <th class="p-3">
                                        <a target="_bank" href="{{ route('phieu.index', ['id' => 'DSCDTA0']) }}">
                                            Danh sách sinh viên được hưởng hỗ trợ tiền ăn (Điều f,g, khoản 3, điều 1)
                                        </a>
                                    </th>
                                </tr>
                                <tr>
                                    <th class="p-3">
                                        <a target="_bank" href="{{ route('phieu.index', ['id' => 'QDCDHP0']) }}">
                                            Quyết định sinh viên được hưởng hỗ trợ học phí (Điều c, g, khoản 3, điều 1)'
                                        </a>
                                    </th>
                                    <th class="p-3">
                                        <a target="_bank" href="{{ route('phieu.index', ['id' => 'DSCDHP0']) }}">
                                            Danh sách sinh viên được hưởng hỗ trợ học phí (Điều c, g, khoản 3, điều 1)'
                                        </a>
                                    </th>
                                </tr>
                                <tr>
                                    <th class="p-3">
                                        <a target="_bank" href="{{ route('phieu.index', ['id' => 'QDCDKTX10']) }}">
                                            Quyết định sinh viên được hỗ trợ chỗ ở (Điểm g, khoản 3, điều 1)
                                        </a>
                                    </th>
                                    <th class="p-3">
                                        <a target="_bank" href="{{ route('phieu.index', ['id' => 'DSCDKTX10']) }}">
                                            Danh sách sinh viên được hỗ trợ chỗ ở (Điểm g, khoản 3, điều 1)
                                        </a>
                                    </th>
                                </tr>
                                <tr>
                                    <th class="p-3">
                                        <a target="_bank" href="{{ route('phieu.index', ['id' => 'QDCDKTX40']) }}">
                                            Quyết định sinh viên được hỗ trợ chỗ ở (Điểm e, khoản 3, điều 1)
                                        </a>
                                    </th>
                                    <th class="p-3">
                                        <a target="_bank" href="{{ route('phieu.index', ['id' => 'DSCDKTX40']) }}">
                                            Danh sách sinh viên được hỗ trợ chỗ ở (Điểm e, khoản 3, điều 1)
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
