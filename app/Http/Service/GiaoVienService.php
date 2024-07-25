<?php

namespace App\Http\Service;

use App\Http\Controllers\Controller;
use App\Models\Phieu;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class GiaoVienService  extends Controller
{
    function xacnhanRHS($request, $stopStudy)
    {
        try {
            if ($stopStudy->status != 0 && $stopStudy->status != -1) {
                abort(404);
            }
            $teacher = Teacher::find(Auth::user()->teacher_id);
            $stopStudy->update(["status" => 1]);

            $phieu = Phieu::find($stopStudy->phieu_id);
            $phieu_content = json_decode($phieu->content,true);
            $phieu_content['giaovien'] = [
                "full_name" => $teacher->full_name,
                "url_chuky" => $teacher->chu_ky,
            ];
            $phieu->content = json_encode($phieu_content,true);
            $phieu->save();

            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->phieu_id = null;
            $newStopStudy->status = 1;
            $newStopStudy->teacher_id = $teacher->id;
            $newStopStudy->parent_id = $request->id;
            $newStopStudy->note = $request->note;
            $user = User::where('student_id',$stopStudy->student_id)->first();
            $this->notification("Đơn xin rút hồ sơ của bạn đã được giáo viên chủ nhiệm xác nhận", null, "RHS",$user->id);
            $newStopStudy->save();
        } catch (QueryException $e) {
            abort(404);
        }
    }
    function khongxacnhanRHS($request, $stopStudy)
    {
        try {

            if ($stopStudy->status != 0 && $stopStudy->status != 1 && $stopStudy->status != -1) {
                abort(404);
            }
            $stopStudy->update(["status" => -1]);

            $phieu = Phieu::find($stopStudy->phieu_id);
            $phieu_content = json_decode($phieu->content,true);
            $phieu_content['giaovien'] = "";
            $phieu->content = json_encode($phieu_content,true);
            $phieu->save();

            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->status = 0;
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->phieu_id = null;
            $newStopStudy->parent_id = $request->id;
            $user_id = User::where('student_id',$stopStudy->student_id)->first()->id;
            $this->notification("Đơn xin rút của bạn đã bị từ chối bởi giáo viên chủ nhiệm", null, "RHS",$user_id);
            $newStopStudy->note = $request->note;


            $newStopStudy->save();
        } catch (QueryException $e) {
            abort(404);
        }
    }
}
