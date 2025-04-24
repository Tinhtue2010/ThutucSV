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
    <link rel="stylesheet" href="css/fontloader.css">
</head>

<body>
    @include('document.thoi_hoc')
    <button onclick="toPDF();" class="d-flex flex-row gap-2 btn btn-warning fw-bold">
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js"
        integrity="sha512-ykZ1QQr0Jy/4ZkvKuqWn4iF3lqPZyij9iRv6sGqLRdTPkY69YX6+7wvVGmsdBbiIfN/8OdsI7HABjvEok6ZopQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        function toPDF(el) {
            doc = new jspdf.jsPDF('p', 'mm', [297, 210]);
            // const font = doc.getFontList()[0]; // Assuming the first font is the default
            doc.addFont('font/times.ttf', 'times', 'normal');
            doc.addFont('font/SVN-Times New Roman Bold.ttf', 'times', 'bold');
            doc.addFont('font/SVN-Times New Roman Italic.ttf', 'times', 'italic');
            doc.setFont('times');
            doc.html(document.getElementById(el), {
                callback: function(pdf) {
                    pdf.save("output.pdf");
                },
                x: 0,
                y: 0,
                width: 170, // set the width of the pdf to 170 mm (A4 width)
                height: 287, // set the height of the pdf to 287 mm (A4 height)
                windowWidth: 650, // set the width of the html content to 650 pixels
                windowHeight: 900,
                fontFaces: [
                    { family: 'times', style: 'normal', file: 'font/times.ttf' }, // Default font
                    { family: 'times', style: 'bold', file: 'font/SVN-Times New Roman Bold.ttf' }, // Bold font
                    { family: 'times', style: 'italic', file: 'font/SVN-Times New Roman Italic.ttf' }, // Italic font
                ],
            });
        }
    </script>
</body>

</html>
