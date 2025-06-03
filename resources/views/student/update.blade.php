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
                <form class="form" id="form_update" enctype="multipart/form-data">
                    <div class="card-body">
                        @csrf
                        @include('student.field', [
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
    <script>
        $(document).ready(function() {
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                language: 'vi',
                endDate: '0d'
            });
        });
    </script>
    <script type="text/javascript">
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
                    let formData = new FormData(form[0]);
                    axios.post("{{ route('student.update') }}", formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then((response) => {
                        mess_success('Thông báo',
                            "Chỉnh sửa thành công"
                        );
                        form.trigger("reset");
                        modelUpdate.hide();
                        location.reload();
                    }).catch(function(error) {
                        mess_error("Cảnh báo",
                            "{{ __('An error has occurred.') }}"
                        );
                    });
                } else {
                    mess_error("Cảnh báo",
                        "{{ __('Có lỗi xảy ra bạn cần kiểm tra lại thông tin.') }}"
                    )
                }
            });
        });


        function btnEdit() {
            modalEl = document.querySelector('#kt_modal_update_target');
            if (!modalEl) {
                return;
            }
            modelUpdate = new bootstrap.Modal(modalEl);
            form = document.querySelector('#form_update');
            axios.get("{{ route('student.getDataInfo') }}").then(response => {
                modelUpdate.show();

                const inputElements = form.querySelectorAll('[name]');
                inputElements.forEach(e => {
                    if (e.name !== '_token' && e.type !== 'file') {
                        let value = response.data[e.name];

                        // Check if it's a valid date string
                        const dateObj = new Date(value);
                        if (!isNaN(dateObj.getTime()) && e.classList.contains('datepicker')) {
                            // Format to dd/mm/yyyy
                            const day = String(dateObj.getDate()).padStart(2, '0');
                            const month = String(dateObj.getMonth() + 1).padStart(2, '0');
                            const year = dateObj.getFullYear();
                            value = `${day}/${month}/${year}`;

                            // Set via datepicker API
                            $(e).datepicker('update', value);
                        } else {
                            // Just assign value to other inputs
                            e.value = value == null ? '' : value;
                        }

                        // Trigger change event
                        const event = new Event('change');
                        e.dispatchEvent(event);
                    }
                });
            });



        }
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/locales/bootstrap-datepicker.vi.min.js">
    </script>

    <script>
        $(document).ready(function() {
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                language: 'vi',
                endDate: '0d'
            });
        });
    </script>
@endpush
