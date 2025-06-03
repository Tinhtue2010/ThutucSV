<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    function index()
    {
        $user = Auth::user();
        $student = Student::leftJoin('lops', 'students.ma_lop', '=', 'lops.ma_lop')
            ->leftJoin('khoas', 'lops.ma_khoa', '=', 'khoas.ma_khoa')
            ->select('students.*', 'lops.name as lop_name', 'khoas.name as khoa_name')
            ->where('students.id', $user->student_id)->first();
            
        // if (!session()->has('show_login_alert')) {
        //     session()->put('show_login_alert', true);
        // }
        return view('student.index', ['student' => $student]);
    }

    public function getDataInfo()
    {
        $user = Auth::user();
        try {
            $error = Student::findOrFail($user->student_id);

            return $error;
        } catch (QueryException $e) {
            abort(404);
        }
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $student = Student::find($user->student_id);

        if ($request->hasFile('chu_ky')) {
            $file = $request->file('chu_ky');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('signatures', $fileName, 'public');
            $student->chu_ky = $filePath;
        }

        $student->full_name = $request->input('full_name');
        $student->date_of_birth = Carbon::createFromFormat('d/m/Y', $request->date_of_birth)->format('Y-m-d');
        $student->phone = $request->input('phone');
        $student->email = $request->input('email');
        $student->cmnd = $request->input('cmnd');
        $student->date_range_cmnd = Carbon::createFromFormat('d/m/Y', $request->date_range_cmnd)->format('Y-m-d');
        if ($request->password != '') {
            $user->password = bcrypt($request->password);
        }
        $student->save();

        $user->cccd = $request->input('cmnd');
        return $user->update();
    }
}
