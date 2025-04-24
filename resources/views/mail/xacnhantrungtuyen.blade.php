<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mã xác nhận chữ ký - Đại học Hạ Long</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .header {
            text-align: center;
            border-bottom: 1px solid #dddddd;
            padding-bottom: 20px;
        }

        .header img {
            width: 100px;
            height: auto;
        }

        .header h1 {
            font-size: 24px;
            color: #333333;
        }

        .content {
            padding: 20px;
            text-align: center;
        }

        .content h2 {
            font-size: 20px;
            color: #333333;
        }

        .content p {
            font-size: 16px;
            color: #555555;
            line-height: 1.5;
        }

        .code {
            font-size: 24px;
            color: #0000FF;
            font-weight: bold;
            margin: 20px 0;
            display: inline-block;
            padding: 10px 20px;
            border: 1px solid #dddddd;
            background-color: #f9f9f9;
            border-radius: 5px;
        }

        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #dddddd;
            font-size: 14px;
            color: #777777;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <img src="https://ehmugbi.stripocdn.email/content/guids/CABINET_028791cf4ec5985133c5f43b294a60a3454c5d1c65bd05d1bf317024eab320a7/images/logo.png" alt="Logo Đại học Hạ Long">
            <h1>Trường Đại học Hạ Long</h1>
        </div>
        <div class="content">
            <h2>Thông báo trúng tuyển</h2>
            <div style="text-align: left">
                <p>
                    Thân gửi em {{ $data['hoten'] }} <br>
                    Trường Đại học Hạ Long chúc mừng em đã trúng tuyển theo phương thức xét tuyển {{ $data['hinhthuc'] }} năm 2024. <br>
                    Họ và tên thí sinh: {{ $data['otp'] }} <br>
                    Ngày sinh: {{ $data['ngaysinh'] }} <br>
                    Mã hồ sơ: {{ $data['mahoso'] }} <br>
                    Trúng tuyển ngành: {{ $data['nganhtrungtuyen'] }} <br>
                    Trường Đại học Hạ Long vui mừng chào đón em trở thành tân sinh viên của Trường sau khi em nộp Giấy xác nhận kết quả thi tốt nghiệp THPT năm 2024, Giấy chứng nhận tốt nghiệp tạm thời năm 2024 (hoặc bằng tốt nghiệp THPT) và đăng ký nguyện vọng trên Hệ thống hỗ trợ tuyển sinh theo kế hoạch của Bộ Giáo dục và Đào tạo.
                </p>
            </div>
        </div>
        <div class="footer">
            <p>Liên hệ
                <br>Cơ sở 1: Số 258, đường Bạch Đằng, phường Nam Khê - thành phố Uông Bí - tỉnh Quảng Ninh
                <br>Cơ sở 2: Số 58 - đường Nguyễn Văn Cừ - thành phố Hạ Long - tỉnh Quảng Ninh
            </p>
            <p>Email: tonghop@daihochalong.edu.vn | Điện thoại: (84 - 0203).3850304</p>

        </div>
    </div>
</body>

</html>
