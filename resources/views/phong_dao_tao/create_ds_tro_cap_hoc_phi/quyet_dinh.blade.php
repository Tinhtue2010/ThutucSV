<div class="modal fade" id="kt_modal_{{ $target }}_target" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tạo quyết định trợ cấp chi phí học tập</h5>
                <div class="btn btn-sm btn-icon btn-active-color-primary close" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <div class="modal-body">
                <form class="form" id="form_{{ $target }}">
                    <div class="card-body">
                        @csrf
                        <div class="d-flex flex-row">
                            <div class="d-flex flex-column mb-8 fv-row col-6 pe-4">
                                <!--begin::Label-->
                                <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                    <span class="required">Số quyết định</span>
                                </label>
                                <!--end::Label-->
                                <input type="text" class="form-control" name="so_QD" />
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label required">Thời gian</label>
                                <div class="input-group log-event" id="kt_td_picker_{{ $target }}_1"
                                    data-td-target-input="nearest" data-td-target-toggle="nearest">
                                    <input readonly name="thoi_gian_tao" type="text" class="form-control"
                                        data-td-target="#kt_td_picker_{{ $target }}_1" />
                                    <span class="input-group-text" data-td-target="#kt_td_picker_{{ $target }}_1"
                                        data-td-toggle="datetimepicker">
                                        <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span
                                                class="path2"></span></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-row">
                            <div class="d-flex flex-column mb-8 fv-row col-6 pe-4"
                                id="select-parent-{{ $target }}-1">
                                <!--begin::Label-->
                                <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                    <span class="required">Học kỳ</span>
                                </label>
                                <!--end::Label-->
                                <select data-dropdown-parent="#select-parent-{{ $target }}-1" name="ky"
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
                        <div class="d-flex flex-column mb-8 fv-row col-12 pe-4">
                            <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                <span class="">Tóm tắt tình hình</span>
                            </label>
                            <textarea name="tom_tat" class="form-control" cols="30" rows="5"></textarea>
                        </div>
                    </div>
                    <p class="text-warning"><b>Lưu ý:</b> Các hồ sơ sinh viên đã tiếp nhận sẽ tự động được thêm vào danh
                        sách miễn giảm học phí, nếu không muốn thêm vào danh sách cần chuyển đơn thành từ chối hoặc bổ
                        sung hồ sơ</p>
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
        const fields = {
            so_QD: {
                validators: {
                    notEmpty: {
                        message: '{{ __('Vui lòng không để trống mục này') }}'
                    }
                }
            },
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
        };
        let form_{{ $target }} = document.querySelector('#form_{{ $target }}');
        let validation_{{ $target }} = FormValidation.formValidation(
            form_{{ $target }}, {
                fields: fields,
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
                        url: "{{ route('PhongDaoTao.TroCapHocPhi.createQuyetDinh') }}",
                        data: form.serialize(),
                    }).then((response) => {
                        mess_success('Thông báo',
                            "Thành công")
                        $(this).trigger("reset");
                        location.reload();
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
        $(document).ready(function() {

        })

        function taoQuyetDinhMGHP() {
            modalEl = document.querySelector('#kt_modal_{{ $target }}_target');
            if (!modalEl) {
                return;
            }
            model{{ $target }} = new bootstrap.Modal(modalEl);
            model{{ $target }}.show();

            form = document.querySelector('#form_{{ $target }}');
            form.querySelectorAll('[name]');
            const inputElements = form.querySelector('[name]');


            axios.get("{{ route('PhongDaoTao.TroCapHocPhi.getQuyetDinh') }}").then(
                response => {
                    form.querySelector('[name="so_QD"]').value = response.data["so_QD"] ?? "";
                    form.querySelector('[name="tom_tat"]').value = response.data["tom_tat"] ?? "";
                    form.querySelector('[name="nam"]').value = response.data["nam"] ?? "";

                    $('select[name="ky"]').val(response.data["ky"]).trigger('change');


                    let date;

                    if (response.data && response.data["thoi_gian_tao_ngay"] && response.data["thoi_gian_tao_thang"] &&
                        response.data["thoi_gian_tao_nam"]) {
                        const day = parseInt(response.data["thoi_gian_tao_ngay"]);
                        const month = parseInt(response.data["thoi_gian_tao_thang"]) - 1;
                        const year = parseInt(response.data["thoi_gian_tao_nam"]);
                        date = new Date(year, month, day);
                    } else {
                        date = new Date();
                    }

                    const linkedPicker1Element = document.getElementById("kt_td_picker_{{ $target }}_1");
                    const linked1 = new tempusDominus.TempusDominus(linkedPicker1Element, {
                        display: {
                            components: {
                                calendar: true,
                                date: true,
                                month: true,
                                year: true,
                                decades: true,
                                clock: false,
                                hours: false,
                                minutes: false,
                                seconds: false
                            }
                        },
                        localization: {
                            locale: "vi-VN",
                            startOfTheWeek: 1,
                            format: "dd/MM/yyyy",
                        },
                        defaultDate: date
                    });
                    linkedPicker1Element.addEventListener(tempusDominus.Namespace.events.change, (e) => {
                        const selectedDate = e.detail.date;
                        const maxDate = new Date(selectedDate);
                    });
                })
        }
    </script>
@endpush
