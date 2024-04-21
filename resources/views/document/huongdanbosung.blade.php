@extends('layout.doc_layout')

@section('data')
<div id="doc_view" class="A4 d-flex flex-column ">
    <div class="d-flex flex-row justify-content-between">
        <div>
            <div class="text-center">TRƯỜNG ĐẠI HỌC HẠ LONG</div>
            <div class="text-center fw-bold">PHÒNG CTCT, HT&SV</div>
            <div
                style="
            width: 94px;
            height: 1px;
            background-color: black;
            margin-top: -1px;
            margin-left: auto;
            margin-right: auto;
          ">
            </div>
        </div>
        <div>
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
            <div class="text-center fst-italic mt-1">
                Quảng Ninh, ngày {{$data['day']}} tháng {{$data['month']}} năm {{$data['year']}}
            </div>
        </div>
    </div>
    <div class="text-center fw-bold" style="margin-top: 35px">
        PHIẾU TIẾP NHẬN HỒ SƠ VÀ HẸN TRẢ KẾT QUẢ
    </div>
    <div class="d-flex flex-column">
        <p class="mb-0">
            Hồ sơ của sinh viên: {{$data['sinhvien']}}
            <br />
            Số định danh cá nhân/CMND: {{$data['cmnd']}} &nbsp;&nbsp;&nbsp; Ngày cấp: {{$data['ngaycap']}} <br />
            SĐT: {{$data['sdt']}} &nbsp; Email: {{$data['email']}}
            <br />
            Nội dung yêu cầu giải quyết:  {{$data['ndgiaiquyet']}}
            <br />
            Qua nghiên cứu hồ sơ,đề nghị sinh viên  {{$data['sinhvien']}} hoàn thiện hồ sơ gồm
            những nội dung sau:  
        </p>
        <ol>
            <li>
                Bổ sung thêm các giấy tờ, thủ tục sau:
                <p class="mb-0">
                    {{$data['bosunggiayto']}}
                </p>
            </li>
            <li>
                Kê khai lại các biểu mẫu sau:
                <p class="mb-0">
                    {{$data['kekhailaigiayto']}}
                </p>
            </li>
            <li>Hướng dẫn khác
                <p class="mb-0"> {{$data['huongdankhac']}}</p>
            </li>
        </ol>
        <p>
            Lý do:  {{$data['lydo']}} <br>
            Xin thông báo đến sinh viên  {{$data['sinhvien']}} được biết.
        </p>
    </div>
    <div class="d-flex flex-row justify-content-between px-5 mt-1">
        <div class="d-flex flex-column justify-content-end text-center"></div>
        <div class="text-center">
            <div class="fst-italic">Uông Bí, ngày  {{$data['day']}} tháng  {{$data['month']}} năm  {{$data['year']}}</div>
            <div class="fw-bold">NGƯỜI TIẾP NHẬN HỒ SƠ</div>
            <br />
            <br />
            <br />
            <br />
            {{$data['giaovien']}}
        </div>
    </div>
</div>
@endsection