<div class="modal fade" id="kt_modal_{{$target}}_target" tabindex="-1" role="dialog"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Từ chối đơn') }}</h5>
                <div class="btn btn-sm btn-icon btn-active-color-primary close" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <div class="modal-body">
                <form class="form" id="form_{{$target}}">
                    <div class="card-body">
                        @csrf
                        <input type="text" name="id" class="d-none">
                        <div class="d-flex flex-column mb-8 fv-row">
                            <!--begin::Label-->
                            <label class="d-flex align-items-start flex-column fs-6 fw-semibold mb-2">
                                <span class="required">Nội dung từ chối đơn</span>
                                <span class="text-warning">Lưu ý: nội dung sẽ được thông báo cho sinh viên</span>
                            </label>
                            <!--end::Label-->
                            <textarea type="text" class="form-control" cols="5" rows="3" name="note">Đơn của bạn đã bị từ chối bởi giáo viên chủ nhiệm</textarea>
                        </div>
                        
                    </div>
                    <div class="card-footer">
                        <button type="submit"
                                class="btn btn-success mr-2">{{ __('Cập nhật') }}</button>
                        <button type="reset"
                                class="btn btn-secondary">{{ __('Nhập lại') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script type="text/javascript">
        let model{{$target}};

        let form_{{$target}} = document.querySelector('#form_{{$target}}');
        let validation_{{$target}} = FormValidation.formValidation(
            form_{{$target}}, {
                fields: {},
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            }
        );

        $('#form_{{$target}}').submit(function (e) {
            e.preventDefault();
            let form = $(this);
            validation_{{$target}}.validate().then(function (status) {
                if (status === 'Valid') {
                    axios({
                        method: 'POST',
                        url: "{{ route('GiaoVien.khongxacnhan') }}",
                        data: form.serialize(),
                    }).then((response) => {
                        mess_success('Thông báo',
                            "Hủy đơn thành công")
                        $(this).trigger("reset");
                        model{{$target}}.hide();
                        Datatable.loadData();
                    }).catch(function (error) {
                        mess_error("Cảnh báo",
                            "{{ __('Có lỗi xảy ra.') }}"
                        )
                    });
                } else {
                    mess_error("Cảnh báo",
                        "{{ __('Có lỗi xảy ra bạn cần kiểm tra lại thông tin.') }}"
                    )
                }
            });
        });


        function khongxacnhan(data) {
            modalEl = document.querySelector('#kt_modal_{{$target}}_target');
            if (!modalEl) {
                return;
            }
            model{{$target}} = new bootstrap.Modal(modalEl);
            model{{$target}}.show();
            $('[name="id"]').val(data);
            console.log(data);
            
        }
    </script>
@endpush
