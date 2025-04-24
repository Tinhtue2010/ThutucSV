@push('js')
    <script>
        function doituong1() {
            Swal.fire({
                title: "Thông tin",
                html: `
                        <div style="text-align: left; line-height: 1.5;">
                            <b>Đối tượng 1:</b> <br/> Đối tượng được hưởng chế độ hỗ trợ tiền ăn, hỗ trợ học phí và hỗ trợ chỗ ở phải là sinh viên có hộ khẩu thường trú tại tỉnh Quảng Ninh thuộc diện:
                            <ul>
                                <li>Sinh viên có gia cảnh thuộc hộ nghèo</li>
                                <li>Sinh viên có gia cảnh thuộc hộ cận nghèo</li>
                                <li>Sinh viên có gia cảnh thuộc các xã khu vực I vùng đồng bào dân tộc thiểu số theo quy định của Thủ tướng Chính phủ</li>
                                <li>Sinh viên tốt nghiệp các trường phổ thông dân tộc nội trú trên địa bàn tỉnh Quảng Ninh</li>
                                <li>Sinh viên đã hoàn thành nghĩa vụ quân sự, nghĩa vụ công an</li>
                            </ul>
                             </div>
                    `,
                width: '800px',
                maxWidth: '95%',
            });
        }

        function doituong2() {
            Swal.fire({
                title: "Thông tin",
                html: `
                        <div style="text-align: left; line-height: 1.5;">
                            <b>Đối tượng 2:</b> <br/>Đối tượng được hưởng chế độ hỗ trợ tiền ăn là những sinh viên thuộc diện miễn giảm học phí theo Nghị định 81/2021/NĐ-CP ngày 27/8/2021 của Chính phủ về cơ chế thu, quản lý học phí đối với cơ sở giáo dục thuộc hệ thống giáo dục quốc dân và chính sách miễn, giảm học phí, hỗ trợ chi phí học tập (tức là những sinh viên đã được xét trong quy trình 3).
                            Chế độ miễn giảm học phí (Nghị định 81) ở quy trình 3 được xét từ đầu kỳ học. Chế độ thuộc Nghị định 35 ở quy trình 4 được xét cuối kỳ học.</div>
                    `,
                width: '800px',
                maxWidth: '95%',
            });
        }

        function doituong3() {
            Swal.fire({
                title: "Thông tin",
                html: `
                        <div style="text-align: left; line-height: 1.5;">
                            <b>Đối tượng 3:</b> <br/> Đối tượng được hưởng chế độ hỗ trợ học phí là sinh viên một trong 7 ngành quy định trên và chưa được hưởng chế độ miễn, giảm học phí nào (hoặc sinh viên thuộc diện được hưởng chế độ miễn, giảm nhưng không phải diện miễn giảm 100% học phí theo Nghị định 81/2021/NĐ-CP ngày 27/8/2021). Điều kiện cần thiết để sinh viên đối tượng 3 được hưởng chế độ hỗ trợ học phí này là phải thuộc 20% số lượng sinh viên có điểm trung bình chung học tập và rèn luyện của mỗi kỳ đạt từ Khá trở lên (thang 10, điểm trung bình chung học tập lớn hơn hoặc bằng 7.0 và điểm rèn luyện phải từ 65 điểm trở lên) trên số lượng sinh viên từng ngành, từng khóa học.
                            Nếu sinh viên đã được hưởng miễn giảm học phí theo Nghị định 81 nhưng không phải diện miễn giảm 100% học phí, thì tiếp tục được miễn giảm số học phí còn lại.
                        </div>
                    `,
                width: '800px',
                maxWidth: '95%',
            });
        }

        function doituong4() {
            Swal.fire({
                title: "Thông tin",
                html: `
                        <div style="text-align: left; line-height: 1.5;">
                            <b>Đối tượng 4:</b> <br/>Đối tượng được hưởng chế độ hỗ trợ chỗ ở là sinh viên một trong 7 ngành quy định trên có điểm trung bình chung học tập, điểm rèn luyện trong học kỳ xếp loại từ Khá trở lên (thang 10, điểm trung bình chung học tập lớn hơn hoặc bằng 7.0 và điểm rèn luyện từ 65 điểm trở lên) và khoảng cách từ trường đến nhà từ 15 km trở lên.
                        </div>
                    `,
                width: '800px',
                maxWidth: '95%',
            });
        }
    </script>
@endpush
