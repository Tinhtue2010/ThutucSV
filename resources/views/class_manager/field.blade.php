<div class="d-flex flex-row">
    <div class="d-flex flex-column mb-8 fv-row col-6 pe-4">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">Tên lớp</span>
        </label>
        <!--end::Label-->
        <input type="text" class="form-control" name="name" />
    </div>

    <div class="d-flex flex-column mb-8 fv-row col-6 pe-4" id="khoa_{{ $type }}">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="required">Khoa</span>
        </label>
        <!--end::Label-->
        <select id="{{ $type }}_field_khoa" data-dropdown-parent="#khoa_{{ $type }}" name="ma_khoa" class="form-select form-select-solid" data-control="select2">
            <option selected disabled>Chọn khoa</option>
            @foreach ($khoas as $item)
                <option value="{{ $item->ma_khoa }}">{{ $item->name }}</option>
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
        <select disabled data-dropdown-parent="#select-parent-{{ $type }}-nganh" class="form-select form-select-solid" data-control="select2" name="nganh_id" data-placeholder="Ngành">
        </select>
    </div>
    <div class="d-flex flex-column mb-8 fv-row col-6 pe-4">
        <!--begin::Label-->
        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
            <span class="">Giảng viên</span>
        </label>
        <!--end::Label-->
        <select data-dropdown-parent="#form{{ $type }}" name="teacher_id" class="form-select form-select-solid" data-control="select2">
            <option selected disabled>Chọn giáo viên</option>
            @foreach ($teachers as $item)
                <option value="{{ $item->id }}">{{ $item->full_name }}</option>
            @endforeach
        </select>
    </div>
</div>




@push('js')
    <script>
        $(document).ready(function() {
            @if ($type == '_update')
                const select_khoa = $('#{{ $type }}_field_khoa').val();
                axios.get("{{ route('khoaManager.nganh') }}/" + select_khoa).then(
                    response => {
                        $('select[name="nganh_id"]').prop('disabled', false);
                        $('select[name="nganh_id"]').empty();
                        response.data.forEach(e => {
                            var newOption = new Option(`${e.tennganh} - ${e.hedaotao == 0 ? "ĐH" : e.hedaotao == 1 ? "THS" : e.hedaotao == 2 ? "CĐ" : "TC"} (${e.manganh})    `, e.manganh, false, false);
                            $('select[name="nganh_id"]').append(newOption).trigger('change');
                        });
                    }).then()
            @endif
            $('#{{ $type }}_field_khoa').change((e) => {
                const selectedValue = $(e.target).val();

                axios.get("{{ route('khoaManager.nganh') }}/" + selectedValue).then(
                    response => {
                        $('select[name="nganh_id"]').prop('disabled', false);
                        $('select[name="nganh_id"]').empty();
                        response.data.forEach(e => {
                            var newOption = new Option(`${e.tennganh} - ${e.hedaotao == 0 ? "ĐH" : e.hedaotao == 1 ? "THS" : e.hedaotao == 2 ? "CĐ" : "TC"} (${e.manganh})    `, e.manganh, false, false);
                            $('select[name="nganh_id"]').append(newOption).trigger('change');
                        });
                    }).then()

            })
        })
    </script>
@endpush
