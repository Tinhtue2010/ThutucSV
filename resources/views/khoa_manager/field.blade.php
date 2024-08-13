<div class="d-flex flex-column mb-8 fv-row">
    <!--begin::Label-->
    <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
        <span class="required">Tên khoa</span>
    </label>
    <!--end::Label-->
    <input type="text" class="form-control" name="name"/>
</div>

<div class="d-flex flex-column mb-8 fv-row" id="select-parent-{{ $type }}-nganh">
    <!--begin::Label-->
    <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
        <span class="required">Tên khoa</span>
    </label>
    <!--end::Label-->
    <select data-dropdown-parent="#select-parent-{{ $type }}-nganh" class="form-select form-select-solid" data-control="select2" name="nganh[]" data-placeholder="Ngành" multiple>
        <option value="Biểu diễn nhạc cụ truyền thống">Biểu diễn nhạc cụ truyền thống</option>
    </select>
</div>