@extends('layout.doc_layout')

@section('data')
        <div id="doc_view" class="A4 d-flex flex-column times-font vietnamese-text" style="height: auto;">
        <div class="text-center">CỘNG HOÀ XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>
        <div class="text-center fw-bold">Độc lập - Tự do - Hạnh phúc</div>
        <div
            style="
        width: 203px;
        height: 1px;
        background-color: black;
        margin-top: -1px;
        margin-left: auto;
        margin-right: auto;
      ">
        </div>
        <div class="text-center fw-bold" style="margin-top: 35px">
            ĐƠN XIN THÔI HỌC VÀ RÚT HỒ SƠ
        </div>
        <div style="margin-left: 45px; margin-top: 15px" class="d-flex flex-row lh-sm">
            <div>Kính gửi:</div>
            <div class="d-flex flex-column" style="margin-left: 10px">
                <p>
                    Ban Giám hiệu Trường Đại học Hạ Long<br />
                    Phòng Công tác chính trị, Quản lý và Hỗ trợ sinh viên
                </p>
            </div>
        </div>
        <div class="d-flex flex-column">
            <p>
                <p>Họ và tên sinh viên: {{ $data['full_name'] }} &nbsp;&nbsp; Sinh ngày: {{ $data['date_of_birth'] }}</p>
                <p>Lớp: {{ $data['lop'] }} &nbsp; Khoa: {{ $data['khoa'] }}</p>
                
                <p style="height: 100px">Lý do xin thôi học và rút hồ sơ: {{ $data['data'] }}</p>
                <p>
                    Em xin bồi hoàn kinh phí đào tạo theo quy định của Trường.
                    <br />
                Em xin chân thành cảm ơn!
                </p>
                
            </p>
        </div>
        <div class="d-flex flex-row justify-content-end" style="float: right">
            <div class="fst-italic">Quảng Ninh, ngày {{ $data['day'] }} tháng {{ $data['month'] }} năm {{ $data['year'] }}
            </div>
        </div>

        <table style="width: 100%; margin-top: 30px">

            <body class="d-flex flex-row justify-content-between px-5 mt-3">
                <tr>
                    <td class="d-flex flex-column justify-content-end text-center">
                        <b>Xác nhận của GVCN lớp</b>
                        <br />
                        <br>
                        <br>
                        <br>
                        <br>
                    </td>
                    <td class="text-center">
                        <b>NGƯỜI VIẾT ĐƠN</b>
                        <br>
                        <br>
                        <img style="height: 50px;widows: 50px" src="data:image/png;base64,{{ $data['chu_ky'] }}" alt="">
                        <br>
                        <br>
                        {{ $data['full_name'] }}
                    </td>
                </tr>
                <tr >
                    <td class="d-flex flex-column justify-content-end text-center" style="padding-top: 20px">
                        <div class="text-center"><b>Xác nhận của phòng KH-TC</b>
                            <br />
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        </div>
                    </td>
                    <td class="text-center" style="padding-top: 20px">
                        <b>Xác nhận của lãnh đạo khoa</b>
                        <br />
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                    </td>
                </tr>

                <tr>
                    <td>
                        <b>BAN GIÁM HIỆU DUYỆT</b>
                        <br />
                        <br>
                        <br>
                        <br>
                        <br />
                        <br>
                    </td>
                    <td style="text-align: center">
                        <b>Phòng CTSV</b><br />
                        (Xác nhận chế độ bồi hoàn kinh phí đào tạo)
                        <br />
                        <br>
                        <br>
                        <br>
                        <br>
                    </td>
                </tr>
            </body>
        </table>

    </div>
@endsection
