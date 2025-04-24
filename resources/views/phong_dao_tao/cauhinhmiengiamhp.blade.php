<div class="modal fade" id="kt_modal_{{ $target }}_target" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Cấu hình danh sách miến giảm học phí') }}</h5>
                <div class="btn btn-sm btn-icon btn-active-color-primary close" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <div class="modal-body">
                <form class="form" id="form_{{ $target }}">
                    <div class="card-body d-flex flex-column">
                        @csrf
                        <input type="text" name="id" class="d-none">
                        <div class="d-flex flex-column mb-8 fv-row">
                            <!--begin::Label-->
                            <label class="d-flex align-items-start flex-column fs-6 fw-semibold mb-2">
                                <span>Số tháng miễn giảm học phí</span>
                            </label>
                            <!--end::Label-->
                            <input type="number" class="form-control" name="month">
                        </div>
                        <span class="fw-bold fs-5 pb-2">Mức học phí/tháng</span>
                        @foreach ($lop as $item)
                            <div class="d-flex flex-column mb-8 fv-row">
                                <!--begin::Label-->
                                <label class="d-flex align-items-start flex-column fs-6 fw-semibold mb-2">
                                    <span>Lớp {{ $item->name}}, ngành {{ $item->nganh}}</span>
                                </label>
                                <!--end::Label-->
                                <input type="number" class="form-control" name="hp_{{ $item->id}}">
                            </div>
                        @endforeach
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success mr-2">{{ __('Lưu') }}</button>
                        <button type="reset" class="btn btn-secondary">{{ __('Nhập lại') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('js')
<script type="text/javascript">
    let  model{{$target}};

    let form_{{$target}} = document.querySelector('#form_{{ $target }}');
    let validation_{{$target}} = FormValidation.formValidation(
        form_{{$target}}, {
            fields: {}
            , plugins: {
                trigger: new FormValidation.plugins.Trigger()
                , bootstrap: new FormValidation.plugins.Bootstrap5({
                    rowSelector: '.fv-row'
                    , eleInvalidClass: ''
                    , eleValidClass: ''
                })
            }
        }
    );
    $('#form_{{ $target }}').submit(function(e) {
        e.preventDefault();
        let form = $(this);
        validation_{{$target}}.validate().then(function(status) {
            if (status === 'Valid') {
                axios({
                    method: 'POST'
                    , url: "{{ route('PhongDaoTao.tuchoihs') }}"
                    , data: form.serialize()
                , }).then((response) => {
                    mess_success('Thông báo'
                        , "Thành công")
                    $(this).trigger("reset");
                    model{{$target}}.hide();
                    Datatable.loadData();
                }).catch(function(error) {
                    mess_error("Cảnh báo"
                        , "{{ __('Có lỗi xảy ra.') }}"
                    )
                });
            } else {
                mess_error("Cảnh báo"
                    , "{{ __('Có lỗi xảy ra bạn cần kiểm tra lại thông tin.') }}"
                )
            }
        });
    });


    function cauhinhmiengiamhp(data) {
        modalEl = document.querySelector('#kt_modal_{{ $target }}_target');
        if (!modalEl) {
            return;
        }
        model{{$target}} = new bootstrap.Modal(modalEl);

        form = document.querySelector('#form_{{ $target }}');
        form.querySelectorAll('[name]');
        const inputElements = form.querySelectorAll('[name]');
        $('[name="id"]').val(data);
        inputElements.forEach(e => {
            if (e.name != '_token' && e.name != 'id') {
                e.value = '';

            }
        });
        model{{$target}}.show();
    }

</script>
@endpush
