<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css"
        integrity="sha512-jnSuA4Ss2PkkikSOLtYs8BlYIeeIK1h99ty4YfvRPAlzr377vr3CXDb7sb7eEEBYjDtcYj+AjBH3FLv5uSJuXg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"
        integrity="sha512-7Pi/otdlbbCR+LnW+F7PwFcSDJOuUJB3OxtEHbg4vSMvzvJjde4Po1v4BR9Gdc9aXNUNFVUY+SK51wWT8WF0Gg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"
        integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"
        integrity="sha512-qZvrmS2ekKPF2mSznTQsxqPgnpkI4DNTlrdUmTzrDgektczlKNRRhy5X5AAOnx5S09ydFYWWNSfcEqDTTHgtNA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/fontloader.css') }}">
</head>
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

    @page {
        size: A4;
    }

    body {
        width: 210mm;
        min-height: 297mm;
        padding: 50px 0mm;
        padding-top: 50px;
        margin: 30px auto;
        border: 1px solid #d3d3d3;
        border-radius: 5px;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        box-sizing: border-box;
    }

    .btn-warning {
        display: none !important;
    }

    .A4 {
        width: 180mm;
        min-height: 247mm;
        padding: 0mm;
        margin: auto;
    }

    .d-flex {
        display: flex !important;
    }

    .flex-column {
        flex-direction: column !important;
    }

    .flex-row {
        flex-direction: row !important;
    }

    .justify-content-between {
        justify-content: space-between !important;
    }

    th,td{
        padding: 10px;
    }
    @media print {
        body {
            width: auto;
            min-height: auto;
            padding: 0;
            margin: 0;
            border: none;
            box-shadow: none;
            background: none;
        }

        .A4 {
            box-shadow: none;
            border: none;
            page-break-after: always;
            padding: 0;
            padding-right: 2px;
        }

        .not {
            display: none !important;
        }
    }
</style>
<body>
    <button onclick="toPDF('doc_view');" class="d-flex flex-row gap-2 btn btn-warning fw-bold not me-5 ms-auto">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-file-down">
            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
            <path d="M14 2v4a2 2 0 0 0 2 2h4" />
            <path d="M12 18v-6" />
            <path d="m9 15 3 3 3-3" />
        </svg>
        Xuất PDF
    </button>
    @yield('data')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js"
        integrity="sha512-ykZ1QQr0Jy/4ZkvKuqWn4iF3lqPZyij9iRv6sGqLRdTPkY69YX6+7wvVGmsdBbiIfN/8OdsI7HABjvEok6ZopQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        function toPDF(el) {
            window.print();
        }
    </script>
</body>

</html>
