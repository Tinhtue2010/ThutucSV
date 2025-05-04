@extends('layout.doc_layout')

@section('data')
    <div id="doc_view" class="A4 d-flex flex-column" style="height: auto;">
        <div class="d-flex flex-row justify-content-between">
            <div>
                <div class="text-center">UBND TỈNH QUẢNG NINH</div>
                <div class="text-center fw-bold">TRƯỜNG ĐẠI HỌC HẠ LONG</div>
                <div
                    style="
      width: 94px;
      height: 1px;
      background-color: black;
      margin-top: 2px;
      margin-left: auto;
      margin-right: auto;
    ">
                </div>
            </div>
            <div>
            </div>
        </div>
        <div class="text-center fw-bold" style="margin-top: 35px; text-transform: uppercase">
            DANH SÁCH SINH VIÊN ĐƯỢC HƯỞNG TRỢ CẤP XÃ HỘI <br />
            HỌC KỲ I NĂM HỌC 2022 – 2023
        </div>
        <div class="fst-italic text-center mx-auto" style="max-width: 85%">
            (Kèm theo quyết định số {{ $data[0]['so_QD'] }}/QĐ-ĐHHL, ngày {{ $data[0]['thoi_gian_tao_ngay'] }} tháng
            {{ $data[0]['thoi_gian_tao_thang'] }} năm {{ $data[0]['thoi_gian_tao_nam'] }} của Hiệu
            trưởng Trường Đại học Hạ Long)
        </div>
        <div class="d-flex flex-column mt-5">
            <table class="table" style="border-collapse: collapse;font-size: 15px">
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
                </tr>
                @foreach ($data[1] as $index => $item)
                    <tr style="height: 30px; text-align: center">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['ho_ten'] }}</td>
                        <td>{{ $item['ngay_sinh'] }}</td>
                        <td>{{ $item['lop'] }}</td>
                        <td>{{ $item['doi_tuong'] }}</td>
                        <td>{{ number_format($item['muc_tro_cap_xh'], 0, ',', '.') }}</td>
                        <td>{{ $item['so_thang_tro_cap'] }}</td>
                        <td>{{ number_format($item['tro_cap_ky'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach

                <tr style="height: 30px">
                    <td class="" colspan="8">Tổng: {{ number_format($data[0]['tong'], 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
        <div class="d-flex flex-row justify-content-between px-5 mt-1">
            <div class="d-flex justify-content-end text-center align-items-baseline">
                <span style="text-align: left"><b>Số tiền bằng chữ:</b> {{ numberInVietnameseCurrency($data[0]['tong']) }}</span>
            </div>
            <div class="text-center px-5">
                <br />
            </div>
        </div>
    </div>
@endsection
