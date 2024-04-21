<div class="modal fade" id="kt_modal_{{ $target }}_target" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Tiến trình xử lý đơn ') }}</h5>
                <div class="btn btn-sm btn-icon btn-active-color-primary close" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-column list_data"></div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script type="text/javascript">
        let model{{ $target }};
        let id{{ $target }} = 0;

        function tientrinh(data) {
            modalEl = document.querySelector('#kt_modal_{{ $target }}_target');
            if (!modalEl) {
                return;
            }
            model{{ $target }} = new bootstrap.Modal(modalEl);
            id{{ $target }} = data;
            $('.list_data').html('');
            axios.get("{{ route('GiaoVien.getDataChild') }}/" + data).then(
                response => {
                    model{{ $target }}.show();
                    response.data[1].forEach(item => {
                        url_phieu = "{{route('phieu.index')}}/"+item.phieu_id;
                        $('.list_data').append(
                            '<div class="d-flex flex-column">' +
                                '<p class="fs-5 fw-medium">Giáo viên : ' + item.full_name + ' <span class="badge badge-primary">' + item.chuc_danh + '</span></p>' +
                                '<p class="fs-5 fw-medium">Trạng thái : <span class="badge badge-' + (item.status == 1 ? 'success' : item.status == 0 ? 'danger' : 'warning') + '">' + (item.status == 1 ? 'Đã xác nhận' : item.status == 0 ? 'Đã từ chối' : 'Yêu cầu bổ sung hồ sơ') + '</span></p>' +
                                '<p class="fs-5 fw-medium">Ghi chú : ' + item.note + '</p>' +
                                (item.phieu_id != null ? '<a href="' + url_phieu + '" target="_blank" class="btn btn-success me-auto ms-0">Xem phiếu</a>' : '') +
                                '<hr>' +
                            '</div>'
                        );
                    });
                    console.log(response.data);
                })

        }
    </script>
@endpush
