<?php

return [
    "miengiamhp" => [
        [ //0
            "100",
            "Người có công với cách mạng",
            'Người có công với cách mạng và thân nhân của người có công với cách mạng theo Pháp lệnh số
        02/2020/UBTVQH14 về ưu đãi người có công với cách mạng: Thân nhân của người có công với cách mạng bao gồm: Cha đẻ, 
        mẹ đẻ, vợ hoặc chồng, con (con đẻ, con nuôi), người có công nuôi liệt sĩ.'
        ],
        [ //1
            "100",
            "Sinh viên bị khuyết tật",
            "Sinh viên bị khuyết tật"
        ],
        [ //2
            "100",
            "Mồ côi cha hoặc mẹ,...",
            "Học sinh, sinh viên mồ côi cả cha lẫn mẹ; mồ côi cha hoặc mẹ, người còn lại rơi vào hoàn cảnh đặc biệt, cha mẹ mất tích, … thời điểm 
        mồ côi dưới 16 tuổi (Quy định tại Khoản 1 và Khoản 2 Điều 5, Nghị định 20/2021/NĐ-CP)."
        ],
        [ //3 auto xoa
            "100",
            "Dân tộc thiểu số thuộc hộ nghèo, cận nghèo",
            "Học sinh, sinh viên học tại các cơ sở giáo dục nghề nghiệp và giáo dục đại học là người dân tộc thiểu số thuộc hộ nghèo và hộ cận 
        nghèo theo quy định của Thủ tướng Chính phủ (Sinh viên có số hộ nghèo và cận nghèo)."
        ],
        [ // 4
            "100",
            "Dân thộc thiểu số rất ít người",
            "Học sinh, sinh viên người dân tộc thiểu số rất ít người (La hủ, La ha, Pà thẻn, Lự, Ngái, Chứt, Lô lô, Mảng, Cống, Cờ lao, Bố y, Si la, 
        Pu péo, Rơ măm, Brâu, Ơ đu) ở vùng có điều kiện kinh tế – xã hội khó khăn và đặc biệt khó khăn"
        ],

        [ //5
            "70",
            "chuyên ngành Múa, nhạc cụ truyền thống",
            "Học sinh, sinh viên các chuyên ngành Múa, Biểu diễn nhạc cụ truyền thống"
        ],
        [ //6
            "70",
            "Dân tộc thiểu số không phải là ít người",
            "Học sinh, sinh viên là người dân tộc thiểu số (không phải là dân tộc thiểu số rất ít người) ở thôn, bản đặc biệt khó khăn, xã khu vực 
        III vùng dân tộc miền núi, xã đặc biệt khó khăn vùng bãi ngang ven biển hải đảo theo quy định của cơ quan có thẩm quyền (Quy định tại QĐ 
        433/QĐ-UBMT ngày 18/6/2021; QĐ số 861/QĐ-TTg ngày 04/6/2021; 353/QĐ-TTg ngày 15/3/2022)"
        ],

        [ //7
            "50",
            "Cha hoặc mẹ là cán bộ, công nhân viên chức bị tai nạn lao động",
            "Học sinh, sinh viên là con cán bộ, công nhân, viên chức mà cha hoặc mẹ bị tai nạn lao động hoặc mắc bệnh nghề nghiệp được hưởng 
        trợ cấp thường xuyên (Có QĐ và Giấy chứng nhận trợ cấp TNLĐ-BNN của Bảo hiểm xã hội cấp)"
        ],
    ],
    "statusmiengiamhp" => [
        ["0", "Sinh viên gửi hồ sơ"],
        ["-1", "Phòng CTSV yêu cầu bổ sung hồ sơ"],
        ["1", "Phòng CTSV đã tiếp nhận đơn"],
        ["2", "Chờ lãnh đạo phòng CTSV xác nhận"],
        ["-2", "Phòng CTSV từ chối"],
        ["3", "Lãnh đạo phòng CTSV đã xác nhận"],
        ["-3", "Lãnh đạo phòng CTSV từ chối danh sách"],
        ["4", "Đã gửi thông báo đến khoa"],
        ["5", "Phòng KHTC đã xác nhận"],
        ["-5", "Phòng KHTC từ chối danh sách"],
        ["6", "Lãnh đạo trường đã xác nhận"],
        ["-6", "Lãnh đạo trường từ chối DS"]
    ],
    "muctrocaphp" => 894000,
    "trocapxahoi" => [
        [
            "140000",
            "Dân tộc thiểu số vùng cao 3 năm trở lên",
            "Học sinh, sinh viên là người dân tộc thiểu số ở vùng cao từ 03 năm trở lên."
        ],
        [
            "100000",
            "Mồ côi cha mẹ không nơi nương tựa",
            "Học sinh, sinh viên mồ côi cả cha lẫn mẹ không nơi nương tựa."
        ],
        [
            "100000",
            "Là người tàn tật khó khăn về kinh tế",
            "Học sinh, sinh viên là người tàn tật gặp khó khăn về kinh tế."
        ],
        [
            "100000",
            "Học sinh sinh viên hoàn cảnh đặc biệt khó khăn",
            "Học sinh, sinh viên có hoàn cảnh đặc biệt khó khăn về kinh tế, vượt khó học tập, gia đình thuộc diện xóa đói giảm nghèo."
        ]
    ],
    "chedochinhsach" => [
        "1" => [
            "Đối tượng được hưởng chế độ hỗ trợ tiền ăn, học phí, chỗ ở",
            "Đối tượng được hưởng chế độ hỗ trợ tiền ăn, hỗ trợ học phí và hỗ trợ chỗ ở là sinh viên có hộ khẩu thường trú lại tỉnh Quảng Ninh"
        ],
        "2" => [
            "Đối tượng sv 7 ngành, điểm > 7.0, rèn luyện > 65, nhà cách trường ít nhất 15km",
            "Đối tượng được hưởng chế độ hỗ trợ chỗ ở là sinh viên một trong 7 ngành quy định trên có điểm trung bình chung học tập, điểm rèn luyện trong học kỳ xếp loại từ Khá trở lên (thang 10, điểm trung bình chung học tập lớn hơn hoặc bằng 7.0 và điểm rèn luyện từ 65 điểm trở lên) và khoảng cách từ trường đến nhà từ 15 km trở lên."
        ],
    ]
];
