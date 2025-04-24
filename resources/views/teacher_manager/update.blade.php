<div class="modal fade" id="kt_modal_update_target" tabindex="-1" role="dialog"
    aria-hidden="true">
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
                        @include('teacher_manager.field',[
                            'type' => '_update',
                        ])
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
                        url: "{{ route('teacherManager.update') }}/" +
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
            console.log("test");
            modalEl = document.querySelector('#kt_modal_update_target');
            if (!modalEl) {
                return;
            }
            modelUpdate = new bootstrap.Modal(modalEl);
            form = document.querySelector('#form_update');
            id = data;
            axios.get("{{ route('teacherManager.getDataChild') }}/" + data).then(
                response => {
                    modelUpdate.show();
                    const inputElements = form.querySelectorAll('[name]');
                    inputElements.forEach(e => {
                        if (e.name != '_token') {
                            e.value = response.data[e.name] == null ? '' : response.data[e.name] ;
                            var event = new Event('change');
                            e.dispatchEvent(event);
                        }
                    });
                })
        }
    </script>
@endpush
