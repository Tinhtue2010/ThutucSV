@extends('layout.main_layout')

@section('content')
    @include('stop_study.header')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">


                @if ($check)
                    <div class="card card-xl-stretch mb-5 mb-xl-8 pt-5">
                        <!--begin::Body-->
                        <div class="card-body pt-0">
                            @foreach ($notification as $item)
                                <!--begin::Item-->
                                <div class="d-flex align-items-center bg-light-warning rounded p-5 mb-7">
                                    <i class="ki-outline ki-snapchat text-warning fs-1 me-5"></i>
                                    <!--begin::Title-->
                                    <div class="flex-grow-1 me-2">
                                        <a href="#" class=" fw-bold text-gray-800 text-hover-primary fs-4">Thông báo</a>
                                        <span class="fs-5 fw-semibold d-block">{{ $item->notification }}</span>
                                    </div>
                                    <!--end::Title-->
                                    @if ($item->phieu_id != null)
                                        <!--begin::Lable-->
                                        <a href="{{ route('StopStudy.viewPdf', ['id' => $item->id]) }}" target="_blank" class="btn btn-primary">Xem đơn</a>
                                        <!--end::Lable-->
                                    @endif

                                </div>
                                <!--end::Item-->
                            @endforeach

                        </div>
                        <!--end::Body-->
                    </div>
                @endif
                @isset($check->status)
                    @if ($check->status == -1)
                        <!--begin::Products-->
                        <div class="card card-flush p-5">

                            <form action="" id="form_create">
                                @csrf
                                <div class="d-flex flex-column mb-8 fv-row">
                                    <!--begin::Label-->
                                    <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                        <span class="required">Lý do rút hồ sơ</span>
                                    </label>
                                    <!--end::Label-->
                                    <textarea class="form-control form-control-solid h-150px" name="data"></textarea>
                                </div>
                                <input type="hidden" id="button_clicked" name="button_clicked" value="">
                                <div class="d-flex w-100">
                                    <button type="submit" class="btn btn-success me-2">{{ __('Gửi lại đơn') }}</button>
                                    <button type="submit" class="btn btn-warning me-2">{{ __('Xem trước') }}</button>
                                    <button type="reset" class="btn btn-secondary">{{ __('Nhập lại') }}</button>
                                </div>
                            </form>
                        </div>
                        <!--end::Products-->
                    @endif
                @endisset

                @if (!isset($check))
                    <!--begin::Products-->
                    <div class="card card-flush p-5">

                        <form action="" id="form_create">
                            @csrf
                            <div class="d-flex flex-column mb-8 fv-row">
                                <!--begin::Label-->
                                <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                    <span class="required">Lý do rút hồ sơ</span>
                                </label>
                                <!--end::Label-->
                                <textarea class="form-control form-control-solid h-150px" name="data"></textarea>
                            </div>
                            <input type="hidden" id="button_clicked" name="button_clicked" value="">
                            <div class="d-flex w-100">
                                <button type="submit" class="btn btn-success me-2">{{ __('Gửi đơn') }}</button>
                                <button type="submit" class="btn btn-warning me-2">{{ __('Xem trước') }}</button>
                                <button type="reset" class="btn btn-secondary">{{ __('Nhập lại') }}</button>
                            </div>
                        </form>
                    </div>
                    <!--end::Products-->
                @endif

            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
@endsection

@push('js')
    <script>
        let form_create = document.querySelector('#form_create');
        let validation_create = FormValidation.formValidation(
            form_create, {
                fields: {
                    data: {
                        validators: {
                            notEmpty: {
                                message: '{{ __('Vui lòng không để trống mục này') }}'
                            },
                        }
                    },
                },
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
        $('button.btn-success').click(function() {
            $('#button_clicked').val('gui_don');
        });
        $('button.btn-warning').click(function() {
            $('#button_clicked').val('xem_truoc');
        });
        $('#form_create').submit(function(e) {
            e.preventDefault();

            let form = $(this);
            validation_create.validate().then(function(status) {
                if (status === 'Valid') {
                    axios({
                        method: 'POST',
                        url: "{{ route('StopStudy.CreateViewPdf') }}",
                        data: form.serialize(),
                    }).then((response) => {
                        if ($('#button_clicked').val() == 'xem_truoc') {
                            window.open("{{ route('StopStudy.viewDemoPdf') }}", "_blank");
                        } else {
                            mess_success('Thông báo',
                                "Đơn của bạn đã được gửi")
                            location.reload();
                        }

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
