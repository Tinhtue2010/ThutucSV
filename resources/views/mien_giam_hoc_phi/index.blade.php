@extends('layout.main_layout')

@section('content')
    @include('mien_giam_hoc_phi.header')
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
                            @isset($don->phieu_id)
                                <a href="{{ route('phieu.index', ['id' => $don->phieu_id]) }}" target="_blank" class="btn btn-primary">Xem phiếu</a>
                            @endisset

                        </div>
                    @endif

                @endisset

                <div class="card card-flush p-5">

                    <p class="fw-medium fs-5">Họ và tên : {{ $student->full_name }}</p>
                    <p class="fw-medium fs-5">Ngày sinh : {{ date('d/m/Y', strtotime($student->date_of_birth)) }}</p>
                    <p class="fw-medium fs-5">Số điện thoại : {{ $student->phone }}</p>
                    <p class="fw-medium fs-5">Lớp : {{ $student->lop_name }}</p>
                    <p class="fw-medium fs-5">Khoa : {{ $student->khoa_name }}</p>

                    <p class="fw-medium fs-5">Mã sinh viên : {{ $student->student_code }}</p>
                    <form action="" id="form_create">
                        @csrf
                        <div class="d-flex flex-column mb-8 fv-row">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                <span class="">Nơi sinh</span>
                            </label>
                            <!--end::Label-->
                            <input @if (isset($don_parent)) @if ($don_parent->status != 0)
                            readonly @endif @endif class="form-control" name="noisinh" value="{{ $phieu['noisinh'] ?? '' }}"/>
                        </div>
                        <div class="d-flex flex-column mb-8 fv-row">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                <span class="">Thuộc đối tượng: (ghi rõ đối tượng được quy định tại Nghị định số 81/2021/NĐ-CP)</span>
                            </label>
                            <select @if (isset($don_parent)) @if ($don_parent->status != 0)
                                readonly @endif @endif name="doituong" class="form-select form-select-solid filter-select" data-name="year" data-control="select2" data-placeholder="Năm">
                                @foreach (config('doituong.miengiamhp') as $index => $item)
                                    <option value="{{ $index }}">{{ $item[2] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex flex-column mb-8 fv-row">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                <span class="">Đã được hưởng chế độ miễn, giảm học phí (ghi rõ tên cơ sở đã được hưởng chế độ miễn giảm học phí, cấp học và trình độ đào tạo):</span>
                            </label>
                            <!--end::Label-->
                            <textarea @if (isset($don_parent)) @if ($don_parent->status != 0)
                                readonly @endif @endif class="form-control h-150px" name="daduochuong">{{ $phieu['daduochuong'] ?? '' }}</textarea>
                        </div>
                        <div class="fs-6 fw-semibol d-flex flex-column">
                            <p class="100"><b>Hồ sơ minh chứng cần thiết</b>
                                <br>
                                1- Bản phô tô công chứng Giấy chứng nhận thương binh, bệnh binh của cha hoặc mẹ sinh viên; giấy khai sinh của sinh viên. <br>
                                2- Bản phô tô công chứng Giấy chứng nhận của cơ quan y tế về tình trạng bị khuyết tật, tàn tật đối với những sinh viên khuyết tật, tàn
                                tật. <br>
                                3- Bản phô tô công chứng Giấy chứng tử của bố, mẹ hoặc Giấy chứng tử của bố (mẹ), người còn lại mất tích, bị bệnh tâm thần, …; giấy
                                khai sinh đối với những sinh viên mồ côi cha mẹ khi chưa đủ 16 tuổi. <br>
                                4- Bản phô tô công chứng Sổ hộ nghèo hoặc hộ cận nghèo <br>
                                5- Bản phô tô công chứng Giấy khai sinh, giấy tờ minh chứng về chỗ ở đối với SV thuộc dân tộc thiểu số rất ít người. <br>
                            </p>
                            <p class="70" style="display: none"><b>Hồ sơ minh chứng cần thiết</b>
                                <br>
                                - Giấy tờ minh chứng về chỗ ở đối với sinh viên ở thôn, bản đặc biệt khó khăn, xã khu vực III vùng dân tộc miền núi, xã đặc biệt khó
                                khăn vùng bãi ngang ven biển hải đảo theo quy định của cơ quan có thẩm quyền (Theo QĐ 433/QĐ-UBMT ngày 18/6/2021; QĐ số 861/QĐ-TTg ngày 04/6/2021; 353/QĐ-TTg ngày 15/3/2022)
                            </p>
                            <p class="50" style="display: none"><b>Hồ sơ minh chứng cần thiết</b>
                                <br>
                                - Bản phô tô công chứng Quyết định và Giấy chứng nhận trợ cấp TNLĐ-BNN của Bảo hiểm xã hội cấp; giấy khai sinh của sinh viên.
                            </p>
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
                                    {{ __('Gửi đơn') }}
                                </button>
                            @endif


                            @isset($don_parent)
                                <a href="{{ route('phieu.index', ['id' => $don_parent->phieu_id]) }}" target="_blank" class="btn btn-warning me-2">Xem đơn</a>
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
                    noisinh: {
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
                    "files[]": {
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
                    await checkMaXacNhan().then(function(result) {
                        if (false) {
                            return;
                        } else {
                            formData.append('otp', result);
                        }
                    });
                    axios({
                        method: 'POST',
                        url: "{{ route('MienGiamHp.CreateViewPdf') }}",
                        data: formData,
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    }).then((response) => {
                        if ($('#button_clicked').val() == 'xem_truoc') {
                            window.open("{{ route('MienGiamHp.viewDemoPdf') }}", "_blank");
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

        $('[name="doituong"]').on('change', function() {
            if (this.value < 5) {
                $(".100").show();
                $(".70").hide();
                $(".50").hide();
            } else if (this.value == 7) {
                $(".100").hide();
                $(".70").hide();
                $(".50").show();
            } else {
                $(".100").hide();
                $(".70").show();
                $(".50").hide();
            }
        })
    </script>
@endpush
