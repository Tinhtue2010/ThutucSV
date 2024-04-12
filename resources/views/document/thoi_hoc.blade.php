@extends('layout.doc_layout')

@section('data')
<div id="doc_view" class="A4 d-flex flex-column ">
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
      "
    ></div>
    <div class="text-center fw-bold" style="margin-top: 35px">
      ĐƠN XIN THÔI HỌC VÀ RÚT HỒ SƠ
    </div>
    <div
      style="margin-left: 45px; margin-top: 15px"
      class="d-flex flex-row lh-sm"
    >
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
        Họ và tên sinh viên: {{$data['full_name']}} &nbsp;&nbsp; Sinh ngày: {{$data['date_of_birth']}}
        <br />
        Lớp: {{$data['lop']}} &nbsp; Khoa: {{$data['khoa']}}
        <br />
        Lý do xin thôi học và rút hồ sơ: {{$data['data']}}
        <br />
        Em xin bồi hoàn kinh phí đào tạo theo quy định của Trường.
        <br />
        Em xin chân thành cảm ơn!
      </p>
    </div>
    <div class="d-flex flex-row justify-content-end">
      <div class="fst-italic">Quảng Ninh, ngày {{$data['day']}} tháng {{$data['month']}} năm {{$data['year']}}</div>
    </div>
    <div class="d-flex flex-row justify-content-between px-5 mt-3">
      <div class="d-flex flex-column justify-content-end text-center">
        Xác nhận của GVCN lớp
      </div>
      <div class="text-center">
        NGƯỜI VIẾT ĐƠN
        <br />
        ( Ký và ghi rõ họ và tên)
      </div>
    </div>
    
    <div
    class="d-flex flex-row justify-content-between px-5"
    style="margin-top: 70px"
  >
    <div class="text-center"></div>
    <div class="text-center">{{$data['full_name']}}</div>
  </div>
    <div
      class="d-flex flex-row justify-content-between px-5"
      style="margin-top: 20px"
    >
      <div class="text-center">Xác nhận của phòng KH-TC</div>
      <div class="text-center">Xác nhận của lãnh đạo khoa</div>
    </div>
    <div
      class="d-flex flex-row justify-content-between ps-5"
      style="margin-top: 100px"
    >
      <div class="text-center">BAN GIÁM HIỆU DUYỆT</div>
      <div class="text-center">
        Phòng CTSV<br />
        ( Xác nhận chế độ bồi hoàn kinh phí đào tạo)
      </div>
    </div>
  </div>
  @endsection