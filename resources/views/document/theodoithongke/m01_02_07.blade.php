@extends('layout.doc_layout')

@section('data')
        <div id="doc_view" class="A4 d-flex flex-column times-font vietnamese-text" style="height: auto;">
        <div class="d-flex flex-row justify-content-between">
            <div>
                <div class="text-center">TRƯỜNG ĐẠI HỌC HẠ LONG</div>
                <div class="text-center fw-bold">PHÒNG CÔNG TÁC CHÍNH TRỊ, <br> QUẢN LÝ VÀ HỖ TRỢ SINH VIÊN</div>
                <div style="
          width: 94px;
          height: 1px;
          background-color: black;
          margin-top: -1px;
          margin-left: auto;
          margin-right: auto;
        "></div>
            </div>
            <div>
                <span class="fst-italic">Uông bí, ngày {{ $data[0]['thoi_gian_tao_ngay'] }} tháng {{ $data[0]['thoi_gian_tao_thang'] }} năm {{ $data[0]['thoi_gian_tao_nam'] }}</span>
            </div>
        </div>
        <div class="text-center fw-bold h3" style="margin-top: 35px">PHIẾU TRÌNH</div>
        <div class="text-center d-flex flex-column">
            <span>Kính gửi: Đồng chí Trần Trung Vỹ</span>
            <span>Vấn đề trình: Trình ký duyệt danh sách miễn giảm học phí</span>
        </div>
        <div class="d-flex flex-column mt-3">
            <table class="table" style="border-collapse: collapse; ">
                <tr class="text-center p-0">
                    <th class="">
                        TÓM TẮT TÌNH HÌNH VÀ KIẾN NGHỊ
                    </th>
                    <th class="">QUYẾT ĐỊNH CỦA CẤP TRÊN</th>
                </tr>
                <tr style="height: 30px" class="py-0">
                    <th class="">1. Tóm tắt tình hình</th>
                    <th>
                    </th>
                </tr>
                <tr style="height: 120px">
                    <td class="align-top" style="text-indent: 20px">
                        {{ $data[0]['tom_tat'] }}
                    </td>
                    <td></td>
                </tr>
                <tr style="height: 160px">
                    <td class="align-top">
                        <p class="fw-bold fst-italic">
                            Quyết định miễn, giảm học phí cho SV học kỳ {{ $data[0]['ky'] }},<br />
                            năm học {{ $data[0]['nam'] }} Số Quyết định {{ $data[0]['so_QD'] }},<br />
                            ngày {{ $data[0]['thoi_gian_tao_ngay'] }} tháng {{ $data[0]['thoi_gian_tao_thang'] }} năm {{ $data[0]['thoi_gian_tao_nam'] }} <br />
                            <span class="fst-normal fw-medium">
                                Số SV miễn 100%: {{ $data[0]['100'] }}, số tiền là: {{ $data[0]['100_tong'] }}. <br />
                                Số SV miễn 70%: {{ $data[0]['70'] }}, số tiền là: {{ $data[0]['70_tong'] }}. <br />
                                Số SV miễn 50%: {{ $data[0]['50'] }}, số tiền là: {{ $data[0]['50_tong'] }}. <br />
                                Tổng số SV được miễn giảm: {{ $data[0]['tong_hs'] }}. <br />
                                Tổng số tiền: {{ $data[0]['tong'] }}.
                            </span>
                        </p>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <th class="">2. Ý kiến của người trình</th>
                    <th>

                    </th>
                </tr>
                <tr>
                    <td class="align-top">
                        Kính trình Đồng chí Trần Trung Vỹ duyệt, ký ban hành <br />
                        Quyết định (Có Danh sách kèm theo)
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <th class="">
                        3. Ý kiến của các đơn vị liên quan
                    </th>
                    <th></th>
                </tr>
                <tr style="height: 100px">
                    <td class="align-top">Ý kiến của Phòng KH-TC: <br>
                        @isset($data[0]['y_kien_khtc'])
                            {{ $data[0]['y_kien_khtc'] }}
                        @endisset
                    </td>
                    <td>
                    </td>
                </tr>
                <tr>
                    <th class="">
                        4. Ý kiến của Trưởng phòng:
                    </th>
                    <th></th>
                </tr>
                <tr style="height: 80px">
                    <td class="align-top">
                        Kính trình đồng chí Trần Trung Vỹ xem xét, phê duyệt.
                    </td>
                    <td class="text-center">
                        @if (!empty($data[0]['canbo_truong']))
                            <br>
                            <img style="height: 50px" src="{{ asset('storage/' . $data[0]['canbo_truong_chu_ky']) }}" alt="">
                            <br>
                            {{ $data[0]['canbo_truong'] }}
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        <div class="d-flex flex-row justify-content-between px-5 mt-1"></div>
    </div>
@endsection
