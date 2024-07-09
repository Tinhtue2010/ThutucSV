<?php

namespace App\Http\Service;

use App\Http\Controllers\Controller;
use App\Models\Phieu;
use App\Models\Teacher;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class KeHoachTaiChinhService  extends Controller
{
    function xacnhanRHS($stopStudy)
    {
        if ($stopStudy->status != 2 && $stopStudy->status != 3 && $stopStudy->status != -3) {
            abort(404);
        }
        $teacher = Teacher::find(Auth::user()->teacher_id);

        $phieu = Phieu::find($stopStudy->phieu_id);
        $phieu_content = json_decode($phieu->content,true);
        $phieu_content['ke_hoac_tai_chinh'] = [
            "full_name" => $teacher->full_name,
            "url_chuky" => $teacher->chu_ky,
        ];
        $phieu->content = json_encode($phieu_content,true);
        $phieu->save();
        $stopStudy->update(["is_pay" => 1,"note_pay"=>""]);

        return true;
    }
    function khongxacnhanRHS($request, $stopStudy)
    {
        if ($stopStudy->status != 2 && $stopStudy->status != -3 && $stopStudy->status != 3) {
            abort(404);
        }
        $phieu = Phieu::find($stopStudy->phieu_id);
        $phieu_content = json_decode($phieu->content,true);
        $phieu_content['ke_hoac_tai_chinh'] = "";
        $phieu->content = json_encode($phieu_content,true);
        $phieu->save();
        
        $stopStudy->update(["is_pay" => 2,"note_pay"=>$request->note]);
    }
}
