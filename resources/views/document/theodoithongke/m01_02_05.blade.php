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
          margin-top: 2px;
          margin-left: auto;
          margin-right: auto;
        "></div>
            </div>
            <div>
            </div>
        </div>
        <div class="text-center fw-bold" style="margin-top: 35px; text-transform: uppercase">
            DANH SÁCH SINH VIÊN ĐƯỢC MIỄN, GIẢM HỌC PHÍ THEO NGHỊ ĐỊNH <br> 81/2021/NĐ-CP. HỌC KỲ {{ $data[0]['ky'] == '1' ? 'I' : 'II' }} NĂM HỌC {{ $data[0]['nam'] }}
        </div>
        <div class="fst-italic text-center mx-auto" style="max-width: 85%;">(Kèm theo quyết định số {{ $data[0]['so_QD'] }}/QĐ-ĐHHL, ngày {{ $data[0]['thoi_gian_tao_ngay'] }} tháng {{ $data[0]['thoi_gian_tao_thang'] }} năm {{ $data[0]['thoi_gian_tao_nam'] }}
            <br> của Hiệu trưởng Trường Đại học Hạ Long)
        </div>
        <div class="d-flex flex-column mt-5">
            <table class="" style="border-collapse: collapse;">
                <tr class="text-center p-0">
                    <th class="" rowspan="2">Stt</th>
                    <th class="" style="width: 120px" rowspan="2">
                        Họ và tên
                    </th>
                    <th class="" rowspan="2">Ngày sinh</th>
                    <th class="" rowspan="2">Lớp</th>
                    <th class="" rowspan="2">Đối tượng</th>
                    <th class="" rowspan="2">
                        Mức học phí/tháng
                    </th>
                    <th class="" colspan="3">Mức miễn giảm</th>
                    <th class="" rowspan="2">
                        Số tiền miễn, giảm/kỳ
                    </th>
                </tr>
                <tr class="text-center">
                    <th class="">Tỷ lệ miễn, giảm</th>
                    <th class="">Số tiền miễn, giảm/tháng</th>
                    <th class="">Số tháng miễn, giảm</th>
                </tr>
                @foreach ($data[1] as $index => $item)
                    <tr style="height: 30px; text-align: center">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['ho_ten'] }}</td>
                        <td>{{ $item['ngay_sinh'] }}</td>
                        <td>{{ $item['lop'] }}</td>
                        <td>{{ $item['doi_tuong'] }}</td>
                        <td>{{ $item['muc_hoc_phi'] }}</td>
                        <td>{{ $item['ti_le_giam'] }}</td>
                        <td>{{ $item['so_tien_giam_1_thang'] }}</td>
                        <td>{{ $item['so_thang_mien_giam'] }}</td>
                        <td>{{ $item['mien_giam_ky'] }}</td>
                    </tr>
                @endforeach
                @foreach ($data[1] as $index => $item)
                    <tr style="height: 30px; text-align: center">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['ho_ten'] }}</td>
                        <td>{{ $item['ngay_sinh'] }}</td>
                        <td>{{ $item['lop'] }}</td>
                        <td>{{ $item['doi_tuong'] }}</td>
                        <td>{{ $item['muc_hoc_phi'] }}</td>
                        <td>{{ $item['ti_le_giam'] }}</td>
                        <td>{{ $item['so_tien_giam_1_thang'] }}</td>
                        <td>{{ $item['so_thang_mien_giam'] }}</td>
                        <td>{{ $item['mien_giam_ky'] }}</td>
                    </tr>
                @endforeach
                @foreach ($data[1] as $index => $item)
                    <tr style="height: 30px; text-align: center">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['ho_ten'] }}</td>
                        <td>{{ $item['ngay_sinh'] }}</td>
                        <td>{{ $item['lop'] }}</td>
                        <td>{{ $item['doi_tuong'] }}</td>
                        <td>{{ $item['muc_hoc_phi'] }}</td>
                        <td>{{ $item['ti_le_giam'] }}</td>
                        <td>{{ $item['so_tien_giam_1_thang'] }}</td>
                        <td>{{ $item['so_thang_mien_giam'] }}</td>
                        <td>{{ $item['mien_giam_ky'] }}</td>
                    </tr>
                @endforeach
                @foreach ($data[1] as $index => $item)
                    <tr style="height: 30px; text-align: center">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['ho_ten'] }}</td>
                        <td>{{ $item['ngay_sinh'] }}</td>
                        <td>{{ $item['lop'] }}</td>
                        <td>{{ $item['doi_tuong'] }}</td>
                        <td>{{ $item['muc_hoc_phi'] }}</td>
                        <td>{{ $item['ti_le_giam'] }}</td>
                        <td>{{ $item['so_tien_giam_1_thang'] }}</td>
                        <td>{{ $item['so_thang_mien_giam'] }}</td>
                        <td>{{ $item['mien_giam_ky'] }}</td>
                    </tr>
                @endforeach
                <tr style="height: 30px">
                    <td class="" colspan="2">Tổng: {{ $data[0]['tong'] }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>
        <div class="d-flex flex-row justify-content-between mt-1">
            <div class="d-flex justify-content-end text-center align-items-baseline">
                <span><b>Số tiền bằng chữ:</b> {{ numberInVietnameseCurrency($data[0]['tong']) }}</span>
            </div>
            <div class="text-center px-5">
                <br />
            </div>
        </div>
    </div>
@endsection
