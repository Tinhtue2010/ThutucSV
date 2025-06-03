<div class="modal fade" id="kt_modal_update_target" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Chỉnh sửa') }}</h5>
                <div class="btn btn-sm btn-icon btn-active-color-primary close" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <div class="modal-body">
                <form class="form" id="form_update">
                    <div class="card-body">
                        @csrf
                        @include('class_manager.field', [
                            'type' => '_update',
                        ])
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success mr-2">{{ __('Cập nhật') }}</button>
                        <button type="reset" class="btn btn-secondary">{{ __('Nhập lại') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script type="text/javascript">
        let id = 0;
        let modelUpdate;

        let form_update = document.querySelector('#form_update');
        let validation_update = FormValidation.formValidation(
            form_update, {
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

        $('#form_update').submit(function(e) {
            e.preventDefault();
            let form = $(this);
            validation_update.validate().then(function(status) {
                if (status === 'Valid') {
                    axios({
                        method: 'POST',
                        url: "{{ route('classManager.update') }}/" +
                            id,
                        data: form.serialize(),
                    }).then((response) => {
                        mess_success('Thông báo',
                            "Chỉnh sửa thành công")
                        $(this).trigger("reset");
                        modelUpdate.hide();
                        Datatable.loadData();
                    }).catch(function(error) {
                        mess_error("Cảnh báo",
                            "{{ __('Cần kiểm tra lại thông tin') }}"
                        )
                    });
                } else {
                    mess_error("Cảnh báo",
                        "{{ __('Có lỗi xảy ra bạn cần kiểm tra lại thông tin.') }}"
                    )
                }
            });
        });


        function btnEdit(data) {
            modalEl = document.querySelector('#kt_modal_update_target');
            if (!modalEl) {
                return;
            }
            modelUpdate = new bootstrap.Modal(modalEl);
            form = document.querySelector('#form_update');
            id = data;

            axios.get("{{ route('classManager.getDataChild') }}/" + data).then(
                response => {
                    modelUpdate.show();

                    // Store the response data for later use
                    const responseData = response.data;

                    // First, populate basic fields (non-select fields)
                    const inputElements = form.querySelectorAll('[name]');
                    inputElements.forEach(e => {
                        if (e.name != '_token' && e.name != 'teacher_id' && e.name != 'nganh_id' && e.name !=
                            'ma_khoa') {
                            e.value = responseData[e.name] == null ? '' : responseData[e.name];
                            var event = new Event('change');
                            e.dispatchEvent(event);
                        }
                    });

                    // Handle ma_khoa first
                    if (responseData['ma_khoa'] != null) {
                        $('[name="ma_khoa"]').val(responseData['ma_khoa']);

                        // Wait for khoa change to complete, then set dependent fields
                        Promise.all([
                            axios.get("{{ route('khoaManager.nganh') }}/" + responseData['ma_khoa']),
                            axios.get("{{ route('khoaManager.teacher') }}/" + responseData['ma_khoa'])
                        ]).then(([nganhResponse, teacherResponse]) => {

                            // Populate nganh_id dropdown
                            $('select[name="nganh_id"]').prop('disabled', false);
                            $('select[name="nganh_id"]').empty();
                            nganhResponse.data.forEach(e => {
                                var newOption = new Option(
                                    `${e.tennganh} - ${e.hedaotao == 0 ? "ĐH" : e.hedaotao == 1 ? "THS" : e.hedaotao == 2 ? "CĐ" : "TC"} (${e.manganh})`,
                                    e.manganh, false, false);
                                $('select[name="nganh_id"]').append(newOption);
                            });

                            // Populate teacher_id dropdown
                            $('select[name="teacher_id"]').prop('disabled', false);
                            $('select[name="teacher_id"]').empty();
                            teacherResponse.data.forEach(e => {
                                var newOption = new Option(`${e.full_name}`, e.id, false, false);
                                $('select[name="teacher_id"]').append(newOption);
                            });

                            // Now set the selected values after dropdowns are populated
                            if (responseData['nganh_id'] != null) {
                                $('[name="nganh_id"]').val(responseData['nganh_id']).trigger('change');
                            }
                            if (responseData['teacher_id'] != null) {
                                $('[name="teacher_id"]').val(responseData['teacher_id']).trigger('change');
                            }

                            // Finally trigger change on ma_khoa
                            $('[name="ma_khoa"]').trigger('change');
                        });
                    }
                }
            );
        }
    </script>
@endpush
