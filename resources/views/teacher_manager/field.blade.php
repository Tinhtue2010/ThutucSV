<div class="d-flex flex-column mb-8 fv-row">
    <!--begin::Label-->
    <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
        <span class="required">Họ tên</span>
    </label>
    <!--end::Label-->
    <input type="text" class="form-control" name="full_name" />
</div>
<div class="d-flex flex-row">
    <div class="d-flex flex-column mb-8 fv-row col-6 pe-4">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">Khoa</span>
        </label>
        <!--end::Label-->
        <select data-dropdown-parent="#select-parent-{{$type}}-1" name="khoa_id" class="form-select form-select-solid" data-control="select2">
            @foreach ($khoas as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="d-flex flex-column mb-8 fv-row col-6 ps-4">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="">Địa chỉ</span>
        </label>
        <!--end::Label-->
        <input type="text" class="form-control" name="dia_chi" />
    </div>
</div>

<div class="d-flex flex-row">
    <div class="d-flex flex-column mb-8 fv-row col-6 pe-4">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">Số điện thoại</span>
        </label>
        <!--end::Label-->
        <input type="text" class="form-control" name="sdt" />
    </div>
    <div class="d-flex flex-column mb-8 fv-row col-6 ps-4">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">Email</span>
        </label>
        <!--end::Label-->
        <input type="email" class="form-control" name="email" />
    </div>
</div>

<div class="d-flex flex-row">
    <div class="d-flex flex-column mb-8 fv-row col-6 pe-4"  id="select-parent-{{$type}}-1">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">Chức danh</span>
        </label>
        <!--end::Label-->
        <input type="text" class="form-control" name="chuc_danh" />
    </div>
</div>