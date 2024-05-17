<?php

namespace App\Http\Service;

use App\Http\Controllers\Controller;
use App\Models\Student;
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
            $stopStudy->update(["status" => 1]);

            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->phieu_id = null;
            $newStopStudy->status = 1;
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->parent_id = $request->id;
            $newStopStudy->note = $request->note;

            $this->notification("Đơn xin rút hồ sơ của bạn đã được giáo viên chủ nhiệm xác nhận", null, "RHS",$stopStudy->student_id);
            $newStopStudy->save();
        } catch (QueryException $e) {
            abort(404);
        }
    }
    function khongxacnhanRHS($request, $stopStudy)
    {
        try {

            if ($stopStudy->status != 0 && $stopStudy->status != 1) {
                abort(404);
            }
            $stopStudy->update(["status" => -1]);

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
