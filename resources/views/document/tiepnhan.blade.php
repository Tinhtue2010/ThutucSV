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
          "
        ></div>
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
          "
        ></div>
        <div class="text-center fst-italic mt-1">
          Quảng Ninh, ngày {{$data['tao_day']}} tháng {{$data['tao_month']}} năm {{$data['tao_year']}}
        </div>
      </div>
    </div>
  
    <div class="text-center fw-bold" style="margin-top: 35px">
      PHIẾU TIẾP NHẬN HỒ SƠ VÀ HẸN TRẢ KẾT QUẢ
    </div>
    <div class="text-center">Mã hồ sơ: {{$phieu->key}}{{$phieu->id}}</div>
    <div class="d-flex flex-column">
      <p>
        Phòng Công tác chính trị, Quản lý và Hỗ trợ sinh viên đã tiếp nhận hồ sơ
        của: <br />
        Sinh viên: {{$data['sinhvien']}}
        <br />
        Số định danh cá nhân/CMND: {{$data['cmnd']}}  Ngày cấp : {{$data['ngaycap']}}<br />
        SĐT:  {{$data['sdt']}} &nbsp; Email:  {{$data['email']}}
        <br />
        Nội dung yêu cầu giải quyết:  {{$data['ndgiaiquyet']}}
        <br />
        Thành phần hồ sơ nộp gồm:
      </p>
      <table class="table lh-1">
        <tr class="text-center">
          <th scope="col" style="width: 50px">TT</th>
          <th scope="col">Tên giấy tờ</th>
          <th scope="col" style="width: 160px">
            <div>Hình thức</div>
            <div>(bản chinh,bản sao hoặc bản chụp)</div>
          </th>
          <th scope="col" style="width: 120px">Ghi chú</th>
        </tr>
        @foreach ($data['bang'] as $index=>$item)
        <tr>
          <td>{{$index+1}}</td>
          <td>{{$item['tengiayto']}}</td>
          <td>{{$item['hinhthuc']}}</td>
          <td>{{$item['ghichu']}}</td>
        </tr>
        @endforeach

      </table>
      <p>
        Thời gian nhận hồ sơ:  {{$data['tiepnhan_gio']}} giờ  {{$data['tiepnhan_phut']}} phút, ngày  {{$data['tiepnhan_day']}}/ {{$data['tiepnhan_month']}}/ {{$data['tiepnhan_year']}}<br />
        Thời gian trả kết quả giải quyết hồ sơ:  {{$data['tiepnhan_gio']}} giờ  {{$data['ketqua_phut']}} phút, ngày  {{$data['ketqua_day']}}/ {{$data['ketqua_month']}}/ {{$data['ketqua_year']}}
        <br />
        Đăng ký nhận kêt quả tại: {{url('/')}}
      </p>
    </div>
    <div class="d-flex flex-row justify-content-between px-5 mt-1">
      <div class="d-flex flex-column justify-content-end text-center"></div>
      <div class="text-center">
        <div class="fst-italic">Uông Bí, ngày  {{$data['tao_day']}} tháng  {{$data['tao_month']}} năm  {{$data['tao_year']}}</div>
        <div class="fw-bold">NGƯỜI TIẾP NHẬN HỒ SƠ</div>
        <br />
        <img style="height: 50px" src="{{ asset('storage/' . $data['chuky']) }}" alt=""> 
        <br>
        {{$data['giaovien']}}
      </div>
    </div>
  </div>
  @endsection