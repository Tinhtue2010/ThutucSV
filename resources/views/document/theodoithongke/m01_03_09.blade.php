@extends('layout.doc_layout')

@section('data')
    <style>
        ul {
            list-style-type: none;
            /* Loại bỏ dấu chấm mặc định */
            padding-left: 80px;
        }

        ul li::before {
            content: "- ";
            /* Thêm dấu gạch ngang trước mỗi mục */
        }
    </style>
    <div id="doc_view" class="A4 d-flex flex-column">
        <table style="width: 100%;">
            <tr>
                <td style="text-align: center; vertical-align: top;">
                    <div>UBND TỈNH QUẢNG NINH</div>
                    <div style="font-weight: bold;">TRƯỜNG ĐẠI HỌC HẠ LONG</div>
                    <div style="width: 94px; height: 1px; background-color: black; margin: 2px auto 0 auto;"></div>
                    <div style="margin-top: 8px;">Số: {{ $data[0]['so_QD'] }}/QĐ-ĐHHL</div>
                </td>
                <td style="text-align: center; vertical-align: top;">
                    <div>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>
                    <div style="font-weight: bold;">Độc lập – Tự do – Hạnh phúc</div>
                    <div style="width: 94px; height: 1px; background-color: black; margin: 2px auto 0 auto;"></div>
                    <div style="margin-top: 8px;">
                        Quảng Ninh, ngày {{ $data[0]['thoi_gian_tao_ngay'] }} tháng {{ $data[0]['thoi_gian_tao_thang'] }} năm {{ $data[0]['thoi_gian_tao_nam'] }}
                    </div>
                </td>
            </tr>
        </table>

        <div class="text-center fw-bold" style="margin-top: 10px; text-transform: uppercase">
            QUYẾT ĐỊNH
        </div>
        <div class="fw-bold text-center mx-auto">
            Về việc thực hiện chính sách hỗ trợ chi phí học tập đối với sinh viên là người dân tộc <br>
            thiểu số thuộc hộ nghèo và cận nghèo. Học kỳ {{ $data[0]['ky'] == '1' ? 'I' : 'II' }} năm học
            {{ $data[0]['nam'] }}
        </div>
        <div
            style="
                    width: 150px;
                    height: 1px;
                    background-color: black;
                    margin-top: 2px;
                    margin-left: auto;
                    margin-right: auto;
                    ">
        </div>
        <div class="text-center fw-bold mb-2" style="margin-top: 10px; text-transform: uppercase">
            HIỆU TRƯỞNG TRƯỜNG ĐẠI HỌC HẠ LONG
        </div>

        <div style="text-align: justify;  font-style: italic;">
            <p class="mb-1">
                &emsp;&emsp;&emsp; Căn cứ Thông tư liên tịch số 35/2014/TTLT-BGDĐT-BTC ngày 15 tháng 10 năm 2014 của Liên bộ
                Giáo dục và đào tạo-Bộ Tài chính; Hướng dẫn thực hiện Quyết định số 66/2013/QĐ-TTg ngày 11 tháng 11 năm 2013
                của Thủ tướng chính phủ Quy định chính sách hỗ trợ chi phí học tập đối với sinh viên là người dân tộc thiểu
                số học tại các cơ sở giáo dục đại học;
            </p>
            <p>
                &emsp;&emsp;&emsp; Căn cứ Quyết định số 2551/QĐ-UBND ngày 12/8/2016 của UBND tỉnh Quảng Ninh về việc quy
                định chức năng, nhiệm vụ, quyền hạn và cơ cấu tổ chức của trường Đại học Hạ Long; Quyết định số 416/QĐ-UBND
                ngày 09/02/2021 của Ủy ban dân tỉnh Quảng Ninh về việc kiện toàn cơ cấu tổ chức của trường Đại học Hạ Long;
            </p>
            <p>
                &emsp;&emsp;&emsp;Theo đề nghị của Trưởng phòng Công tác Chính trị, Quản lý và Hỗ trợ sinh viên.
            </p>
        </div>
        <div class="text-center fw-bold" style="margin-top: 5px; text-transform: uppercase">
            QUYẾT ĐỊNH:
        </div>
        <div style="text-align: justify; ">
            <p class="mb-1"><b>&emsp;&emsp;&emsp;Điều 1.</b> Thực hiện chính sách hỗ trợ chi phí học tập đối với sinh viên
                là người dân tộc thiểu số thuộc hộ nghèo và cận nghèo học kì
                {{ $data[0]['tong_hs'] }} sinh viên.</p>
            <p class="mb-1"></p>
            <ul class="mb-1">
                <li>Mức hỗ trợ/sinh viên/tháng: 894.000 đồng (bằng 60% mức lương cơ sở)</li>
                <li>Số tháng được hưởng: 05 tháng.</li>
                <li>Tổng số tiền hỗ trợ: {{ $data[0]['tong'] }}.</li>
            </ul>
        </div>
        <p style="text-align: center;  font-style: italic;">
            (Có danh sách kèm theo)
        </p>
        <div style="text-align: justify; ">
            <p class="mb-1"><b>&emsp;&emsp;&emsp;Điều 2.</b> Trưởng các phòng: Công tác tác chính trị, Quản lý và Hỗ trợ
                sinh viên; Kế hoạch - Tài chính; các khoa có học sinh, sinh viên được hưởng chế độ và những học sinh, sinh
                viên có tên tại Điều 1 căn cứ Quyết định thi hành./.
            </p>
        </div>


        <table style="width: 100%; margin-top: 20px; font-size: 13px; border-collapse: collapse;">
            <tr>
                <td style="vertical-align: top; width: 60%; padding-left: 30px;">
                    <div class="fw-bold fst-italic">Nơi nhận:</div>
                    <p style="margin: 0;">- HT và các phó HT (để b/c);<br>
                        - Như Điều 2 (thực hiện);<br>
                        - Lưu VT; CTSV.
                    </p>
                </td>
                <td style="text-align: center;">
                    <div class="fw-bold">KT. HIỆU TRƯỞNG<br>HIỆU TRƯỞNG</div>
                    @if (!empty($data[0]['canbo_truong']))
                        <div style="margin: 5px 0;">
                            <img style="height: 50px" src="{{ asset('storage/' . $data[0]['canbo_truong_chu_ky']) }}"
                                alt="">
                        </div>
                        <div>{{ $data[0]['canbo_truong'] }}</div>
                    @endif
                </td>
            </tr>
        </table>
    @endsection
