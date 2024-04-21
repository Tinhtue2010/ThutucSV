<div class="modal fade" id="kt_modal_{{ $target }}_target" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Chi tiết đơn') }}</h5>
                <div class="btn btn-sm btn-icon btn-active-color-primary close" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-column list_file"></div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script type="text/javascript">
        let model{{ $target }};
        let id{{ $target }} = 0;

        function chitiet(data) {
            modalEl = document.querySelector('#kt_modal_{{ $target }}_target');
            if (!modalEl) {
                return;
            }
            model{{ $target }} = new bootstrap.Modal(modalEl);
            id{{ $target }} = data;
            $('.list_file').html('<p class="fs-5 fw-medium">File đính kèm</p>');
            axios.get("{{ route('GiaoVien.getDataChild') }}/" + data).then(
                response => {
                    model{{ $target }}.show();
                    if(response.data[0].length == 0)
                    {
                        $('.list_file').html('')
                    }
                    response.data[0].forEach(item => {
                        $('.list_file').append(`<a href="/storage/${item[1]}" target="_blank">${item[0]}</a>`);
                    })
                    url = "{{ route('phieu.index') }}" + '/' + response.data[2];
                    $('.list_file').append(`<a href="${url}" target="_blank" class="btn btn-primary me-auto ms-0 mt-4">xem đơn</a>`)
                })

        }
    </script>
@endpush
