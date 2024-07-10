<div class="modal fade" id="kt_modal_{{ $target }}_target" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm tự động danh sách trợ cấp xã hội</h5>
                <div class="btn btn-sm btn-icon btn-active-color-primary close" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <div class="modal-body">
                <form class="form" id="form_{{ $target }}">
                    <div class="card-body">
                        @csrf
                        <!--begin::Row-->
                        <div class="d-flex flex-column mb-8 fv-row col-12 pe-4" data-kt-buttons="true" data-kt-buttons-target=".form-check-image, .form-check-input">
                            <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                <span class="required">Các danh sách sinh viên được hưởng trợ cấp theo nghị định 81/2021/NĐ-CP</span>
                            </label>
                            @foreach ($phieu_2 as $index=>$item)
                                <div class="col-12 ms-5">
                                    <label class="form-check-image active">
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio"  value="{{$item->id}}" name="phieu_id" {{ $index == 0 ? 'checked' : '' }}/>
                                            <a href="{{ route('phieu.index', ['id' => $item->id]) }}" target="_bank" class="ms-5 form-check-label d-flex flex-row">
                                                @php
                                                    $data = json_decode($item->content,true);
                                                @endphp
                                                Kỳ {{ $data[0]['ky'] == '1' ? 'I' : 'II' }} NĂM HỌC {{ $data[0]['nam'] }}
                                            </a>
                                        </div>
                                    </label>
                                </div>
                            @endforeach

                        </div>
                        <!--end::Row-->
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success mr-2">{{ __('Thêm') }}</button>
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
                        url: "{{ route('PhongDaoTao.CheDoChinhSach.ImportQTMGHP') }}",
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
        $(document).ready(function() {

        })

        function import_qt_3() {
            modalEl = document.querySelector('#kt_modal_{{ $target }}_target');
            if (!modalEl) {
                return;
            }
            model{{ $target }} = new bootstrap.Modal(modalEl);
            model{{ $target }}.show();
        }
    </script>
@endpush
