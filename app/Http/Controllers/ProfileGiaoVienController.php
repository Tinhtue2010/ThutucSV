<?php

namespace App\Http\Controllers;


use APP\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileGiaoVienController extends Controller
{
    function index(){
        $user = Auth::user();
        $teacher = Teacher::leftJoin('khoas','teachers.khoa_id','=','khoas.id')
        ->select('teachers.*','khoas.name as khoa_name')
        ->where('teachers.id',$user->teacher_id)->first();
        $teacherArray = $teacher->toArray();
        $totalFields = count($teacherArray);
        $nonNullFields = count(array_filter($teacherArray));

        $percentage = intval(($nonNullFields / $totalFields) * 100);
        return view('giao_vien.index',['percent'=>$percentage,'teacher'=>$teacher]);
    }

    public function getDataInfo()
    {
        $user = Auth::user();
        try {
            $error = Teacher::findOrFail($user->teacher_id);

            return $error;
        } catch (QueryException $e) {
            abort(404);
        }
    }

    public function update(Request $request) {
        $user = Auth::user();
        $teacher = Teacher::find($user->teacher_id);

        return $teacher->update($request->only([
            'full_name',
            'dia_chi',
            'sdt',
            'email'
        ]));
    }
}
