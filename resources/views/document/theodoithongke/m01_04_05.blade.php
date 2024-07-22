@extends('layout.doc_layout')

@section('data')
    <div id="doc_view" class="A4 d-flex flex-column" style="height: auto;">
        <div class="d-flex flex-row justify-content-between">
            <div>
                <div class="text-center">UBND TỈNH QUẢNG NINH</div>
                <div class="text-center fw-bold">TRƯỜNG ĐẠI HỌC HẠ LONG</div>
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
                <!-- <div class="text-center">CỘNG HOÀ XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>
                            <div class="text-center fw-bold">Độc lập - Tự do - Hạnh phúc</div>
                            <div
                              style="
            width: 203px;
            height: 1px;
            background-color: black;
            margin-top: -1px;
            margin-left: auto;
            margin-right: auto;
          "
                            ></div>
                            <div class="text-center fst-italic mt-1">
                              Quảng Ninh, ngày ?? tháng ?? năm ????
                            </div> -->
            </div>
        </div>
        <div class="text-center fw-bold" style="margin-top: 35px">
            DANH SÁCH SINH VIÊN ĐƯỢC HƯỞNG CHẾ ĐỘ HỖ TRỢ TIỀN ĂN <br />
            <div class="lh-1">
                Theo điểm f, g, khoản 3, điều 1, Nghị quyết 35/2021/NQ-HĐND tỉnh Quảng
                Ninh.
                <br />
                <div class="mt-1">
                    Học kỳ {{ $data[0]['ky'] }}, năm học {{ $data[0]['nam'] }}
                </div>

            </div>
        </div>
        <div class="fst-italic text-center mx-auto mt-1" style="max-width: 85%">
            (Kèm theo quyết định số {{ $data[0]['so_QD_TA'] }}/QĐ-ĐHHL, ngày {{ $data[0]['thoi_gian_tao_ngay'] }} tháng {{ $data[0]['thoi_gian_tao_thang'] }} năm {{ $data[0]['thoi_gian_tao_nam'] }}
            <br> của Hiệu trưởng Trường Đại học Hạ Long)
        </div>
        <div class="d-flex flex-column mt-5">
            <table class="border-1 border-black border" style="border-collapse: collapse;">
                <tr class="text-center p-0">
                    <th class="border border-1 border-black">Stt</th>
                    <th class="border border-1 border-black" style="width: 120px">
                        Họ và tên
                    </th>
                    <th class="border border-1 border-black">Ngày sinh</th>
                    <th class="border border-1 border-black">Lớp</th>
                    <th class="border border-1 border-black">Đối tượng</th>
                    <th class="border border-1 border-black">Mức hỗ trợ/tháng</th>
                    <th class="border border-1 border-black">Số tháng hỗ trợ</th>
                    <th class="border border-1 border-black">Số tiền hỗ trợ/kỳ</th>
                    <th class="border border-1 border-black">Ghi chú</th>
                </tr>
                @foreach ($data[1] as $index => $item)
                    <tr style="height: 30px; text-align: center">
                        <td>{{$index +1}}</td>
                        <td>{{$item['ho_ten']}}</td>
                        <td>{{$item['ngay_sinh']}}</td>
                        <td>{{$item['lop']}}</td>
                        <td>{{$item['doi_tuong']}}</td>
                        <td>{{$item['muc_tro_cap']}}</td>
                        <td>{{$item['so_thang_tro_cap']}}</td>
                        <td>{{$item['tro_cap_ky']}}</td>
                        <td></td>
                    </tr>
                @endforeach

                <tr style="height: 30px">
                    <td colspan="9">&nbsp;&nbsp;Tổng: {{$data[0]['tong_cdta']}}</td>
                </tr>
            </table>
        </div>
        <div class="d-flex flex-row justify-content-between px-5 mt-1">
            <div class="d-flex justify-content-end text-center fw-bold align-items-baseline">
                <span>số tiền bằng chữ:</span></span><span class="fw-light ms-1"> {{ numberInVietnameseCurrency($data[0]['tong_cdta']) }}
            </div>
            <div class="text-center px-5">
                <br />
            </div>
        </div>
    </div>
@endsection
