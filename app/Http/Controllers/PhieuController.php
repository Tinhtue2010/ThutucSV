<?php

namespace App\Http\Controllers;

use App\Models\Phieu;
use App\Models\StopStudy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PhieuController extends Controller
{
    function index($id = null) {
        if($id == null)
        {
            abort(404);
        }
        $phieu = Phieu::find($id);
        if(!$phieu)
        {
            abort(404);
        }
        if(Role(1))
        {
            if($phieu->student_id != Auth::user()->student_id)
            {
                abort(404);
            }
        }

        if($phieu->key == "RHS")
        {
            return view('document.thoi_hoc', ['data' => json_decode($phieu->content, true)]);
        }
        if($phieu->key == "GHP")
        {
            return view('document.miengiamhp', ['data' => json_decode($phieu->content, true)]);
        }
        if($phieu->key == "TCXH")
        {
            return view('document.trocapxahoi', ['data' => json_decode($phieu->content, true)]);
        }
        if($phieu->key == "TCHP")
        {
            return view('document.hotro_cpht', ['data' => json_decode($phieu->content, true)]);
        }
        if($phieu->key == "CDCS")
        {
            return view('document.chinhsach_qn', ['data' => json_decode($phieu->content, true)]);
        }
        if($phieu->key == "HDBSSH")
        {
            return view('document.huongdanbosung', ['data' => json_decode($phieu->content, true)]);
        }
        if($phieu->key == "TCGQ")
        {
            return view('document.tuchoi', ['data' => json_decode($phieu->content, true)]);
        }
        if($phieu->key == "TNHS")
        {
            return view('document.tiepnhan', ['data' => json_decode($phieu->content, true),'phieu'=>$phieu]);
        }
        return dd("chưa có phiếu");

    }
    function getData($id = null) {
        if($id == null)
        {
            abort(404);
        }
        $phieu = Phieu::find($id);
        if(!$phieu)
        {
            abort(404);
        }
        if(Role(1))
        {
            if($phieu->student_id != Auth::user()->student_id)
            {
                abort(404);
            }
        }
        return json_decode($phieu->content);
    }

    function giaQuyetCongViec($id) {
        if($id == null)
        {
            abort(404);
        }
        $don = StopStudy::where('id', $id)->first();
        if(!$don)
        {
            abort(404);
        }
        $phieu = [];
        $phieu['tiepnhan'] = json_decode($don->tiepnhan);
        $phieu['ykien'] =json_decode($don->ykien);
        $phieu['lanhdaophong'] =json_decode($don->lanhdaophong);
        $phieu['lanhdaotruong'] =json_decode($don->lanhdaotruong);
        return view('document.theodoithongke.theodoicongviec',['phieu'=>$phieu,'type'=>$don->type]);
    }
}
