<div class="d-flex flex-row">
    <div class="d-flex flex-column mb-8 fv-row col-6 pe-4">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">Tên lớp</span>
        </label>
        <!--end::Label-->
        <input type="text" class="form-control form-control-solid" name="name" />
    </div>

    <div class="d-flex flex-column mb-8 fv-row col-6 pe-4">

        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">Ngành học</span>
        </label>
        <!--end::Label-->
        <input type="text" class="form-control form-control-solid" name="nganh" />
    </div>
</div>
<div class="d-flex flex-row">
    <div class="d-flex flex-column mb-8 fv-row col-6 pe-4">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">Khoa</span>
        </label>
        <!--end::Label-->
        <select data-dropdown-parent="#select-parent-{{ $type }}-1" name="khoa_id"
            class="form-select form-select-solid" data-control="select2">
            @foreach ($khoas as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="d-flex flex-column mb-8 fv-row col-6 pe-4">

        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">Giảng viên</span>
        </label>
        <!--end::Label-->
        <select data-dropdown-parent="#select-parent-{{ $type }}-1" name="teacher_id"
            class="form-select form-select-solid" data-control="select2">
            @foreach ($teachers as $item)
                <option value="{{ $item->id }}">{{ $item->full_name }}</option>
            @endforeach
        </select>
    </div>
</div>
