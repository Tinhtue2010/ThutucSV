@extends('layout.main_layout')

@section('content')
    @include('che_do_chinh_sach.header')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                @isset($don_parent)
                    @php
                        $noti_class_name = 'primary';
                        $noti_title = 'Thông báo';

                        if ($don_parent->status < 0) {
                            $noti_class_name = 'warning';
                            $noti_title = 'Cảnh báo';
                        }
                    @endphp
                    @isset($don->note)
                    <div class="d-flex align-items-center bg-light-{{ $noti_class_name }} rounded p-5 mb-7">
                        <i class="ki-outline ki-snapchat text-{{ $noti_class_name }} fs-1 me-5"></i>
                        <div class="flex-grow-1 me-2">
                            <a href="#" class=" fw-bold text-gray-800 text-hover-primary fs-4">{{ $noti_title }}</a>
                            <span class="fs-5 fw-semibold d-block">
                                @if ($don_parent->status == 0)
                                    {{ __('Đơn của bạn đã được gửi đi hãy chờ thông báo tiếp theo') }}
                                @else
                                    {{ $don->note ?? "" }}
                                @endif
                            </span>
                        </div>
                        @isset($don->phieu_id)
                            <a href="{{ route('phieu.index', ['id' => $don->phieu_id]) }}" target="_blank" class="btn btn-primary">Xem phiếu</a>
                        @endisset

                    </div>
                    @endisset

                @endisset

                <div class="card card-flush p-5">

                    <p class="fw-medium fs-5">Họ và tên : {{ $student->full_name }}</p>
                    <p class="fw-medium fs-5">Ngày sinh : {{ date('d/m/Y', strtotime($student->date_of_birth)) }}</p>
                    <p class="fw-medium fs-5">Số điện thoại : {{ $student->phone }}</p>
                    <p class="fw-medium fs-5">Lớp : {{ $student->lop_name }}</p>
                    <p class="fw-medium fs-5">Khoa : {{ $student->khoa_name }}</p>
                    <form action="" id="form_create">
                        @csrf
                        <div class="d-flex flex-column mb-2 fv-row">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                <span class="mt-3">Thuộc đối tượng</span>
                            </label>
                            <div class="form-check form-check-custom form-check-solid mb-2">
                                <input name="doituong" class="form-check-input" type="radio" value="1" id="doituong1" checked />
                                <label class="ms-3" for="doituong1">
                                    <b>* Đối tượng 1: Đối tượng được hưởng chế độ hỗ trợ tiền ăn, hỗ trợ học phí và hỗ trợ chỗ ở phải là sinh viên có hộ khẩu thường trú lại tỉnh Quảng Ninh thuộc diện: </b> <br>
                                    - Sinh viên có gia cảnh thuộc hộ nghèo <br>
                                    - Sinh viên có gia cảnh thuộc hộ cận nghèo <br>
                                    - Sinh viên có gia cảnh thuộc các xã khu vực I vùng đồng bào dân tộc thiểu số theo quy định cua Thủ tướng Chính phủ <br>
                                    - Sinh viên tốt nghiệp các trường phổ thông dân tộc nội trú trên địa bàn tỉnh Quảng Ninh <br>
                                    - Sinh viên đã hoàn thành nghĩa vụ quân sự, nghĩa vụ công an.
                                    <br>
                                </label>
                            </div>
                            <div class="form-check form-check-custom form-check-solid">
                                <input name="doituong" class="form-check-input" type="radio" value="2" id="doituong2" />
                                <label class="ms-3" for="doituong2">
                                    <b>* Đối tượng 2: Đối tượng được hưởng chế độ hỗ trợ chỗ ở là sinh viên một trong 7 ngành quy định trên có điểm trung bình chung học tập, điểm rèn luyện trong học kỳ xếp loại từ Khá trở lên (thang 10, điểm trung bình chung học tập lớn hơn hoặc bằng 7.0 và điểm rèn luyện từ 65 điểm trở lên) và khoảng cách từ trường đến nhà từ 15 km trở lên. </b>
                                </label>
                            </div>
                            
                        </div>
                        <div class="d-flex flex-column mb-8 fv-row">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                <span class="">Hồ sơ xin hưởng chế độ trợ cấp xã hội kèm theo giấy này gồm:</span>
                            </label>
                            <!--end::Label-->
                            <textarea @if (isset($don_parent)) @if ($don_parent->status > 0)
                                readonly @endif @endif class="form-control h-150px" name="hoso">{{ $phieu['hoso'] ?? '' }}</textarea>
                        </div>
                        <div class="d-flex flex-column mb-8 fv-row">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                <span class="">Địa chỉ:</span>
                            </label>
                            <!--end::Label-->
                            <input type="text" @if (isset($don_parent)) @if ($don_parent->status > 0)
                                readonly @endif @endif class="form-control" name="diachi" value="{{ $don_parent['diachi'] ?? '' }}"/>
                        </div>
                        <div class="d-flex flex-column mb-8 fv-row">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                <span class="">Khoảng cách (lớn hơn 15km):</span>
                            </label>
                            <!--end::Label-->
                            <input type="number" @if (isset($don_parent)) @if ($don_parent->status > 0)
                                readonly @endif @endif class="form-control" name="km" min="15" value="{{ $don_parent['km'] ?? '' }}"/>
                        </div>
                        <div class="d-flex flex-column mb-8 fv-row">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                <span class="">File</span>
                            </label>
                            <!--end::Label-->
                            <input type="file" class="form-control" name="files[]" accept="application/pdf" multiple />
                        </div>
                        <input type="hidden" id="button_clicked" name="button_clicked" value="">
                        <div class="d-flex w-100">

                            @if (isset($don_parent))
                                @if ($don_parent->status <= 0)
                                    <button type="submit" class="btn btn-success me-2">
                                        {{ __('Sửa đơn xin chế độ chính sách') }}
                                    </button>
                                @endif
                            @else
                                <button type="submit" class="btn btn-success me-2">
                                    {{ __('Gửi đơn xin chế độ chính sách') }}
                                </button>
                            @endif


                            @isset($don_parent)
                                <a href="/storage/{{ $don_parent->file_name }}" target="_blank" class="btn btn-warning me-2">Xem đơn</a>
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
    <script>
        let form_create = document.querySelector('#form_create');
        let validation_create = FormValidation.formValidation(
            form_create, {
                fields: {
                    hoso: {
                        validators: {
                            notEmpty: {
                                message: '{{ __('Vui lòng không để trống mục này') }}'
                            },
                        }
                    },
                    doituong: {
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
            const form = document.querySelector("#form_create");
            const formData = new FormData(form);
            validation_create.validate().then(async function(status) {
                if (status === 'Valid') {
                    axios({
                        method: 'POST',
                        url: "{{ route('CheDoChinhSach.KyDonPdf') }}",
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
                        checkMaXacNhan(formData,response.data,'{{route('CheDoChinhSach.CreateViewPdf')}}');

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
