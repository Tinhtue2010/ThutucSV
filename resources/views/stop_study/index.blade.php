@extends('layout.main_layout')

@section('content')
    @include('stop_study.header')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                {{-- @isset($don_parent)
                    @php
                        $noti_class_name = 'primary';
                        $noti_title = 'Thông báo';

                        if ($don_parent->status < 0) {
                            $noti_class_name = 'warning';
                            $noti_title = 'Cảnh báo';
                        }
                    @endphp
                    @if ($don_parent->status == -1)
                        <div class="d-flex align-items-center bg-light-warning rounded p-5 mb-7">
                            <i class="ki-outline ki-snapchat text-primary fs-1 me-5"></i>
                            <div class="flex-grow-1 me-2">
                                <a href="#" class=" fw-bold text-gray-800 text-hover-primary fs-4">Cảnh báo</a>
                                <span class="fs-5 fw-semibold d-block">
                                    Đơn của bạn đã bị từ chối bởi giáo viên chủ nhiệm hãy kiểm tra lại thông tin
                                </span>
                            </div>
                        </div>
                    @endif
                    @if ($don_parent->is_update == 1)
                        <div class="d-flex align-items-center bg-light-primary rounded p-5 mb-7">
                            <i class="ki-outline ki-snapchat text-primary fs-1 me-5"></i>
                            <div class="flex-grow-1 me-2">
                                <a href="#" class=" fw-bold text-gray-800 text-hover-primary fs-4">Thông báo</a>
                                <span class="fs-5 fw-semibold d-block">
                                    Bạn đã bổ sung hồ sơ thành công vui lòng chờ thông báo tiếp theo
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="d-flex align-items-center bg-light-{{ $noti_class_name }} rounded p-5 mb-7">
                            <i class="ki-outline ki-snapchat text-{{ $noti_class_name }} fs-1 me-5"></i>
                            <div class="flex-grow-1 me-2">
                                <a href="#" class=" fw-bold text-gray-800 text-hover-primary fs-4">{{ $noti_title }}</a>
                                <span class="fs-5 fw-semibold d-block">
                                    @if ($don_parent->status == 0)
                                        {{ __('Đơn của bạn đã được gửi đi hãy chờ thông báo tiếp theo') }}
                                    @else
                                    
                                        {{ $don->note }}
                                    @endif

                                </span>
                            </div>
                            @isset($don->file_name)
                                <a href="/storage/{{$don->file_name}}" target="_blank"
                                    class="btn btn-primary">Xem phiếu</a>
                            @endisset
                            @if ($don_parent->status == 6)
                            <a href="/storage/{{$don->file_name}}" target="_blank"
                                class="btn btn-primary">Xem đơn</a>
                            @endif

                        </div>
                    @endif

                @endisset --}}

                <div class="card card-flush p-5">

                    <p class="fw-medium fs-5">Họ và tên : {{ $student->full_name }}</p>
                    <p class="fw-medium fs-5">Ngày sinh : {{ date('d/m/Y', strtotime($student->date_of_birth)) }}</p>
                    <p class="fw-medium fs-5">Lớp : {{ $student->lop_name }}</p>
                    <p class="fw-medium fs-5">Khoa : {{ $student->khoa_name }}</p>
                    <form action="" id="form_create">
                        @csrf
                        <div class="d-flex flex-column mb-8 fv-row">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                <span class="">Lý do rút hồ sơ</span>
                            </label>
                            <!--end::Label-->
                            <textarea
                                @if (isset($don_parent)) @if ($don_parent->status >= 0)
                                readonly @endif
                                @endif class="form-control h-150px" name="data">{{ $don_parent->note ?? '' }}</textarea>
                        </div>
                        <div class="d-flex flex-column mb-8 fv-row">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                <span class="">Hồ sơ minh chứng (chỉ nhận file pdf)</span>
                            </label>
                            <!--end::Label-->
                            <input type="file" class="form-control" name="files[]" accept="application/pdf" />
                        </div>
                        <input type="hidden" id="button_clicked" name="button_clicked" value="">
                        <div class="d-flex w-100">

                            @if (isset($don_parent))
                                @if ($don_parent->status <= 0)
                                    @if ($don_parent->is_update == 1)
                                        <button type="submit" class="btn btn-success me-2">
                                            {{ __('Sửa hồ sơ bổ sung') }}
                                        </button>
                                    @else
                                        <button type="submit" class="btn btn-success me-2">
                                            {{ __('Bổ sung hồ sơ') }}
                                        </button>
                                    @endif

                                @endif
                            @else
                                <button type="submit" class="btn btn-success me-2">
                                    {{ __('Gửi đơn xin rút hồ sơ') }}
                                </button>
                            @endif

                            @isset($don_parent)
                                <a href="{{ asset('storage/' . $don_parent->file_name) }}" target="_blank"
                                    class="btn btn-warning me-2">Xem đơn</a>
                                {{-- <a href="{{ asset('storage/'.$don_parent->file_name) }}"
                                        class="btn btn-danger me-2">Xoá đơn</a> --}}
                            @endisset
                            @if (isset($don_parent))
                                @if ($don_parent->status <= 0)
                                    <button type="reset" class="btn btn-secondary">{{ __('Nhập lại') }}</button>
                                @endif
                            @else
                                <button type="reset" class="btn btn-secondary">{{ __('Nhập lại') }}</button>
                            @endif

                        </div>
                    </form>
                </div>
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
@endsection


@push('js')
    scr
@endpush
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
        $('#form_create').submit(async function(e) {
            e.preventDefault();

            const form = document.querySelector("#form_create");
            const formData = new FormData(form);
            validation_create.validate().then(async function(status) {
                if (status === 'Valid') {
                    checkChuKy();
                    axios({
                        method: 'POST',
                        url: "{{ route('StopStudy.KyDonPdf') }}",
                        data: formData,
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    }).then((response) => {
                        if (response.data == 0) {
                            mess_error("Cảnh báo",
                                "{{ __('Bạn chưa đăng ký chữ ký số cần đăng ký chữ ký số SmartCA') }}"
                            )
                        }
                        checkMaXacNhan(formData,response.data);
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
