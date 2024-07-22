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
        <div class="text-center fw-bold lh-1" style="margin-top: 35px">
            DANH SÁCH SINH VIÊN ĐƯỢC HƯỞNG CHẾ ĐỘ MIỄN PHÍ CHỖ Ở<br />KÝ TÚC XÁ HỌC KỲ
            {{ $data[0]['ky'] == "1" ? "I" : "II"}} NĂM HỌC {{ $data[0]['nam'] }}
            <div class="lh-1">
                Theo điểm g, khoản 3, điều 1, Nghị quyết 35/2021/NQ-HĐND tỉnh Quảng Ninh.
                <br />
                Học kỳ {{ $data[0]['ky'] }}, năm học {{ $data[0]['nam'] }}
            </div>
        </div>
        <div class="fst-italic text-center mx-auto" style="max-width: 85%">
            (Kèm theo quyết định số {{ $data[0]['so_QD_KTX_1'] }}/QĐ-ĐHHL, ngày {{ $data[0]['thoi_gian_tao_ngay'] }} tháng {{ $data[0]['thoi_gian_tao_thang'] }} năm {{ $data[0]['thoi_gian_tao_nam'] }} 
            <br> của Hiệu trưởng Trường Đại học Hạ Long)
        </div>
        <div class="d-flex flex-column mt-5">
            <table class="border-1 border-black border" style="
        border-collapse: collapse;
      ">
                <tr class="text-center p-0">
                    <th class="border border-1 border-black">Stt</th>
                    <th class="border border-1 border-black" style="width: 120px">
                        Họ và tên
                    </th>
                    <th class="border border-1 border-black">Ngày sinh</th>
                    <th class="border border-1 border-black">Tên lớp</th>
                    <th class="border border-1 border-black">Đối tượng hưởng</th>
                    <th class="border border-1 border-black">Ngày vào ở KTX</th>
                    <th class="border border-1 border-black">Số tháng được miễn</th>
                </tr>

                @foreach ($data[1] as $index => $item)
                    <tr style="height: 30px; text-align: center">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['ho_ten'] }}</td>
                        <td>{{ $item['ngay_sinh'] }}</td>
                        <td>{{ $item['lop'] }}</td>
                        <td>{{ $item['doi_tuong'] }}</td>
                        <td>{{ $item['ngay_vao'] }}</td>
                        <td>{{ $item['so_thang'] }}</td>
                    </tr>
                @endforeach
                <tr style="height: 30px">
                    <td class="" colspan="2">Tổng: {{ $data[0]['tong_hs_cdktx_1'] }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
            Danh sách có {{ $data[0]['tong_hs_cdktx_1'] }} sinh viên
        </div>
    </div>
@endsection
