<div class="modal fade" id="kt_modal_{{ $target }}_target" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Danh sách miễn giảm học phí') }}</h5>
                <div class="btn btn-sm btn-icon btn-active-color-primary close" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <div class="modal-body">
                <div class="w-100 d-flex justify-content-end">
                    <button onclick="cauhinhmiengiamhp()" class="btn btn-info">Cấu hình chung</button>
                </div>
                <form class="form" id="form_{{ $target }}">
                    <div class="card-body">
                        @csrf
                        <input type="text" name="id" class="d-none">
                        <table class="table table-row-dashed">
                            <thead>
                                <tr>
                                    <th class="fw-bold text-center" style="width: 7%">STT</th>
                                    <th class="fw-bold text-center" style="width: 10%">Họ và tên</th>
                                    <th class="fw-bold text-center" style="width: 10%">Ngày sinh</th>
                                    <th class="fw-bold text-center" style="width: 10%">Lớp</th>
                                    <th class="fw-bold text-center" style="width: 10%">Đối tượng</th>
                                    <th class="fw-bold text-center" style="width: 10%">Mức học phí/tháng</th>
                                    <th class="fw-bold text-center" style="width: 10%">Tỉ lệ miễn giảm</th>
                                    <th class="fw-bold text-center" style="width: 10%">Số tiền miễn giảm/tháng</th>
                                    <th class="fw-bold text-center" style="width: 10%">Số tháng miễn giảm</th>
                                    <th class="fw-bold text-center" style="width: 10%">Số tiền được miễn giảm/kỳ</th>
                                    <th class="fw-bold text-center" style="width: 3%"></th>
                                </tr>
                            </thead>
                            <tbody id="table_tiepnhan">
                                <tr class="text-center">
                                    <td scope="row">1</td>
                                    <td class="p-0">
                                        Phạm nguyên hồng
                                    </td>
                                    <td class="p-0">
                                        18/08/2001
                                    </td>
                                    <th>Lớp</th>
                                    <th>Đối tượng</th>
                                    <td class="p-0">
                                        <input name="ghichu[]" class="w-100 p-3 border-0" type="text">
                                    </td>
                                    <th>
                                        <i onclick="deleteCloumn(this)" class="ki-duotone ki-minus-circle fs-2x cursor-pointer text-danger">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </th>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                    <input type="hidden" class="button_clicked" name="button_clicked" value="">
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success mr-2">{{ __('Tạo') }}</button>
                        <button type="submit" class="btn btn-warning mr-2">{{ __('Tạo và xem phiếu') }}</button>
                        <button type="submit" class="btn btn-danger mr-2">{{ __('Hủy phiếu') }}</button>
                        <button type="reset" class="btn btn-secondary">{{ __('Nhập lại') }}</button>
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
        $('#kt_modal_{{ $target }}_target button.btn-success').click(function() {
            $('#kt_modal_{{ $target }}_target .button_clicked').val('gui_don');
        });
        $('#kt_modal_{{ $target }}_target button.btn-warning').click(function() {
            $('#kt_modal_{{ $target }}_target .button_clicked').val('xem_truoc');
        });
        $('#kt_modal_{{ $target }}_target button.btn-danger').click(function() {
            $('#kt_modal_{{ $target }}_target .button_clicked').val('huy_phieu');
        });
        $('#form_{{ $target }}').submit(function(e) {
            e.preventDefault();
            let form = $(this);
            validation_{{ $target }}.validate().then(function(status) {
                if (status === 'Valid') {
                    axios({
                        method: 'POST',
                        url: "{{ route('PhongDaoTao.tuchoihs') }}",
                        data: form.serialize(),
                    }).then((response) => {
                        if($('#kt_modal_{{ $target }}_target .button_clicked').val() == 'xem_truoc')
                        {
                            window.open("{{route('phieu.index')}}/"+response.data, '_blank');
                        }
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


        function dsmiengiamhp(data) {
            modalEl = document.querySelector('#kt_modal_{{ $target }}_target');
            if (!modalEl) {
                return;
            }
            model{{ $target }} = new bootstrap.Modal(modalEl);

            form = document.querySelector('#form_{{ $target }}');
            form.querySelectorAll('[name]');
            const inputElements = form.querySelectorAll('[name]');
            $('[name="id"]').val(data);
            inputElements.forEach(e => {
                if (e.name != '_token' && e.name != 'id') {
                    e.value ='';

                }
            });
            // axios.get("{{ route('PhongDaoTao.getbosunghs') }}/" + data).then(
            //     response => {
            //         inputElements.forEach(e => {
            //             if (e.name != '_token' && e.name != 'id') {
            //                 e.value = response.data[e.name] == null ? '' : response.data[e.name];
            //                 var event = new Event('change');
            //                 e.dispatchEvent(event);
            //             }
            //         });
            //     })
            model{{ $target }}.show();
        }
    </script>
@endpush
