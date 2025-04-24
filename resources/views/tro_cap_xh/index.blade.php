@extends('layout.main_layout')

@section('content')
    @include('tro_cap_xh.header')
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
                        @isset($don->phieu_id)
                            <a href="{{ route('phieu.index', ['id' => $don->phieu_id]) }}" target="_blank" class="btn btn-primary">Xem phiếu</a>
                        @endisset

                    </div>
                @endisset

                <div class="card card-flush p-5">

                    <p class="fw-medium fs-5">Họ và tên : {{ $student->full_name }}</p>
                    <p class="fw-medium fs-5">Ngày sinh : {{ date('d/m/Y', strtotime($student->date_of_birth)) }}</p>
                    <p class="fw-medium fs-5">Số điện thoại : {{ $student->phone }}</p>
                    <p class="fw-medium fs-5">Lớp : {{ $student->lop_name }}</p>
                    <p class="fw-medium fs-5">Khoa : {{ $student->khoa_name }}</p>
                    <form action="" id="form_create">
                        @csrf
                        <div class="d-flex flex-column mb-8 fv-row">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                <span class="">Thuộc đối tượng</span>
                            </label>
                            <!--end::Label-->
                            <select @if (isset($don_parent)) @if ($don_parent->status != 0)
                                readonly @endif @endif name="doituong" class="form-select form-select-solid filter-select" data-name="year" data-control="select2" data-placeholder="Năm">
                                <option value="0">Học sinh, sinh viên là người dân tộc thiểu số ở vùng cao từ 03 năm trở lên.</option>
                                <option value="1">Học sinh, sinh viên mồ côi cả cha lẫn mẹ không nơi nương tựa.</option>
                                <option value="2">Học sinh, sinh viên là người tàn tật gặp khó khăn về kinh tế.</option>
                                <option value="3">Học sinh, sinh viên có hoàn cảnh đặc biệt khó khăn về kinh tế, vượt khó học tập, gia đình thuộc diện xóa đói giảm nghèo.</option>
                            </select>
                        </div>
                        <div class="d-flex flex-column mb-2 fv-row">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                <span class="">Chế độ hỗ trợ</span>
                            </label>
                            <div class="form-check form-check-custom form-check-solid mb-2">
                                <input name="trocapxh" class="form-check-input" type="checkbox" value="1"checked />
                                <label class="ms-4">
                                    <b>Trợ cấp xã hội</b> <br>
                                    Đối tượng được hưởng chế độ Trợ cấp xã hội là một trong những trường hợp sau: <br>
                                    - Học sinh, sinh viên là người dân tộc thiểu số ở vùng cao từ 03 năm trở lên. <br>
                                    - Học sinh, sinh viên mồ côi cả cha lẫn mẹ không nơi nương tựa. <br>
                                    - Học sinh, sinh viên là người tàn tật gặp khó khăn về kinh tế. <br>
                                    - Học sinh, sinh viên có hoàn cảnh đặc biệt khó khăn về kinh tế, vượt khó học tập, gia đình thuộc diện xóa đói giảm nghèo.
                                </label>
                            </div>
                            <div class="form-check form-check-custom form-check-solid">
                                <input name="hocphi" class="form-check-input" type="checkbox" value="1" />
                                <label class="ms-4">
                                    <b> Trợ cấp học phí </b> <br>
                                    Đối tượng được hưởng chế độ Hỗ trợ chi phí học tập thuộc trường hợp: <br>
                                    Học sinh, sinh viên là người dân tộc thiểu số thuộc hộ nghèo,
                                    cận nghèo theo quy định của Thủ tướng Chính phủ phê duyệt từng thời kỳ.
                                </label>
                            </div>
                        </div>
                        <p class="text-warning fs-5">Lưu ý: Trợ cấp học phí chỉ áp dụng cho học sinh, sinh viên là người dân tộc thiểu số thuộc hộ nghèo,
                            cận nghèo theo quy định</p>
                        <div class="d-flex flex-column mb-8 fv-row">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                <span class="">Hồ sơ xin hưởng chế độ trợ cấp xã hội kèm theo giấy này gồm:</span>
                            </label>
                            <!--end::Label-->
                            <textarea @if (isset($don_parent)) @if ($don_parent->status > 0)
                                readonly @endif @endif class="form-control h-150px" name="hoso">{{ $phieu['hoso'] ?? '' }}</textarea>
                        </div>
                        <div class="fs-6 fw-semibol d-flex flex-column">
                            <p class="100">
                                <b>Hồ sơ minh chứng cần thiết</b> <br>
                                <b>* Với chế độ Trợ cấp xã hội</b> <br>
                                1- Giấy tờ minh chứng về nơi cư trú đối với SV ở vùng cao từ 3 năm trở lên. <br>
                                2- Bản phô tô công chứng Giấy chứng tử của bố mẹ. <br>
                                3- Bản phô tô công chứng Giấy khai sinh đối với những học sinh, sinh viên là mồ côi cha mẹ. <br>
                                4- Bản phô tô công chứng Giấy chứng nhận của cơ quan y tế về tình trạng bị khuyết tật, tàn tật đối với những SV khuyết tật, tàn tật. <br>
                                5- Bản phô tô công chứng sổ hộ nghèo hoặc hộ cận ghèo đói với những sinh viên có hoàn cảnh đặc biệt khó khăn <br>
                                <b>* Với chế độ Hỗ trợ chi phí học tập</b> <br>
                                1- Bản phô tô công chứng Giấy khai sinh <br>
                                2- Bản phô tô công chứng Sổ hộ nghèo hoặc hộ cận nghèo.
                            </p>
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
                                        {{ __('Sửa đơn xin trợ cấp') }}
                                    </button>
                                @endif
                            @else
                                <button type="submit" class="btn btn-success me-2">
                                    {{ __('Gửi đơn xin trợ cấp') }}
                                </button>
                            @endif


                            @isset($don_parent_tcxh)
                                <a href="{{ route('phieu.index', ['id' => $don_parent_tcxh->phieu_id]) }}" target="_blank" class="btn btn-warning me-2">Xem đơn trợ cấp xã hội</a>
                            @endisset
                            @isset($don_parent_mghp)
                                <a href="{{ route('phieu.index', ['id' => $don_parent_mghp->phieu_id]) }}" target="_blank" class="btn btn-warning me-2">Xem đơn trợ cấp học phí</a>
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
                    thuongchu: {
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
                        url: "{{ route('TroCapXH.KyDonPdf') }}",
                        data: formData,
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    }).then((response) => {
                        console.log(response);

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
