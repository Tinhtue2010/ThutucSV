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
            <span class="required">Ngày sinh</span>
        </label>
        <!--end::Label-->
        <input type="text" class="form-control datepicker" name="date_of_birth" readonly />
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
    <div class="d-flex flex-column mb-8 fv-row col-6 pe-4">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">Số định danh cá nhân/CMND</span>
        </label>
        <!--end::Label-->
        <input type="text" class="form-control" name="cmnd" />
    </div>
    <div class="d-flex flex-column mb-8 fv-row col-6 ps-4">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">Ngày cấp</span>
        </label>
        <!--end::Label-->
        <input type="text" class="form-control datepicker" name="date_range_cmnd" readonly />
    </div>
</div>
<div class="d-flex flex-column mb-8 fv-row">
    <!--begin::Label-->
    <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
        <span class="required">Email</span>
    </label>
    <!--end::Label-->
    <input type="email" class="form-control" name="email" />
</div>
<div class="d-flex flex-column mb-8 fv-row">
    <!--begin::Label-->
    <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
        <span class="required">Chữ ký</span>
    </label>
    <!--end::Label-->
    <input type="file" class="form-control" name="chu_ky" accept="image/jpg, image/jpeg, image/png" />
</div>
<div class="d-flex flex-column mb-8 fv-row">
    <!--begin::Label-->
    <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
        <span>Mật khẩu mới</span>
    </label>
    <!--end::Label-->
    <input type="password" class="form-control" name="password" />
</div>
