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
            <h2>Xác nhận xác nhận chữ ký của bạn</h2>
            <div style="text-align: left">
                <p>Chào bạn,</p>
                <p>Chúng tôi đã nhận được yêu cầu xác nhận chữ ký của bạn cho tài khoản {{$data['email']}}
                    <br>Vui lòng sử dụng mã xác nhận dưới đây để hoàn tất quá trình:
                </p>
            </div>
            <div class="code">{{$data['otp']}}</div>
            <p>Nếu bạn không yêu cầu xác nhận này, vui lòng bỏ qua email này.</p>
        </div>
        <div class="footer">
            <p>Đại học Hạ Long, Địa chỉ: Số 1, Đường Đại học, TP. Hạ Long, Quảng Ninh</p>
            <p>Email: info@halonguni.edu.vn | Điện thoại: (0203) 123 456</p>
        </div>
    </div>
</body>

</html>
