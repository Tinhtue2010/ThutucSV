<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css"
        integrity="sha512-jnSuA4Ss2PkkikSOLtYs8BlYIeeIK1h99ty4YfvRPAlzr377vr3CXDb7sb7eEEBYjDtcYj+AjBH3FLv5uSJuXg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('assets/css/fontloader.css') }}"> --}}
</head>

<style>
    .container {
        display: flex;
        justify-content: center;
        /* Căn giữa theo chiều ngang */
        align-items: center;
        /* Căn giữa theo chiều dọc */
        height: 100vh;
        /* Chiều cao toàn màn hình */
        background-color: #f4f4f4;
    }

    .box {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 300px;
        height: 200px;
        background: white;
        border-radius: 10px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .box h2 {
        margin: 0;
        color: #333;
    }

    .box p {
        color: #666;
        font-size: 16px;
    }


    /* Flexbox */
    .d-flex {
        display: flex;
    }

    .flex-column {
        flex-direction: column;
    }

    .flex-row {
        flex-direction: row;
    }

    .justify-content-center {
        justify-content: center;
    }

    .justify-content-between {
        justify-content: space-between;
    }

    .justify-content-end {
        justify-content: flex-end;
    }

    .align-items-center {
        align-items: center;
    }

    /* Căn giữa văn bản */
    .text-center {
        text-align: center;
    }

    /* Font-weight */
    .fw-bold {
        font-weight: bold;
    }

    /* Font-style */
    .fst-italic {
        font-style: italic;
    }

    /* Khoảng cách giữa các dòng */
    .lh-sm {
        line-height: 1.25;
    }

    /* Khoảng cách padding */
    .px-5 {
        padding-left: 3rem;
        padding-right: 3rem;
    }

    .ps-5 {
        padding-left: 3rem;
    }

    /* Margin */
    .mt-3 {
        margin-top: 1rem;
    }

    .mt-4 {
        margin-top: 1.5rem;
    }

    .mt-5 {
        margin-top: 3rem;
    }
    p{
        margin: 0;
    }

    @page {
        size: A4 portrait;
        /* A4 dọc */
        margin: 20mm 20mm 20mm 25mm;
        /* Lề chuẩn để tránh bị cắt nội dung */
    }
    @font-face {
    font-family: 'MyCustomFont';
    src: url('{{ public_path("fonts/4.ttf") }}') format('truetype');
    font-weight: normal;
    font-style: normal;
}

</style>

<body>
    @yield('data')
</body>

</html>
