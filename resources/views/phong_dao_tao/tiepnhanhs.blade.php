<div class="modal fade" id="kt_modal_{{ $target }}_target" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Tiếp nhận hồ sơ') }}</h5>
                <div class="btn btn-sm btn-icon btn-active-color-primary close" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <div class="modal-body">
                <form class="form" id="form_{{ $target }}">
                    <div class="card-body">
                        @csrf
                        <input type="text" name="id" class="d-none">
                        <div class="row mb-5">
                            <div class="col-sm-6">
                                <label for="kt_td_picker_linked_1_input" class="form-label">Tiếp nhận</label>
                                <div class="input-group log-event" id="kt_td_picker_linked_1" data-td-target-input="nearest" data-td-target-toggle="nearest">
                                    <input readonly name="thoigiantiepnhan" id="kt_td_picker_linked_1_input" type="text" class="form-control" data-td-target="#kt_td_picker_linked_1" />
                                    <span class="input-group-text" data-td-target="#kt_td_picker_linked_1" data-td-toggle="datetimepicker">
                                        <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span class="path2"></span></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="kt_td_picker_linked_2_input" class="form-label">Trả kết quả</label>
                                <div class="input-group log-event" id="kt_td_picker_linked_2" data-td-target-input="nearest" data-td-target-toggle="nearest">
                                    <input readonly name="thoigiantraketqua" id="kt_td_picker_linked_2_input" type="text" class="form-control" data-td-target="#kt_td_picker_linked_2" />
                                    <span class="input-group-text" data-td-target="#kt_td_picker_linked_2" data-td-toggle="datetimepicker">
                                        <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span class="path2"></span></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="kt_td_picker_linked_2_input" class="form-label">Ý kiến giải quyết công việc</label><br>
                            <label for="kt_td_picker_linked_2_input" class="form-label text-warning">Lưu ý: Nội dung sẽ được lưu vào phiếu theo dõi giải quyết công việc</label>
                            <textarea type="text" class="form-control form-control-solid" cols="5" rows="3" name="ykientiepnhan"></textarea>
                        </div>
                        <br>
                        <div class="d-flex flex-column mb-8 fv-row">
                            <!--begin::Label-->
                            <label class="d-flex align-items-start flex-column fs-6 fw-semibold mb-2">
                                <span>Thành phần hồ sơ gồm</span>
                            </label>
                            <!--end::Label-->
                            <table class="table table-row-dashed">
                                <thead>
                                    <tr>
                                        <th class="fw-bold text-center" style="width: 7%">TT</th>
                                        <th class="fw-bold text-center" style="width: 40%">Tên giấy tờ</th>
                                        <th class="fw-bold text-center" style="width: 30%">Hình thức (bản chính, bản sao hoặc bản chụp)</th>
                                        <th class="fw-bold text-center" style="width: 20%">Ghi chú</th>
                                        <th class="fw-bold text-center" style="width: 3%"></th>
                                    </tr>
                                </thead>
                                <tbody id="table_tiepnhan">
                                    <tr class="text-center">
                                        <td scope="row">1</td>
                                        <td class="p-0">
                                            <input name="tengiayto[]" class="w-100 p-3 border-0" type="text">
                                        </td>
                                        <td class="p-0">
                                            <input name="hinhthuc[]" class="w-100 p-3 border-0" type="text">
                                        </td>
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
                            <div class="d-flex w-100 justify-content-end">
                                <i onclick="addCloumn()" class="ki-duotone ki-plus-circle fs-2x cursor-pointer text-primary">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>

                        </div>


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
        function setSTT() {
            $('#table_tiepnhan tr').each(function(index) {
                $(this).find('td:first').text(index + 1);
            });
            return false;
        }

        function addCloumn() {
            var lastRow = $('tr.text-center').last();
            lastvalue = lastRow.find('[name="tengiayto[]"]').val();
            if (lastvalue == '') {
                return;
            }
            $('#table_tiepnhan').append(`
                <tr class="text-center">
                    <td scope="row">k</td>
                    <td class="p-0">
                        <input name="tengiayto[]" class="w-100 p-3 border-0" type="text">
                    </td>
                    <td class="p-0">
                        <input name="hinhthuc[]" class="w-100 p-3 border-0" type="text">
                    </td>
                    <td class="p-0">
                        <input name="ghichu[]" class="w-100 p-3 border-0" type="text">
                    </td>
                    <th>
                        <i onclick="deleteCloumn(this)"  class="ki-duotone ki-minus-circle fs-2x cursor-pointer text-danger">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </th>
                </tr>
            `);
            setSTT();
        }

        function deleteCloumn(element) {
            $(element).closest('tr').remove();
            setSTT();
        }
        $('#form_{{ $target }}').submit(function(e) {
            e.preventDefault();
            let form = $(this);
            validation_{{ $target }}.validate().then(async function(status) {
                if (status === 'Valid') {
                    await checkMaXacNhan().then(function(result) {
                        if (false) {
                            return;
                        } else {
                            form.append('otp', result);
                        }
                    });
                    axios({
                        method: 'POST',
                        url: "{{ route('PhongDaoTao.tiepnhanhs') }}",
                        data: form.serialize(),
                    }).then((response) => {
                        if ($('#kt_modal_{{ $target }}_target .button_clicked').val() == 'xem_truoc') {
                            window.open("{{ route('phieu.index') }}/" + response.data, '_blank');
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

        function tiepnhanhs(data) {
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
                    e.value = '';

                }
            });
            axios.get("{{ route('PhongDaoTao.gettiepnhanhs') }}/" + data).then(
                response => {
                    inputElements.forEach(e => {
                        if (e.name != '_token' && e.name != 'id') {
                            e.value = response.data[e.name] == null ? '' : response.data[e.name];
                            var event = new Event('change');
                            e.dispatchEvent(event);
                        }
                    });
                })
            model{{ $target }}.show();
        }
        const linkedPicker1Element = document.getElementById("kt_td_picker_linked_1");
        const linked1 = new tempusDominus.TempusDominus(linkedPicker1Element, {
            localization: {
                locale: "en-GB",
                startOfTheWeek: 1,
                format: "dd/MM/yyyy"
            }
        });
        const linked2 = new tempusDominus.TempusDominus(document.getElementById("kt_td_picker_linked_2"), {
            localization: {
                locale: "en-GB",
                startOfTheWeek: 1,
                format: "dd/MM/yyyy"
            },
            useCurrent: false
        });

// Sử dụng event listeners
linkedPicker1Element.addEventListener(tempusDominus.Namespace.events.change, (e) => {
    const selectedDate = e.detail.date;
    const maxDate = new Date(selectedDate);
    maxDate.setDate(maxDate.getDate() + 7); // Thêm 7 ngày
    linked2.updateOptions({
        restrictions: {
            minDate: selectedDate,
            maxDate: maxDate
        }
    });
});

// Sử dụng phương thức subscribe
const subscription = linked2.subscribe(tempusDominus.Namespace.events.change, (e) => {
    const selectedDate = e.date;
    const minDate = new Date(selectedDate);
    minDate.setDate(minDate.getDate() - 7); // Trừ 7 ngày
    linked1.updateOptions({
        restrictions: {
            minDate: minDate,
            maxDate: selectedDate
        }
    });
});
    </script>
@endpush
