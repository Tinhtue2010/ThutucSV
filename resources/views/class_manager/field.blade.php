<div class="d-flex flex-row">
    <div class="d-flex flex-column mb-8 fv-row col-6 pe-4">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">Tên lớp</span>
        </label>
        <!--end::Label-->
        <input type="text" class="form-control" name="name" />
    </div>

    <div class="d-flex flex-column mb-8 fv-row col-6 pe-4">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">Khoa</span>
        </label>
        <!--end::Label-->
        <select data-dropdown-parent="#form_create" name="khoa_id" class="form-select form-select-solid" data-control="select2">
            @foreach ($khoas as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
    </div>

</div>
<div class="d-flex flex-row">
    <div class="d-flex flex-column mb-8 fv-row col-6 pe-4" id="select-parent-{{ $type }}-nganh">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">Ngành học</span>
        </label>
        <!--end::Label-->
        <select data-dropdown-parent="#select-parent-{{ $type }}-nganh" class="form-select form-select-solid" data-control="select2" name="nganh" data-placeholder="Ngành">
            <option value="Biểu diễn nhạc cụ truyền thống">Biểu diễn nhạc cụ truyền thống</option>
            <option value="Biểu diễn nhạc cụ Phương Tây">Biểu diễn nhạc cụ Phương Tây</option>
            <option value="Thanh nhạc (TC)">Thanh nhạc (TC)</option>
            <option value="Khoa học máy tính">Khoa học máy tính</option>
            <option value="Ngôn ngữ Hàn Quốc">Ngôn ngữ Hàn Quốc</option>
            <option value="Ngôn ngữ Anh">Ngôn ngữ Anh</option>
            <option value="Ngôn ngữ Nhật">Ngôn ngữ Nhật</option>
            <option value="Ngôn ngữ Trung Quốc">Ngôn ngữ Trung Quốc</option>
            <option value="Quản trị dịch vụ du lịch và lữ hành">Quản trị dịch vụ du lịch và lữ hành</option>
            <option value="Quản trị khách sạn">Quản trị khách sạn</option>
            <option value="Quản trị nhà hàng và dịch vụ ăn uống">Quản trị nhà hàng và dịch vụ ăn uống</option>
            <option value="Nuôi trồng thủy sản">Nuôi trồng thủy sản</option>
            <option value="Quản lý tài nguyên và môi trường">Quản lý tài nguyên và môi trường</option>
            <option value="Quản lý văn hóa">Quản lý văn hóa</option>
            <option value="Hội họa">Hội họa</option>
            <option value="Thanh nhạc (CĐ)">Thanh nhạc (CĐ)</option>
            <option value="Giáo dục Mầm non (ĐH)">Giáo dục Mầm non (ĐH)</option>
            <option value="Giáo dục Tiểu học">Giáo dục Tiểu học</option>
            <option value="Sư phạm Ngữ Văn">Sư phạm Ngữ Văn</option>
            <option value="Sư phạm">Sư phạm</option>
            <option value="Thiết kế đồ họa">Thiết kế đồ họa</option>
            <option value="Công nghệ thông tin">Công nghệ thông tin</option>            
        </select>
    </div>
    <div class="d-flex flex-column mb-8 fv-row col-6 pe-4">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">Giảng viên</span>
        </label>
        <!--end::Label-->
        <select data-dropdown-parent="#form{{ $type }}" name="teacher_id" class="form-select form-select-solid" data-control="select2">
            @foreach ($teachers as $item)
                <option value="{{ $item->id }}">{{ $item->full_name }}</option>
            @endforeach
        </select>
    </div>
</div>


<div class="d-flex flex-column mb-8 fv-row col-12 pe-4">
    <!--begin::Label-->
    <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
        <span class="required">{{ __('Học phí') }}</span>
    </label>
    <!--end::Label-->
    <input type="number" class="form-control" name="hocphi" />
</div>
