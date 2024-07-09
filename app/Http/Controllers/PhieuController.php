<?php

namespace App\Http\Controllers;

use App\Models\Phieu;
use App\Models\StopStudy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PhieuController extends Controller
{
    function index($id = null)
    {
        if ($id == null) {
            abort(404);
        }
        $phieu = Phieu::find($id);
        if ($id == "DSMGHP0") {
            $phieu = Phieu::where('key','DSMGHP')->where('status',0)->first();
        }
        if ($id == "DSMGHP1") {
            $phieu = Phieu::where('key','DSMGHP')->where('status',1)->first();
        }
        if ($id == "QDGHP0") {
            $phieu = Phieu::where('key','QDGHP')->where('status',0)->first();
        }
        if ($id == "QDGHP1") {
            $phieu = Phieu::where('key','QDGHP')->where('status',1)->first();
        }
        if ($id == "PTGHP0") {
            $phieu = Phieu::where('key','PTGHP')->where('status',0)->first();
        }
        if ($id == "PTGHP1") {
            $phieu = Phieu::where('key','PTGHP')->where('status',1)->first();
        }

        if ($id == "DSTCXH0") {
            $phieu = Phieu::where('key','DSTCXH')->where('status',0)->first();
        }
        
        if ($id == "QDTCXH0") {
            $phieu = Phieu::where('key','QDTCXH')->where('status',0)->first();
        }
        if ($id == "PTTCXH0") {
            $phieu = Phieu::where('key','PTTCXH')->where('status',0)->first();
        }

        if ($id == "DSTCHP0") {
            $phieu = Phieu::where('key','DSTCHP')->where('status',0)->first();
        }
        
        if ($id == "QDTCHP0") {
            $phieu = Phieu::where('key','QDTCHP')->where('status',0)->first();
        }
        if ($id == "PTTCHP0") {
            $phieu = Phieu::where('key','PTTCHP')->where('status',0)->first();
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
        if($phieu->key == "DSMGHP")
        {
            return view('document.theodoithongke.m01_02_05', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);

        }
        if($phieu->key == "QDGHP")
        {
            return view('document.theodoithongke.m01_02_06', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);

        }
        if($phieu->key == "PTGHP")
        {
            return view('document.theodoithongke.m01_02_07', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if($phieu->key == "DSTCXH")
        {
            return view('document.theodoithongke.m01_03_06', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if($phieu->key == "QDTCXH")
        {
            return view('document.theodoithongke.m01_03_07', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if($phieu->key == "PTTCXH")
        {
            return view('document.theodoithongke.m01_03_10', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }

        if($phieu->key == "DSTCHP")
        {
            return view('document.theodoithongke.m01_03_08', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if($phieu->key == "QDTCHP")
        {
            return view('document.theodoithongke.m01_03_09', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if($phieu->key == "PTTCHP")
        {
            return view('document.theodoithongke.m01_03_10_2', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        return dd("chưa có phiếu");
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
