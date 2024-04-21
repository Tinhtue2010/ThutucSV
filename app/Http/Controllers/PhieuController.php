<?php

namespace App\Http\Controllers;

use App\Models\Phieu;
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
}
