<div class="d-flex flex-column mb-8 fv-row">
    <!--begin::Label-->
    <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
        <span class="required">Tên sinh viên</span>
    </label>
    <!--end::Label-->
    <input type="text" class="form-control" name="full_name" />
</div>
<div class="d-flex flex-row">
    <div class="d-flex flex-column mb-8 fv-row col-12 pe-4">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">Mã sinh viên</span>
        </label>
        <!--end::Label-->
        <input type="text" class="form-control" name="student_code" />
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
            <span>Số điện thoại</span>
        </label>
        <!--end::Label-->
        <input type="text" class="form-control" name="phone" />
    </div>
</div>

<div class="d-flex flex-row">
    <div class="d-flex flex-column mb-8 fv-row col-6 pe-4" id="khoa_{{ $type }}">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">Khoa</span>
        </label>
        <!--end::Label-->
        <select id="{{ $type }}_field_khoa" data-dropdown-parent="#khoa_{{ $type }}" name="khoa_id" class="form-select form-select-solid" data-control="select2">
            <option selected disabled>Chọn khoa</option>
            @foreach ($khoas as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="d-flex flex-column mb-8 fv-row col-6 ps-4" id="select-parent-{{ $type }}-1">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">lớp</span>
        </label>
        <!--end::Label-->
        <select data-dropdown-parent="#select-parent-{{ $type }}-1" name="lop_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Lớp">
            <option disabled selected>Chọn lớp</option>
        </select>
    </div>

</div>
<div class="d-flex flex-row">
    <div class="d-flex flex-column mb-8 fv-row col-6 pe-4">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">Liên khóa</span>
        </label>
        <!--end::Label-->
        <input type="text" value="{{ date('Y') }}" class="form-control" name="nien_khoa" />
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

@if ($type == '_update')
    <div class="d-flex flex-row">
        <div class="d-flex flex-column mb-8 fv-row col-12" id="select-parent-{{ $type }}-3">
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
    </div>
@endif
{{-- <div class="d-flex flex-row">
    <div class="d-flex flex-column mb-8 fv-row col-12" id="select-parent-{{ $type }}-4">
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
</div> --}}
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
        $(document).ready(function() {
            $('#{{ $type }}_field_khoa').change((e) => {
                const selectedValue = $(e.target).val();

                axios.get("{{ route('khoaManager.lop') }}/" + selectedValue).then(
                    response => {
                        $('select[name="lop_id"]').prop('disabled', false);
                        $('select[name="lop_id"]').empty();
                        response.data.forEach(e => {
                            var newOption = new Option(`${e.name} - ${e.hedaotao == 0 ? "ĐH" : e.hedaotao == 1 ? "THS" : e.hedaotao == 2 ? "CĐ" : "TC"}`, e.id, false, false);
                            $('select[name="lop_id"]').append(newOption).trigger('change');
                        });
                    }).then()

            })
        })
    </script>
@endpush
