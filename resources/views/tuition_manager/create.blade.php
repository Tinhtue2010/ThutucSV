<div class="modal fade" id="kt_modal_{{ $target }}_target" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tạo, cập nhật học phí</h5>
                <div class="btn btn-sm btn-icon btn-active-color-primary close" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <div class="modal-body">
                <form class="form" id="form_{{ $target }}">
                    <div class="card-body">
                        @csrf
                        <div class="d-flex flex-row">
                            <div class="d-flex flex-column mb-8 fv-row col-6 pe-4" >
                                <!--begin::Label-->
                                <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                    <span class="required">Số tiền học phí</span>
                                </label>
                                <!--end::Label-->
                                <input oninput="formatNumberInput(this)" type="text" class="form-control" name="hoc_phi" />
                            </div>
                            <div class="d-flex flex-column mb-8 fv-row col-6 pe-4" id="select-parent-{{ $target }}-1">
                                <!--begin::Label-->
                                <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                    <span class="required">Lớp</span>
                                </label>
                                <!--end::Label-->
                                <select data-dropdown-parent="#select-parent-{{ $target }}-1" class="form-select" name="ma_lop" data-name="lop_id" data-control="select2"
                                    data-placeholder="Lớp">
                                    <option></option>
                                    @foreach ($lops as $item)
                                        <option value="{{ $item->ma_lop }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>


                        <div class="d-flex flex-row">
                            <div class="d-flex flex-column mb-8 fv-row col-6 pe-4"
                                id="select-parent-{{ $target }}-2">
                                <!--begin::Label-->
                                <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                    <span class="required">Học kỳ</span>
                                </label>
                                <!--end::Label-->
                                <select data-dropdown-parent="#select-parent-{{ $target }}-2" name="ky"
                                    class="form-select" data-control="select2" data-placeholder="Kỳ học">
                                    <option value="1">Kỳ 1</option>
                                    <option value="2">Kỳ 2</option>
                                </select>
                            </div>
                            <div class="d-flex flex-column mb-8 fv-row col-6 pe-4">
                                <!--begin::Label-->
                                <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                    <span class="required">Năm học</span>
                                </label>
                                <!--end::Label-->
                                <input type="text" class="form-control" name="nam"
                                    placeholder="Năm học vd: 2023-2024" />
                            </div>


                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success mr-2">{{ __('Tạo, Cập nhật') }}</button>
                        <button type="reset" class="btn btn-secondary">{{ __('Đóng') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script type="text/javascript">
        let model{{ $target }};
        let form_{{ $target }} = document.querySelector('#form_{{ $target }}');
        let validation_{{ $target }} = FormValidation.formValidation(
            form_{{ $target }}, {
                fields: {
                    ky: {
                        validators: {
                            notEmpty: {
                                message: '{{ __('Vui lòng không để trống mục này') }}'
                            }
                        }
                    },
                    nam: {
                        validators: {
                            notEmpty: {
                                message: '{{ __('Vui lòng không để trống mục này') }}'
                            }
                        }
                    }
                },
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

        $('#form_{{ $target }} .btn-secondary').click(function() {
            model{{ $target }}.hide();
        })
        $('#form_{{ $target }}').submit(function(e) {
            e.preventDefault();
            let form = $(this);
            validation_{{ $target }}.validate().then(function(status) {
                if (status === 'Valid') {
                    axios({
                        method: 'POST',
                        url: "{{ route('tuitionManager.create') }}",
                        data: form.serialize(),
                    }).then((response) => {
                        mess_success('Thông báo',
                            "Thành công")
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

        function capNhatHocPhi() {
            modalEl = document.querySelector('#kt_modal_{{ $target }}_target');
            if (!modalEl) {
                return;
            }
            model{{ $target }} = new bootstrap.Modal(modalEl);
            model{{ $target }}.show();

            form = document.querySelector('#form_{{ $target }}');
            form.querySelectorAll('[name]');
            const inputElements = form.querySelector('[name]');
        }

         function formatNumberInput(input) {
            let value = input.value.replace(/\D/g, '');
            input.value = Number(value).toLocaleString('vi-VN');
        }
    </script>
@endpush
