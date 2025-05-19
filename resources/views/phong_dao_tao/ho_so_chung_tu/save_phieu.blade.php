<div class="modal fade" id="kt_modal_{{ $target }}_target" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Lưu trữ hồ sơ') }}</h5>
                <div class="btn btn-sm btn-icon btn-active-color-primary close" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <div class="modal-body">
                <form class="form" id="form_{{ $target }}">
                    <div class="card-body">
                        @csrf
                        <input type="text" name="id" class="d-none">
                        <div class="col-12">
                            <label for="kt_td_picker_linked_2_input" class="form-label">Bạn có chắc muốn lưu trữ toàn bộ hồ sơ chứng từ hiện tại <br> Hành động này sẽ khoá tất cả hồ sơ đã xử lý hoàn tất về trạng thái lưu trữ <br> Tất cả file được lưu tại hệ thống sẽ được chuyển qua drive <br> Nếu lưu trữ hồ sơ chứng từ sẽ không thể tiếp tục quá trình xử lý được nữa</label><br>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" name="RHS" type="checkbox" value="1" checked />
                                    <label class="form-check-label" >
                                        Đơn xin rút hồ sơ
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" name="DHP" type="checkbox" value="1" checked />
                                    <label class="form-check-label">
                                        Chế độ chính sách miễn giảm học phí
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" name="TCXH" type="checkbox" value="1" checked />
                                    <label class="form-check-label" >
                                        Trợ cấp xã hội
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" name="CDCS" type="checkbox" value="1" checked />
                                    <label class="form-check-label" >
                                        Chế độ chính sách hỗ trợ tiền săn, học phí, chỗ ở
                                    </label>
                                </div>
                            </div>
                        </div>
                        <br>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success mr-2">{{ __('Xác nhận') }}</button>
                        <button type="reset" class="btn btn-secondary">{{ __('Hủy') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script type="text/javascript">
        let id = 0;
        let model{{ $target }};

        let form_{{ $target }} = document.querySelector('#form_{{ $target }}');
        let validation_{{ $target }} = FormValidation.formValidation(
            form_{{ $target }}, {
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

        $('#form_{{ $target }}').submit(function(e) {
            e.preventDefault();
            let form = $(this);
            validation_{{ $target }}.validate().then(async function(status) {
                if (status === 'Valid') {
                    axios({
                        method: 'POST',
                        url: "{{route('PhongDaoTao.HoSoChungTu.saveAll')}}",
                        data: form.serialize(),
                    }).then((response) => {
                        mess_success('Thông báo',
                            "Đã lưu trữ hồ sơ thành công")
                        $(this).trigger("reset");
                        model{{ $target }}.hide();
                        Datatable.loadData();
                    }).catch(function(error) {
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


        function savePhieu() {
            data = 0;
            modalEl = document.querySelector('#kt_modal_{{ $target }}_target');
            if (!modalEl) {
                return;
            }
            model{{ $target }} = new bootstrap.Modal(modalEl);
            model{{ $target }}.show();
            $('[name="id"]').val(data);
        }
    </script>
@endpush
