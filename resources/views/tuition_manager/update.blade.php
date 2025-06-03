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
                        @include('student_manager.field', [
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
                        url: "{{ route('studentManager.update') }}/" +
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
                            "{{ __('An error has occurred.') }}"
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

            var lop_id = 0;
            var ma_khoa = '';
            axios.get("{{ route('studentManager.getDataChild') }}/" + data).then(
                response => {
                    modelUpdate.show();
                    const inputElements = form.querySelectorAll('[name]');
                    inputElements.forEach(e => {
                        if (e.name != '_token' && e.name != 'status') {
                            e.value = response.data[e.name] == null ? '' : response.data[e.name];
                            var event = new Event('change');
                            e.dispatchEvent(event);
                        }
                        if (e.name == 'date_of_birth') {
                            let dateStr = response.data['date_of_birth'];
                            let formattedDate = dateStr.slice(0, 10);
                            e.value = formattedDate;
                        }
                    });
                    lop_id = response.data['lop_id'];
                    return response.data['ma_khoa'];
                }
            ).then((res) => {
                $('select[name="lop_id"]').prop('disabled', false);
                $('select[name="lop_id"]').empty();

                axios.get("{{ route('khoaManager.lop') }}/" + res).then(
                    response => {
                        $('select[name="lop_id"]').prop('disabled', false);
                        $('select[name="lop_id"]').empty();
                        response.data.forEach(e => {
                            var newOption = new Option(
                                `${e.name} - ${e.hedaotao == 0 ? "ĐH" : e.hedaotao == 1 ? "THS" : e.hedaotao == 2 ? "CĐ" : "TC"}`,
                                e.id, false, false);
                            $('select[name="lop_id"]').append(newOption).trigger('change');
                        });
                    }).then()
                $('select[name="lop_id"]').val(lop_id).trigger('change');
            });

            $('#_update_field_khoa').val('DL').trigger('change');

        }
    </script>
@endpush
