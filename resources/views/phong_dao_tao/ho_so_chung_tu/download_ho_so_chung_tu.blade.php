<div class="modal fade" id="kt_modal_{{ $target }}_target" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Xuất thống kê báo cáo') }}</h5>
                <div class="btn btn-sm btn-icon btn-active-color-primary close" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <div class="modal-body">
                <form class="form" id="form_{{ $target }}"
                    action="{{ route('PhongDaoTao.HoSoChungTu.download') }}" method="POST">
                    <div class="card-body">
                        @csrf
                        <input type="text" name="id" class="d-none">
                        <div class="col-12">
                            <div class="d-flex flex-row">
                                <div class="d-flex flex-column mb-8 fv-row col-6 pe-4"
                                    id="select-parent-{{ $target }}-1">
                                    <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                        <span class="required">Học kỳ</span>
                                    </label>
                                    <select data-dropdown-parent="#select-parent-{{ $target }}-1" name="ky"
                                        class="form-select" data-control="select2" data-placeholder="Kỳ học">
                                        <option value="1">Kỳ 1</option>
                                        <option value="2">Kỳ 2</option>
                                    </select>
                                </div>
                                <div class="d-flex flex-column mb-8 fv-row col-6 pe-4">
                                    <label class="d-flex align-items-center fs-6 fw-semibold mb-2"
                                        id="select-parent-{{ $target }}-2">
                                        <span class="required">Năm học</span>
                                    </label>
                                    <select name="nam_hoc" class="form-select filter-select" data-control="select2"
                                        data-placeholder="Năm học"
                                        data-dropdown-parent="#select-parent-{{ $target }}-2">
                                        @php
                                            $currentYear = date('Y');
                                            $currentMonth = date('m');
                                            if ($currentMonth <= 6) {
                                                $selectedYear = $currentYear - 1 . '-' . $currentYear;
                                            } else {
                                                $selectedYear = $currentYear . '-' . ($currentYear + 1);
                                            }
                                        @endphp

                                        @for ($year = 2010; $year <= 2100; $year++)
                                            @php
                                                $optionValue = $year . '-' . ($year + 1);
                                            @endphp
                                            <option value="{{ $optionValue }}"
                                                {{ $optionValue == $selectedYear ? 'selected' : '' }}>
                                                {{ $optionValue }}
                                            </option>
                                        @endfor

                                    </select>
                                </div>
                            </div>

                            <div class="d-flex flex-row">
                                <div class="d-flex flex-column mb-8 fv-row col-6 pe-4">
                                    <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                        <span class="required">Tải hồ sơ đính kèm</span>
                                    </label>
                                    <select name="tai_ho_so" class="form-select" placeholder="Kỳ học">
                                        <option value="1">Không tải hồ sơ đính kèm</option>
                                        <option value="2">Tải hồ sơ đính kèm</option>
                                    </select>
                                </div>
                                <div class="d-flex flex-column mb-8 fv-row col-6 pe-4">
                                    <label class="d-flex align-items-center fs-6 fw-semibold mb-2"
                                        id="select-parent-{{ $target }}-3">
                                        <span class="required">Loại hồ sơ</span>
                                    </label>
                                    <select name="loai_ho_so" class="form-select filter-select" data-control="select2"
                                        data-placeholder="Loại hồ sơ"
                                        data-dropdown-parent="#select-parent-{{ $target }}-3">
                                        <option value="1">Sổ theo dõi sinh viên rút hồ sơ</option>
                                        <option value="2">Sổ theo dõi kết quả giải quyết chế độ miễn giảm học phí</option>
                                        <option value="3">Sổ theo dõi kết quả giải quyết chế độ Trợ cấp Xã hội</option>
                                        <option value="4">Sổ theo dõi kết quả giải quyết chế độ Hỗ trợ chi phí học tập</option>
                                        <option value="5">Sổ theo dõi kết quả giải quyết Miễn phí chỗ ở KTX</option>
                                        <option value="6">Sổ theo dõi kết quả giải quyết Chế độ hỗ trợ học phí</option>
                                        <option value="7">Sổ theo dõi kết quả giải quyết chế độ Hỗ trợ học, đồ dùng học tập</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success mr-2">{{ __('Tải xuống') }}</button>
                        <button type="reset" class="btn btn-secondary">{{ __('Hủy') }}</button>
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
            e.preventDefault(); // Ngừng gửi form mặc định

            let form = $(this);
            validation_{{ $target }}.validate().then(function(status) {
                if (status === 'Valid') {
                    // Nếu form hợp lệ, chúng ta sẽ gửi form trực tiếp
                    form.off('submit').submit(); // Gửi form mà không qua AJAX

                    mess_success('Thông báo', "Tải thành công");

                    model{{ $target }}.hide();
                    Datatable.loadData();
                } else {
                    mess_error("Cảnh báo", "{{ __('Có lỗi xảy ra bạn cần kiểm tra lại thông tin.') }}");
                }
            });
        });

        function downloadPhieu() {
            data = 0;
            modalEl = document.querySelector('#kt_modal_{{ $target }}_target');
            if (!modalEl) {
                return;
            }
            model{{ $target }} = new bootstrap.Modal(modalEl);
            model{{ $target }}.show();
        }
    </script>
@endpush
