<div class="d-flex flex-column mb-8 fv-row">
    <!--begin::Label-->
    <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
        <span class="required">Tên sinh viên</span>
    </label>
    <!--end::Label-->
    <input type="text" class="form-control" name="full_name" />
</div>
<div class="d-flex flex-row">
    <div class="d-flex flex-column mb-8 fv-row col-6 pe-4">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">Mã sinh viên</span>
        </label>
        <!--end::Label-->
        <input type="text" class="form-control" name="student_code" />
    </div>
    <div class="d-flex flex-column mb-8 fv-row col-6 ps-4">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="">Mã sinh viên</span>
        </label>
        <!--end::Label-->
        <input type="text" class="form-control" name="student_id" />
    </div>
</div>

<div class="d-flex flex-row">
    <div class="d-flex flex-column mb-8 fv-row col-6 pe-4">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">Ngày sinh</span>
        </label>
        <!--end::Label-->
        <input type="date" class="form-control" name="date_of_birth" />
    </div>
    <div class="d-flex flex-column mb-8 fv-row col-6 ps-4">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">Số điện thoại</span>
        </label>
        <!--end::Label-->
        <input type="text" class="form-control" name="phone" />
    </div>
</div>

<div class="d-flex flex-row">
    <div class="d-flex flex-column mb-8 fv-row col-6 pe-4" id="select-parent-{{ $type }}-1">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">lớp</span>
        </label>
        <!--end::Label-->
        <select data-dropdown-parent="#select-parent-{{ $type }}-1" name="lop_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Lớp">
            @foreach ($lops as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="d-flex flex-column mb-8 fv-row col-6 ps-4">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">Năm đăng ký</span>
        </label>
        <!--end::Label-->
        <input type="text" value="{{ date('Y') }}" class="form-control" name="school_year" />
    </div>
</div>
<div class="d-flex flex-row">
    <div class="d-flex flex-column mb-8 fv-row col-6 pe-4" id="select-parent-{{ $type }}-2">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">Hệ đào tạo</span>
        </label>
        <!--end::Label-->
        <select data-dropdown-parent="#select-parent-{{ $type }}-2" class="form-select form-select-solid" data-control="select2" data-hide-search="true" name="he_tuyen_sinh" data-placeholder="Hệ đào tạo">
            <option value="Đại học">Đại học</option>
            <option value="Cao đẳng chính quy">Cao đẳng chính quy</option>
            <option value="Trung cấp">Trung cấp</option>
        </select>
    </div>
    <div class="d-flex flex-column mb-8 fv-row col-6 ps-4">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">Ngày nhập học</span>
        </label>
        <!--end::Label-->
        <input type="date" class="form-control" name="ngay_nhap_hoc" />
    </div>
</div>

<div class="d-flex flex-row">
    <div class="d-flex flex-column mb-8 fv-row col-12 pe-4" id="select-parent-{{ $type }}-nganh">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">Ngành tuyển sinh</span>
        </label>
        <!--end::Label-->
        <select data-dropdown-parent="#select-parent-{{ $type }}-nganh" class="form-select form-select-solid" data-control="select2" data-hide-search="true" name="nganh_tuyen_sinh" data-placeholder="Ngành tuyển sinh">
        </select>
    </div>
</div>

<div class="d-flex flex-row">

    <div class="d-flex flex-column mb-8 fv-row col-6 pe-4" id="select-parent-{{ $type }}-3">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">Trạng thái</span>
        </label>
        <!--end::Label-->
        <select data-dropdown-parent="#select-parent-{{ $type }}-3" class="form-select form-select-solid" name="status" data-control="select2" data-hide-search="true" data-placeholder="Trạng thái">
            <option value="0">Đang học</option>
            <option value="1">Rút hồ sơ</option>
            <option value="2">Đã tốt nghiệp</option>
        </select>
    </div>
    <div class="d-flex flex-column mb-8 fv-row col-6 ps-4" id="select-parent-{{ $type }}-4">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">Giới tính</span>
        </label>
        <!--end::Label-->
        <select data-dropdown-parent="#select-parent-{{ $type }}-4" class="form-select form-select-solid" name="gioitinh" data-control="select2" data-hide-search="true" data-placeholder="Giới tính">
            <option value="0">Nữ</option>
            <option value="1">Nam</option>
        </select>
    </div>
</div>
<div class="d-flex flex-column mb-8 fv-row">
    <!--begin::Label-->
    <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
        <span>Ghi chú</span>
    </label>
    <!--end::Label-->
    <textarea class="form-control" name="note"></textarea>
</div>

@push('js')
    <script>
        var daiHoc = [
            "Công nghệ thông tin",
            "Khoa học máy tính",
            "Thiết kế đồ họa",
            "Nuôi trồng thủy sản",
            "Quản lý tài nguyên và môi trường",
            "Ngôn ngữ Anh",
            "Ngôn ngữ Nhật",
            "Ngôn ngữ Hàn Quốc",
            "Ngôn ngữ Trung Quốc",
            "Kế toán",
            "Quản trị kinh doanh",
            "Quản trị khách sạn",
            "Quản trị dịch vụ du lịch và lữ hành",
            "Quản trị nhà hàng và dịch vụ ăn uống",
            "Giáo dục Mầm non",
            "Giáo dục Tiểu học",
            "Sư phạm Tin học",
            "Sư phạm Ngữ Văn",
            "Sư phạm Tiếng Anh",
            "Sư phạm Khoa học tự nhiên",
            "Văn học",
            "Quản lý văn hóa"
        ];

        var caoDang = [
            "Giáo dục Mầm non",
            "Thanh nhạc"
        ];

        var trungCap = [
            "Biểu diễn nhạc cụ Phương Tây",
            "Biểu diễn nhạc cụ truyền thống",
            "Thanh nhạc",
            "Hội họa"
        ];

        $(document).ready(function() {
            const selectedValue = $('[data-dropdown-parent="#select-parent-{{ $type }}-2"]').val();
            $('[data-dropdown-parent="#select-parent-{{ $type }}-nganh"]').empty();

            let nganhHoc = [];
            if (selectedValue === "Đại học") {
                nganhHoc = daiHoc;
            } else if (selectedValue === "Cao đẳng chính quy") {
                nganhHoc = caoDang;
            } else if (selectedValue === "Trung cấp") {
                nganhHoc = trungCap;
            }

            nganhHoc.forEach(function(nganh) {
                $('[data-dropdown-parent="#select-parent-{{ $type }}-nganh"]').append(new Option(nganh, nganh));
            });
        });
        $('[data-dropdown-parent="#select-parent-{{ $type }}-2"]').change(function(e) {

            const selectedValue = $(this).val();
            $('[data-dropdown-parent="#select-parent-{{ $type }}-nganh"]').empty();

            let nganhHoc = [];
            if (selectedValue === "Đại học") {
                nganhHoc = daiHoc;
            } else if (selectedValue === "Cao đẳng chính quy") {
                nganhHoc = caoDang;
            } else if (selectedValue === "Trung cấp") {
                nganhHoc = trungCap;
            }

            nganhHoc.forEach(function(nganh) {
                $('[data-dropdown-parent="#select-parent-{{ $type }}-nganh"]').append(new Option(nganh, nganh));
            });
        });
    </script>
@endpush
