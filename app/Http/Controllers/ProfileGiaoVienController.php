<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileGiaoVienController extends Controller
{
    function index()
    {
        $user = Auth::user();
        $teacher = Teacher::leftJoin('khoas', 'teachers.ma_khoa', '=', 'khoas.ma_khoa')
            ->select('teachers.*', 'khoas.name as khoa_name')
            ->where('teachers.id', $user->teacher_id)->first();
        return view('profile_giao_vien.index', ['teacher' => $teacher, 'user' => $user]);
    }

    public function getDataInfo()
    {
        $user = Auth::user();
        try {
            $error = Teacher::join('users', 'teachers.id', '=', 'users.teacher_id')
                ->select('teachers.*', 'users.cccd')
                ->where('teachers.id', $user->teacher_id)
                ->first();

            return $error;
        } catch (QueryException $e) {
            abort(404);
        }
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $teacher = Teacher::find($user->teacher_id);
        if ($request->hasFile('chu_ky')) {
            $file = $request->file('chu_ky');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('signatures', $fileName, 'public');
            $teacher->chu_ky = $filePath;
        }

        $teacher->full_name = $request->input('full_name');
        $teacher->dia_chi = $request->input('dia_chi');
        $teacher->sdt = $request->input('sdt');
        $teacher->email = $request->input('email');
        if ($request->password != '') {
            $user->password = bcrypt($request->password);
        }
        $teacher->save();


        $user->cccd = $request->input('cccd');
        return $user->update();
    }
}
