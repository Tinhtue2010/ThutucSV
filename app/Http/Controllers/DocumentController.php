<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;

class DocumentController extends Controller
{
    function index()
    {
        // view('document.thoi_hoc')->render() // đoạn code này lấy ra code html từ blade, 
        // chỉ cần in được pdf là được còn vấn đề truyền dữ liệu ntn tôi sẽ tự làm 
        
        //code này là code pdf laravel mà ko bị lỗi unicode, 
        // nếu trường hợp mà méo chạy được của của m thì chia nhau làm file pdf thôi :)))
        // $pdf = new Mpdf();
        // $pdf->WriteHTML(view('document.thoi_hoc'));
        // $pdf->Output('bao_gia.pdf', 'I');
        
        // @Zord: đã xử lý pdf ngay trên client, đẩy data vào view mà fill :v
        return view('document.index');
    }
}
