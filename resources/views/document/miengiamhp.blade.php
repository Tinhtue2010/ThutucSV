@extends('layout.doc_layout')

@section('data')
<div id="doc_view" class="A4 d-flex flex-column">
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
      ĐƠN ĐỀ NGHỊ MIỄN, GIẢM HỌC PHÍ
      <div class="lh-1 fw-medium fst-italic">
        (Dùng cho học sinh, sinh viên đang học tại các cơ sở
        <div>giáo dục nghề nghiệp và giáo dục đại học công lập)</div>
      </div>
    </div>
    <div
      style="margin-top: 15px"
      class="d-flex flex-row lh-sm justify-content-center"
    >
      <div>Kính gửi:</div>
      <div class="d-flex flex-column" style="margin-left: 10px">
        <p>Trường Đại học Hạ Long<br /></p>
      </div>
    </div>
    <div class="d-flex flex-column">
      <div>
        Họ và tên: {{$data['full_name']}} &nbsp;&nbsp; SĐT: {{$data['sdt']}}
        <br />
        Ngày, tháng, năm sinh: {{$data['date_of_birth']}}
        <br />
        Nơi sinh: {{$data['noisinh']}}
        <br />
        Lớp: {{$data['student_code']}} &nbsp; Khóa: {{$data['khoa_hoc']}}; &nbsp; Khoa: {{$data['khoa']}}
        <br />
        Thuộc đối tượng:
        <span class="fst-italic"
          >(ghi rõ đối tượng được quy định tại Nghị định số 81/2021/NĐ-CP)</span
        >
        <div class="ms-3">
          {{$data['doituong']}}
        </div>
        <br />
        Đã được hưởng chế độ miễn, giảm học phí (ghi rõ tên cơ sở đã được hưởng
        chế độ miễn giảm học phí, cấp học và trình độ đào tạo):
        <div class="ms-3">
          {{$data['daduochuong']}}
        </div>
        <br />
        Căn cứ vào Nghị định số 81/2021/NĐ-CP của Chính phủ, tôi làm đơn này đề nghị được Nhà trường xem xét để được miễn, giảm học phí theo quy định hiện hành.
      </div>
    </div>
    <div class="d-flex flex-row justify-content-end mt-2">
      <div class="fst-italic">Quảng Ninh, ngày {{$data['day']}} tháng {{$data['month']}} năm {{$data['year']}}</div>
    </div>
    <div class="d-flex flex-row justify-content-end px-5 mt-3">
      <div class="text-center">
        NGƯỜI VIẾT ĐƠN
        <br />
        ( Ký và ghi rõ họ và tên)
        <br>
        <img style="height: 50px" src="{{ asset('storage/'.$data['url_chuky']) }}" alt="">
        <br>
      {{$data['full_name']}}
      </div>
    </div>
  </div>
  @endsection