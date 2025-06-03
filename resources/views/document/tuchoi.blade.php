@extends('layout.doc_layout')

@section('data')
        <div id="doc_view" class="A4 d-flex flex-column times-font vietnamese-text" style="height: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <!-- Cột bên trái -->
                <td style="width: 40%; text-align: center; vertical-align: top;">
                    <div>TRƯỜNG ĐẠI HỌC HẠ LONG</div>
                    <div class="fw-bold">PHÒNG CTCT, HT&SV</div>
                    <div style="width: 94px; height: 1px; background-color: black; margin: -1px auto 0;"></div>
                </td>
        
                <!-- Cột bên phải -->
                <td style="width: 60%; text-align: center; vertical-align: top;">
                    <div>CỘNG HOÀ XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>
                    <div class="fw-bold">Độc lập - Tự do - Hạnh phúc</div>
                    <div style="width: 203px; height: 1px; background-color: black; margin: -1px auto 0;"></div>
                    <div class="fst-italic mt-1">
                        Quảng Ninh, ngày {{ $data['day'] }} tháng {{ $data['month'] }} năm {{ $data['year'] }}
                    </div>
                </td>
            </tr>
        </table>
        
        <div class="text-center fw-bold" style="margin-top: 35px">
            PHIẾU TỪ CHỐI TIẾP NHẬN, GIẢI QUYẾT HỒ SƠ
        </div>
        <div class="d-flex flex-column mt-3">
            <p class="mb-0">
                Hồ sơ của sinh viên: {{ $data['sinhvien'] }}
                <br />
                Số định danh cá nhân/CMND: {{ $data['cmnd'] }} Ngày cấp: {{ $data['ngaycap'] }} <br />
                SĐT: {{ $data['sdt'] }} &nbsp; Email: {{ $data['email'] }}
                <br />
                Nội dung yêu cầu giải quyết: {{ $data['ndgiaiquyet'] }}
                <br />Qua xem xét hồ sơ, Phòng Công tác chính trị, Quản lý và Hỗ trợ sinh
                viên thông báo không tiếp nhận, giải quyết hồ sơ này với lý do, cụ thể như
                sau:
            </p>
            <p style="min-height: 100px">
                &emsp;{{ $data['lydo'] }} <br />
            </p>
            <p> Xin thông báo đến sinh viên {{ $data['sinhvien'] }} được biết.
            </p>
        </div>
        <div class="d-flex flex-row justify-content-between px-5 mt-1">
            <div class="d-flex flex-column justify-content-end text-center"></div>
            <div class="text-center">
                <div class="fst-italic">Uông Bí, ngày {{ $data['day'] }} tháng {{ $data['month'] }} năm
                    {{ $data['year'] }}</div>
                <div class="fw-bold">NGƯỜI TIẾP NHẬN HỒ SƠ</div>
                <br />
                <img style="height: 50px;widows: 50px" src="data:image/png;base64,{{ $data['chu_ky'] }}" alt="">
                <br />
                {{ $data['giaovien'] }}
            </div>
        </div>
    </div>
@endsection
