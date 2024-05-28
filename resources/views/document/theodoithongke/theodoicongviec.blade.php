@extends('layout.doc_layout')

@section('data')
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
        }

        td:nth-child(2) {
            text-align: center;
        }
    </style>
    <div id="doc_view" class="A4 d-flex flex-column">
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
            <div></div>
        </div>
        <div class="text-center fw-bold" style="margin-top: 35px">
            PHIẾU THEO DÕI GIẢI QUYẾT CÔNG VIỆC
        </div>
        <div class="text-center">
            Vấn đề cần giải quyết:
            @if ($type == 0)
                Đơn xin rút hồ sơ
            @elseif ($type == 1)
                Đơn xin miễn giảm học phí
            @elseif ($type == 2)
                Đơn xin trợ cấp xã hội
            @elseif ($type == 3)
                Đơn xin chế độ chính sách
            @endif
        </div>
        <div class="d-flex flex-column mt-3">
            <table class="border-1 border-black border" style="border-collapse: collapse">
                <tr class="text-center p-0">
                    <th class="border border-1 border-black">Ý kiến xử lý công việc</th>
                    <th class="border border-1 border-black">Thời gian, kí tên</th>
                </tr>
                <tr style="height: 160px;">
                    <td class="ps-5 align-top">1. Tiếp nhận hồ sơ
                        <br>
                        {{ $phieu['tiepnhan']->data }}
                    </td>
                    <td>
                        <br>
                        <br>
                        <i>({{ $phieu['tiepnhan']->thoigian }})</i>
                        <br>
                        {{ $phieu['tiepnhan']->hoten }}
                    </td>
                </tr>
                <tr style="height: 160px;">
                    <td class="ps-5 align-top">2. Ý kiến chuyên viên
                        <br>
                        {{ $phieu['ykien']->data }}
                    </td>
                    <td>
                        <br>
                        <br>
                        <i>({{ $phieu['ykien']->thoigian }})</i>
                        <br>
                        {{ $phieu['ykien']->hoten }}
                    </td>
                </tr>
                <tr style="height: 160px;">
                    <td class="ps-5 align-top">3. Ý kiến lãnh đạo phòng
                        <br>
                        {{ $phieu['lanhdaophong']->data }}
                    </td>
                    <td>
                        <br>
                        <br>
                        <i>({{ $phieu['lanhdaophong']->thoigian }})</i>
                        <br>
                        {{ $phieu['lanhdaophong']->hoten }}
                    </td>
                </tr>
                <tr style="height: 160px;">
                    <td class="ps-5 align-top">4. Ý kiến lãnh đạo Trường
                        <br>
                        {{ $phieu['lanhdaotruong']->data }}
                    </td>
                    <td>
                        <br>
                        <br>
                        <i>({{ $phieu['lanhdaotruong']->thoigian }})</i>
                        <br>
                        {{ $phieu['lanhdaotruong']->hoten }}
                    </td>
                </tr>
            </table>
        </div>
        <div class="d-flex flex-row justify-content-between px-5 mt-1">
        </div>
    </div>
@endsection
