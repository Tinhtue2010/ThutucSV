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
        if($request->hasFile('chu_ky')){
            $file = $request->file('chu_ky');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('signatures', $fileName, 'public');
            $teacher->chu_ky = $filePath;
        }

        $teacher->full_name = $request->input('full_name');
        $teacher->dia_chi = $request->input('dia_chi');
        $teacher->sdt = $request->input('sdt');
        $teacher->email = $request->input('email');

        $teacher->safe();
    }
}
