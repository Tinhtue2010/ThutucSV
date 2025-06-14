<div class="modal fade" id="kt_modal_new_target" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Thêm mới') }}</h5>
                <div class="btn btn-sm btn-icon btn-active-color-primary close" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <div class="modal-body">
                <form class="form " id="form_create">
                    <div class="card-body">
                        @csrf
                        @include('khoa_manager.field', [
                            'type' => '_create',
                        ])
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success mr-2">{{ __('Thêm mới') }}</button>
                        <button type="reset" class="btn btn-secondary">{{ __('Nhập lại') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script type="text/javascript">
        let form_create = document.querySelector('#form_create');
        let validation_create = FormValidation.formValidation(
            form_create, {
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


        function btnCreate() {
            modalEl = document.querySelector('#kt_modal_new_target');
            if (!modalEl) {
                return;
            }
            modelUpdate = new bootstrap.Modal(modalEl);
            form = document.querySelector('#form_create');
            axios.get("{{ route('khoaManager.nganh') }}").then(
                response => {
                    modelUpdate.show();
                    $('select[name="nganh[]"]').empty();
                    response.data.forEach(e => {
                        var newOption = new Option(`${e.tennganh} - ${e.hedaotao == 0 ? "ĐH" : e.hedaotao == 1 ? "THS" : e.hedaotao == 2 ? "CĐ" : "TC"} (${e.manganh})    `, e.manganh, false, false);
                        $('select[name="nganh[]"]').append(newOption).trigger('change');
                    });
                })
        }

        $('#form_create').submit(function(e) {
            e.preventDefault();
            let form = $(this);
            validation_create.validate().then(function(status) {
                if (status === 'Valid') {
                    axios({
                        method: 'POST',
                        url: "{{ route('khoaManager.create') }}",
                        data: form.serialize(),
                    }).then((response) => {
                        mess_success('Thông báo',
                            "Thêm mới thành công")
                        $('#form_create').trigger("reset");

                        $('#kt_modal_new_target').modal(
                            'hide');
                        Datatable.loadData();
                    }).catch(function(error) {
                        mess_error("Cảnh báo",
                            "{{ __('Có lỗi xảy ra bạn cần kiểm tra lại thông tin.') }}"
                        )
                    });
                } else {

                }
            });
        });
    </script>
@endpush
