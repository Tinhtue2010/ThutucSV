<div class="d-flex flex-column mb-8 fv-row">
    <!--begin::Label-->
    <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
        <span class="required">Tên sinh viên</span>
    </label>
    <!--end::Label-->
    <input type="text" class="form-control form-control-solid" name="full_name" />
</div>
<div class="d-flex flex-row">
    <div class="d-flex flex-column mb-8 fv-row col-6 pe-4">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">Mã sinh viên</span>
        </label>
        <!--end::Label-->
        <input type="text" class="form-control form-control-solid" name="student_code" />
    </div>
    <div class="d-flex flex-column mb-8 fv-row col-6 ps-4">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="">Mã sinh viên</span>
        </label>
        <!--end::Label-->
        <input type="text" class="form-control form-control-solid" name="student_id" />
    </div>
</div>

<div class="d-flex flex-row">
    <div class="d-flex flex-column mb-8 fv-row col-6 pe-4">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">Ngày sinh</span>
        </label>
        <!--end::Label-->
        <input type="date" class="form-control form-control-solid" name="date_of_birth" />
    </div>
    <div class="d-flex flex-column mb-8 fv-row col-6 ps-4">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">Số điện thoại</span>
        </label>
        <!--end::Label-->
        <input type="text" class="form-control form-control-solid" name="phone" />
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
        <input type="text" value="{{ date('Y') }}" class="form-control form-control-solid" name="school_year" />
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
            <option value="Liên thông đại học">Liên thông đại học</option>
            <option value="Thạc sĩ">Thạc sĩ</option>
        </select>
    </div>
    <div class="d-flex flex-column mb-8 fv-row col-6 ps-4">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">Ngày nhập học</span>
        </label>
        <!--end::Label-->
        <input type="date" class="form-control form-control-solid" name="ngay_nhap_hoc" />
    </div>
</div>

<div class="d-flex flex-row">
    <div class="d-flex flex-column mb-8 fv-row col-6 pe-4">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">Ngành tuyển sinh</span>
        </label>
        <!--end::Label-->
        <input type="text" class="form-control form-control-solid" name="nganh_tuyen_sinh" />
    </div>
    <div class="d-flex flex-column mb-8 fv-row col-6 ps-4">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">Trình độ</span>
        </label>
        <!--end::Label-->
        <input type="text" class="form-control form-control-solid" name="trinh_do" />
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
    <textarea class="form-control form-control-solid" name="note"></textarea>
</div>
