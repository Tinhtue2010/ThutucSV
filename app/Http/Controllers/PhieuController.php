<?php

namespace App\Http\Controllers;

use App\Models\Phieu;
use App\Models\StopStudy;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use TCPDF;
use TCPDF_FONTS;

class PhieuController extends Controller
{
    function index($id = null)
    {
        if ($id == null) {
            abort(404);
        }
        $phieu = Phieu::find($id);
        if ($id == "DSMGHP0") {
            $phieu = Phieu::where('key', 'DSMGHP')->where('status', 0)->first();
        }
        if ($id == "DSMGHP1") {
            $phieu = Phieu::where('key', 'DSMGHP')->where('status', 1)->first();
        }
        if ($id == "QDGHP0") {
            $phieu = Phieu::where('key', 'QDGHP')->where('status', 0)->first();
        }
        if ($id == "QDGHP1") {
            $phieu = Phieu::where('key', 'QDGHP')->where('status', 1)->first();
        }
        if ($id == "PTGHP0") {
            $phieu = Phieu::where('key', 'PTGHP')->where('status', 0)->first();
        }
        if ($id == "PTGHP1") {
            $phieu = Phieu::where('key', 'PTGHP')->where('status', 1)->first();
        }

        if ($id == "DSTCXH0") {
            $phieu = Phieu::where('key', 'DSTCXH')->where('status', 0)->first();
        }

        if ($id == "QDTCXH0") {
            $phieu = Phieu::where('key', 'QDTCXH')->where('status', 0)->first();
        }
        if ($id == "PTTCXH0") {
            $phieu = Phieu::where('key', 'PTTCXH')->where('status', 0)->first();
        }

        if ($id == "DSTCHP0") {
            $phieu = Phieu::where('key', 'DSTCHP')->where('status', 0)->first();
        }

        if ($id == "QDTCHP0") {
            $phieu = Phieu::where('key', 'QDTCHP')->where('status', 0)->first();
        }
        if ($id == "PTTCHP0") {
            $phieu = Phieu::where('key', 'PTTCHP')->where('status', 0)->first();
        }


        if ($id == "DSCDTA0") {
            $phieu = Phieu::where('key', 'DSCDTA')->where('status', 0)->first();
        }
        if ($id == "QDCDTA0") {
            $phieu = Phieu::where('key', 'QDCDTA')->where('status', 0)->first();
        }

        if ($id == "DSCDHP0") {
            $phieu = Phieu::where('key', 'DSCDHP')->where('status', 0)->first();
        }
        if ($id == "QDCDHP0") {
            $phieu = Phieu::where('key', 'QDCDHP')->where('status', 0)->first();
        }


        if ($id == "DSCDKTX10") {
            $phieu = Phieu::where('key', 'DSCDKTX1')->where('status', 0)->first();
        }
        if ($id == "DSCDKTX40") {
            $phieu = Phieu::where('key', 'DSCDKTX4')->where('status', 0)->first();
        }

        if ($id == "QDCDKTX10") {
            $phieu = Phieu::where('key', 'QDCDKTX1')->where('status', 0)->first();
        }
        if ($id == "QDCDKTX40") {
            $phieu = Phieu::where('key', 'QDCDKTX4')->where('status', 0)->first();
        }

        if ($id == "PTQT40") {
            $phieu = Phieu::where('key', 'PTQT4')->where('status', 0)->first();
        }


        if (!$phieu) {
            abort(404);
        }
        if (Role(1)) {
            if ($phieu->student_id != Auth::user()->student_id && $phieu->key != "DSMGHP") {
                abort(404);
            }
        }

        if ($phieu->key == "RHS") {

            $options = new Options();
            $options->set('defaultFont', 'DejaVu Sans');
            $dompdf = new Dompdf($options);
            
            $html = view('document.thoi_hoc', ['data' => json_decode($phieu->content, true)])->render();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            
            // Lưu vào bộ nhớ tạm thời
            $filePath = 'output.pdf';
            file_put_contents($filePath, $dompdf->output());
            
            // Đọc file PDF và chuyển thành Base64
            $base64Pdf = base64_encode(file_get_contents($filePath));
            
            // Xuất Base64
            echo $base64Pdf;

            return ;


            return view('document.thoi_hoc', ['data' => json_decode($phieu->content, true)]);
        }
        if ($phieu->key == "GHP") {
            return view('document.miengiamhp', ['data' => json_decode($phieu->content, true)]);
        }
        if ($phieu->key == "TCXH") {
            return view('document.trocapxahoi', ['data' => json_decode($phieu->content, true)]);
        }
        if ($phieu->key == "TCHP") {
            return view('document.hotro_cpht', ['data' => json_decode($phieu->content, true)]);
        }
        if ($phieu->key == "CDCS") {
            return view('document.chinhsach_qn', ['data' => json_decode($phieu->content, true)]);
        }
        if ($phieu->key == "HDBSSH") {
            return view('document.huongdanbosung', ['data' => json_decode($phieu->content, true)]);
        }
        if ($phieu->key == "TCGQ") {
            return view('document.tuchoi', ['data' => json_decode($phieu->content, true)]);
        }
        if ($phieu->key == "TNHS") {
            return view('document.tiepnhan', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if ($phieu->key == "DSMGHP") {
            return view('document.theodoithongke.m01_02_05', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if ($phieu->key == "QDGHP") {
            return view('document.theodoithongke.m01_02_06', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if ($phieu->key == "PTGHP") {
            return view('document.theodoithongke.m01_02_07', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if ($phieu->key == "DSTCXH") {
            return view('document.theodoithongke.m01_03_06', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if ($phieu->key == "QDTCXH") {
            return view('document.theodoithongke.m01_03_07', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if ($phieu->key == "PTTCXH") {
            return view('document.theodoithongke.m01_03_10', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }

        if ($phieu->key == "DSTCHP") {
            return view('document.theodoithongke.m01_03_08', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if ($phieu->key == "QDTCHP") {
            return view('document.theodoithongke.m01_03_09', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if ($phieu->key == "PTTCHP") {
            return view('document.theodoithongke.m01_03_10_2', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if ($phieu->key == "DSCDTA") {
            return view('document.theodoithongke.m01_04_05', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if ($phieu->key == "QDCDTA") {
            return view('document.theodoithongke.m01_04_06', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }


        if ($phieu->key == "DSCDHP") {
            return view('document.theodoithongke.m01_04_07', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if ($phieu->key == "QDCDHP") {
            return view('document.theodoithongke.m01_04_08', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }


        if ($phieu->key == "DSCDKTX1") {
            return view('document.theodoithongke.m01_04_09', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if ($phieu->key == "DSCDKTX4") {
            return view('document.theodoithongke.m01_04_10', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }

        if ($phieu->key == "QDCDKTX1") {
            return view('document.theodoithongke.m01_04_11', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if ($phieu->key == "QDCDKTX4") {
            return view('document.theodoithongke.m01_04_12', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }

        if ($phieu->key == "PTQT4") {
            return view('document.theodoithongke.m01_04_13', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }

        return abort(404);
    }

    function indexa($id = null)
    {
        if ($id == null) {
            abort(404);
        }
        $phieu = Phieu::find($id);
        if ($id == "DSMGHP0") {
            $phieu = Phieu::where('key', 'DSMGHP')->where('status', 0)->first();
        }
        if ($id == "DSMGHP1") {
            $phieu = Phieu::where('key', 'DSMGHP')->where('status', 1)->first();
        }
        if ($id == "QDGHP0") {
            $phieu = Phieu::where('key', 'QDGHP')->where('status', 0)->first();
        }
        if ($id == "QDGHP1") {
            $phieu = Phieu::where('key', 'QDGHP')->where('status', 1)->first();
        }
        if ($id == "PTGHP0") {
            $phieu = Phieu::where('key', 'PTGHP')->where('status', 0)->first();
        }
        if ($id == "PTGHP1") {
            $phieu = Phieu::where('key', 'PTGHP')->where('status', 1)->first();
        }

        if ($id == "DSTCXH0") {
            $phieu = Phieu::where('key', 'DSTCXH')->where('status', 0)->first();
        }

        if ($id == "QDTCXH0") {
            $phieu = Phieu::where('key', 'QDTCXH')->where('status', 0)->first();
        }
        if ($id == "PTTCXH0") {
            $phieu = Phieu::where('key', 'PTTCXH')->where('status', 0)->first();
        }

        if ($id == "DSTCHP0") {
            $phieu = Phieu::where('key', 'DSTCHP')->where('status', 0)->first();
        }

        if ($id == "QDTCHP0") {
            $phieu = Phieu::where('key', 'QDTCHP')->where('status', 0)->first();
        }
        if ($id == "PTTCHP0") {
            $phieu = Phieu::where('key', 'PTTCHP')->where('status', 0)->first();
        }


        if ($id == "DSCDTA0") {
            $phieu = Phieu::where('key', 'DSCDTA')->where('status', 0)->first();
        }
        if ($id == "QDCDTA0") {
            $phieu = Phieu::where('key', 'QDCDTA')->where('status', 0)->first();
        }

        if ($id == "DSCDHP0") {
            $phieu = Phieu::where('key', 'DSCDHP')->where('status', 0)->first();
        }
        if ($id == "QDCDHP0") {
            $phieu = Phieu::where('key', 'QDCDHP')->where('status', 0)->first();
        }


        if ($id == "DSCDKTX10") {
            $phieu = Phieu::where('key', 'DSCDKTX1')->where('status', 0)->first();
        }
        if ($id == "DSCDKTX40") {
            $phieu = Phieu::where('key', 'DSCDKTX4')->where('status', 0)->first();
        }

        if ($id == "QDCDKTX10") {
            $phieu = Phieu::where('key', 'QDCDKTX1')->where('status', 0)->first();
        }
        if ($id == "QDCDKTX40") {
            $phieu = Phieu::where('key', 'QDCDKTX4')->where('status', 0)->first();
        }

        if ($id == "PTQT40") {
            $phieu = Phieu::where('key', 'PTQT4')->where('status', 0)->first();
        }


        if (!$phieu) {
            abort(404);
        }
        if (Role(1)) {
            if ($phieu->student_id != Auth::user()->student_id && $phieu->key != "DSMGHP") {
                abort(404);
            }
        }

        if ($phieu->key == "RHS") {

            return view('document.thoi_hoc', ['data' => json_decode($phieu->content, true)]);
        }
        if ($phieu->key == "GHP") {
            return view('document.miengiamhp', ['data' => json_decode($phieu->content, true)]);
        }
        if ($phieu->key == "TCXH") {
            return view('document.trocapxahoi', ['data' => json_decode($phieu->content, true)]);
        }
        if ($phieu->key == "TCHP") {
            return view('document.hotro_cpht', ['data' => json_decode($phieu->content, true)]);
        }
        if ($phieu->key == "CDCS") {
            return view('document.chinhsach_qn', ['data' => json_decode($phieu->content, true)]);
        }
        if ($phieu->key == "HDBSSH") {
            return view('document.huongdanbosung', ['data' => json_decode($phieu->content, true)]);
        }
        if ($phieu->key == "TCGQ") {
            return view('document.tuchoi', ['data' => json_decode($phieu->content, true)]);
        }
        if ($phieu->key == "TNHS") {
            return view('document.tiepnhan', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if ($phieu->key == "DSMGHP") {
            return view('document.theodoithongke.m01_02_05', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if ($phieu->key == "QDGHP") {
            return view('document.theodoithongke.m01_02_06', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if ($phieu->key == "PTGHP") {
            return view('document.theodoithongke.m01_02_07', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if ($phieu->key == "DSTCXH") {
            return view('document.theodoithongke.m01_03_06', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if ($phieu->key == "QDTCXH") {
            return view('document.theodoithongke.m01_03_07', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if ($phieu->key == "PTTCXH") {
            return view('document.theodoithongke.m01_03_10', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }

        if ($phieu->key == "DSTCHP") {
            return view('document.theodoithongke.m01_03_08', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if ($phieu->key == "QDTCHP") {
            return view('document.theodoithongke.m01_03_09', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if ($phieu->key == "PTTCHP") {
            return view('document.theodoithongke.m01_03_10_2', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if ($phieu->key == "DSCDTA") {
            return view('document.theodoithongke.m01_04_05', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if ($phieu->key == "QDCDTA") {
            return view('document.theodoithongke.m01_04_06', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }


        if ($phieu->key == "DSCDHP") {
            return view('document.theodoithongke.m01_04_07', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if ($phieu->key == "QDCDHP") {
            return view('document.theodoithongke.m01_04_08', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }


        if ($phieu->key == "DSCDKTX1") {
            return view('document.theodoithongke.m01_04_09', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if ($phieu->key == "DSCDKTX4") {
            return view('document.theodoithongke.m01_04_10', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }

        if ($phieu->key == "QDCDKTX1") {
            return view('document.theodoithongke.m01_04_11', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if ($phieu->key == "QDCDKTX4") {
            return view('document.theodoithongke.m01_04_12', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }

        if ($phieu->key == "PTQT4") {
            return view('document.theodoithongke.m01_04_13', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }

        return abort(404);
    }
    function getData($id = null)
    {
        if ($id == null) {
            abort(404);
        }
        $phieu = Phieu::find($id);
        if (!$phieu) {
            abort(404);
        }
        if (Role(1)) {
            if ($phieu->student_id != Auth::user()->student_id) {
                abort(404);
            }
        }
        return json_decode($phieu->content);
    }

    function giaQuyetCongViec($id)
    {
        if ($id == null) {
            abort(404);
        }
        $don = StopStudy::where('id', $id)->first();
        if (!$don) {
            abort(404);
        }
        if (Role(1) && $don->type != 0) {
            abort(404);
        }
        $phieu = [];
        $phieu['tiepnhan'] = json_decode($don->tiepnhan);
        $phieu['ykien'] = json_decode($don->ykien);
        $phieu['lanhdaophong'] = json_decode($don->lanhdaophong);
        $phieu['lanhdaotruong'] = json_decode($don->lanhdaotruong);
        return view('document.theodoithongke.theodoicongviec', ['phieu' => $phieu, 'type' => $don->type]);
    }
}
