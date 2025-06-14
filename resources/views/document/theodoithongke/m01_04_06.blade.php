@extends('layout.doc_layout')

@section('data')
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

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
        <div id="doc_view" class="A4 d-flex flex-column times-font vietnamese-text" style="height: auto;">
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
                <div class="text-center mt-2">Số: {{ $data[0]['so_QD_TA'] }}/QĐ-ĐHHL</div>

            </div>
            <div>
                <div class="text-center">CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>
                <div class="text-center fw-bold">Độc lập – Tự do – Hạnh phúc</div>
                <div style="
                    width: 94px;
                    height: 1px;
                    background-color: black;
                    margin-top: 2px;
                    margin-left: auto;
                    margin-right: auto;
                    "></div>
                <div class="text-center mt-2">Quảng ninh, ngày {{ $data[0]['thoi_gian_tao_ngay'] }} tháng {{ $data[0]['thoi_gian_tao_thang'] }} năm {{ $data[0]['thoi_gian_tao_nam'] }}</div>
            </div>
        </div>
        <div class="text-center fw-bold" style="margin-top: 30px; text-transform: uppercase">
            QUYẾT ĐỊNH
        </div>
        <div class="fw-bold text-center mx-auto">
            Về việc thực hiện chế độ hỗ trợ tiền ăn hàng tháng theo điểm f, g, khoản 3, điều 1, Nghị <br>
            quyết 35/2021/NQ-HĐND ngày 27/08/2021 của Hội đồng nhân dân tỉnh Quảng Ninh <br>
            Học kỳ {{ $data[0]['ky'] == '1' ? 'I' : 'II' }} năm học {{ $data[0]['nam'] }}
        </div>
        <div style="
                    width: 150px;
                    height: 1px;
                    background-color: black;
                    margin-top: 2px;
                    margin-left: auto;
                    margin-right: auto;
                    "></div>
        <div class="text-center fw-bold mb-3" style="margin-top: 30px; text-transform: uppercase">
            HIỆU TRƯỞNG TRƯỜNG ĐẠI HỌC HẠ LONG
        </div>

        <div style="text-align: justify;  font-style: italic;">
            <p class="mb-1">
                &emsp;&emsp;&emsp;Căn cứ Nghị quyết số 35/2021/NQ-HĐND ngày 27/08/2021 của Hội đồng nhân dân tỉnh Quảng Ninh về việc quy định chính sách thu hút, đào tạo nguồn nhân lực chất lượng cao tại Trường Đại học Hạ Long, Trường Cao đẳng Việt – Hàn Quảng Ninh và Trường Cao đẳng Y tế Quảng Ninh giai đoạn 2021 – 2025.
            </p>
            <p>
                &emsp;&emsp;&emsp;Căn cứ Quyết định số 2551/QĐ-UBND ngày 12/8/2016 của UBND tỉnh Quảng Ninh về việc quy định chức năng, nhiệm vụ, quyền hạn và cơ cấu tổ chức của trường Đại học Hạ Long; Quyết định số 416/QĐ-UBND ngày 09/02/2021 của Uỷ ban nhân dân tỉnh Quảng Ninh về việc kiện toàn cơ cấu tổ chức của Trường Đại học Hạ Long; <br>
            </p>
            <p>
                &emsp;&emsp;&emsp; Căn cứ Hồ sơ sinh viên thuộc điểm f, g, Nghị quyết số 35/2021/NQ-HĐND ngày 27/08/2021 của Hội đồng nhân dân tỉnh Quảng Ninh;<br>
            </p>
            <p>
                &emsp;&emsp;&emsp; Theo đề nghị của Trưởng phòng Công tác Chính trị, Quản lý và Hỗ trợ sinh viên.<br>
            </p>
        </div>
        <div class="text-center fw-bold" style="margin-top: 15px; text-transform: uppercase">
            QUYẾT ĐỊNH:
        </div>
        <div style="text-align: justify; ">
            <p class="mb-1"><b>&emsp;&emsp;&emsp;Điều 1.</b> Thực hiện chế độ hỗ trợ tiền ăn theo điểm f, g, khoản 3, điều 1, Nghị quyết 35/2021/NQ-HĐND
                đối với {{ $data[0]['tong_hs_cdta'] }} sinh viên hệ đại học các ngành:
                Quản trị dịch vụ du lịch và lữ hành, Quản trị khách sạn, Quản trị nhà hàng và dịch vụ ăn uống, Ngôn ngữ Nhật, Ngôn ngữ Hàn Quốc, Ngôn ngữ Trung quốc, Nuôi trồng thủy sản như sau:</p>
            <p class="mb-1"></p>
            <ul class="mb-1">
                <li>Mức hỗ trợ tiền ăn hàng tháng: 600.000 đồng/sinh viên/tháng.</li>
                <li>Số tháng được độ hỗ trợ: 05.</li>
                <li>Tổng số tiền hỗ trợ: {{$data[0]['tong_cdta']}}</li>
            </ul>
        </div>
        <p style="text-align: center;  font-style: italic;">
            (Có danh sách kèm theo)
        </p>
        <div style="text-align: justify; ">
            <p class="mb-1"><b>&emsp;&emsp;&emsp;Điều 2.</b> Trưởng các phòng: Công tác tác chính trị, Quản lý và Hỗ trợ sinh viên; Kế hoạch - Tài chính; các khoa có học sinh, sinh viên được hưởng chế độ và những học sinh, sinh viên có tên tại Điều 1 căn cứ Quyết định thi hành./.
            </p>
        </div>


        <div class="d-flex flex-row justify-content-between mt-3" style="">
            <div style="font-size: 13px" class="ms-5">
                <div class="fw-bold fst-italic">Nơi nhận:</div>
                <p>-HT và các phó HT ( để b/c); <br>
                    -Như Điều 2 (thực hiện); <br>
                    -Lưu VT; CTSV.
                </p>
            </div>
            <div>
                <div class="text-center fw-bold">KT. HIỆU TRƯỞNG
                    <br>KT. HIỆU TRƯỞNG
                </div>
                <br>
                <div class="text-center">
                    @if (!empty($data[0]['canbo_truong']))
                        <br>
                        <img style="height: 50px" src="{{ asset('storage/' . $data[0]['canbo_truong_chu_ky']) }}" alt="">
                        <br>
                        {{ $data[0]['canbo_truong'] }}
                    @endif
                </div>
            </div>
        </div>
        </p>
    @endsection
