@extends('layout.doc_layout')

@section('data')
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
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
    <div id="doc_view" class="A4 d-flex flex-column">
        <table style="width: 100%;">
            <tr>
                <td style="text-align: center; width: 40%;">
                    <div>UBND TỈNH QUẢNG NINH</div>
                    <div style="font-weight: bold;">TRƯỜNG ĐẠI HỌC HẠ LONG</div>
                    <div style="width: 94px; height: 1px; background-color: black; margin: 2px auto;"></div>
                    <div class="mt-2">Số: {{ $data[0]['so_QD'] }}/QĐ-ĐHHL</div>
                </td>
                <td style="text-align: center; width: 60%;">
                    <div>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>
                    <div style="font-weight: bold;">Độc lập – Tự do – Hạnh phúc</div>
                    <div style="width: 94px; height: 1px; background-color: black; margin: 2px auto;"></div>
                    <div class="mt-2">Quảng Ninh, ngày {{ $data[0]['thoi_gian_tao_ngay'] }} tháng {{ $data[0]['thoi_gian_tao_thang'] }} năm {{ $data[0]['thoi_gian_tao_nam'] }}</div>
                </td>
            </tr>
        </table>
        
        <div class="text-center fw-bold" style="margin-top: 10px; text-transform: uppercase">
            QUYẾT ĐỊNH
        </div>
        <div class="fw-bold text-center mx-auto">
            Về việc miễn, giảm học phí đối với sinh viên <br>
            diện chính sách, Học kỳ {{ $data[0]['ky'] == '1' ? 'I' : 'II' }} năm học {{ $data[0]['nam'] }}
        </div>
        <div style="
                    width: 150px;
                    height: 1px;
                    background-color: black;
                    margin-top: 2px;
                    margin-left: auto;
                    margin-right: auto;
                    "></div>
        <div class="text-center fw-bold mb-3" style="margin-top: 10px; text-transform: uppercase">
            HIỆU TRƯỞNG TRƯỜNG ĐẠI HỌC HẠ LONG
        </div>

        <div style="text-align: justify;  font-style: italic;">
            <p class="mb-1">
                &emsp;&emsp;&emsp;Căn cứ Pháp lệnh Ưu đãi người có công với cách mạng ngày 09/12/2020;
            </p>
            <p>
                &emsp;&emsp;&emsp;Căn cứ vào Nghị định số 81/2021/NĐ-CP ngày 27 tháng 8 năm 2021 của Chính phủ quy định về cơ chế thu, quản lý học phí đối với cơ sở giáo dục thuộc hệ thống giáo dục quốc dân và chính sách miễn, giảm học phí, hỗ trợ chi phí học tập; giá dịch vụ trong lĩnh vực giáo dục, đào tạo; <br>
            </p>
            <p>
                &emsp;&emsp;&emsp;Căn cứ Quyết định số 2551/QĐ-UBND ngày 12/8/2016 của Uỷ ban nhân dân tỉnh Quảng Ninh về việc Quy định chức năng, nhiệm vụ, quyền hạn và cơ cấu tổ chức của trường Đại học Hạ Long; Quyết định số 416/QĐ-UBND ngày 09/02/2021 của Uỷ ban nhân dân tỉnh Quảng Ninh về việc kiện toàn cơ cấu tổ chức của Trường Đại học Hạ Long; <br>
            </p>
            <p>
                &emsp;&emsp;&emsp;Theo đề nghị của Trưởng phòng Công tác Chính trị, Quản lý và Hỗ trợ sinh viên.
            </p>
        </div>
        <div class="text-center fw-bold" style="margin-top: 15px; text-transform: uppercase">
            QUYẾT ĐỊNH:
        </div>
        <div style="text-align: justify; ">
            <p class="mb-1"><b>&emsp;&emsp;&emsp;Điều 1.</b> Thực hiện chế độ miễn, giảm học phí học kỳ {{ $data[0]['ky'] == '1' ? 'I' : 'II' }} năm học {{ $data[0]['nam'] }}
                đối với {{ $data[0]['tong_hs'] }} sinh viên thuộc diện chính sách, trong đó:</p>
            <p class="mb-1"></p>
            <ul class="mb-1">
                <li>Số sinh viên được miễn 100% học phí: {{ $data[0]['100'] }};</li>
                <li>Số sinh viên được giảm 70% học phí: {{ $data[0]['70'] }};</li>
                <li>Số sinh viên được giảm 50% học phí: {{ $data[0]['50'] }};</li>
                <li>Số tháng được miễn, giảm: 05 tháng.</li>
                <li>Tổng số tiền miễn, giảm: {{ number_format($data[0]['tong'], 0, ',', '.') }}.</li>
                {{-- {{ numberInVietnameseCurrency($data[0]['tong']) }} --}}
            </ul>
        </div>
        <p style="text-align: center;  font-style: italic;">
            (Có danh sách kèm theo)
        </p>
        <div style="text-align: justify; ">
            <p class="mb-1"><b>&emsp;&emsp;&emsp;Điều 2.</b> Trưởng các phòng: Công tác tác chính trị, Quản lý và Hỗ trợ sinh viên; Kế hoạch - Tài chính; các khoa có học sinh, sinh viên được hưởng chế độ và những học sinh, sinh viên có tên tại Điều 1 căn cứ Quyết định thi hành./.
            </p>
        </div>


        <table style="width: 100%; margin-top: 10px;">
            <tr>
                <td style="width: 50%; vertical-align: top; padding-left: 40px; font-size: 13px;">
                    <div class="fw-bold fst-italic">Nơi nhận:</div>
                    <p>
                        - HT và các phó HT (để b/c); <br>
                        - Như Điều 2 (thực hiện); <br>
                        - Lưu VT; CTSV.
                    </p>
                </td>
                <td style="width: 50%; text-align: center; vertical-align: top;">
                    <div class="fw-bold">
                        KT. HIỆU TRƯỞNG
                    </div>
                    <br>
                    @if (!empty($data[0]['canbo_truong']))
                        <br>
                        <img style="height: 50px;" src="{{ asset('storage/' . $data[0]['canbo_truong_chu_ky']) }}" alt="">
                        <br>
                        {{ $data[0]['canbo_truong'] }}
                    @endif
                </td>
            </tr>
        </table>
        
        </p>
    @endsection
