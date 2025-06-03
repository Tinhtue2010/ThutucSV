@extends('layout.doc_layout')

@section('data')
        <div id="doc_view" class="A4 d-flex flex-column times-font vietnamese-text" style="height: auto;">
        <div class="text-center">CỘNG HOÀ XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>
        <div class="text-center fw-bold">Độc lập - Tự do - Hạnh phúc</div>
        <div style="
        width: 203px;
        height: 1px;
        background-color: black;
        margin-top: -1px;
        margin-left: auto;
        margin-right: auto;
      "></div>
        <div class="text-center fw-bold" style="margin-top: 35px">
            ĐƠN XIN HƯỞNG TRỢ CẤP XÃ HỘI
        </div>
        <div style="margin-top: 15px" class="d-flex flex-row lh-sm justify-content-center">
            <div>Kính gửi:</div>
            <div class="d-flex flex-column" style="margin-left: 10px">
                <p>Ban Giám hiệu Trường Đại học Hạ Long</p>
            </div>
        </div>
        <div class="d-flex flex-column">
            <div>
                Họ và tên: {{ $data['full_name'] }} &nbsp;&nbsp;
                Sinh ngày: {{ $data['date_of_birth'] }}
                <br />
                Lớp: {{ $data['lop'] }} &nbsp; Khoa: {{ $data['khoa'] }} &nbsp; SĐT: {{ $data['sdt'] }}
                <br />
                Nơi thường trú: {{ $data['thuongtru'] }}
                <br />
                Thuộc đối tượng: {{ $data['doituong'] }} <br>
                Hồ sơ xin hưởng chế độ trợ cấp xã hội kèm theo giấy này gồm: {{ $data['hoso'] }} <br>
                <span class="ms-3">Kính đề nghị Trường xem xét và giải quyết cho tôi được hưởng trợ cấp xã hội theo qui định hiện hành.</span>
            </div>
        </div>
        <div class="d-flex flex-row justify-content-end mt-2">
            <div class="fst-italic">Quảng Ninh, ngày {{ $data['day'] }} tháng {{ $data['month'] }} năm {{ $data['year'] }}</div>
        </div>
        <table style="width: 100%; margin-top: 20px; text-align: center; font-weight: bold;">
            <tr>
                <td>BAN GIÁM HIỆU DUYỆT</td>
                <td>NGƯỜI LÀM ĐƠN</td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <img style="height: 50px; width: 50px;" src="data:image/png;base64,{{ $data['chu_ky'] }}" alt="">
                </td>
            </tr>
            <tr>
                <td></td>
                <td>{{ $data['full_name'] }}</td>
            </tr>
        </table>
        
    </div>
@endsection
