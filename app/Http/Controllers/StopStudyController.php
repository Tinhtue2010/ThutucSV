<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Phieu;
use App\Models\StopStudy;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class StopStudyController extends Controller
{
    function index()
    {
        $user = Auth::user();
        $check = StopStudy::where('student_id', $user->student_id)->first();
        $notification = Notification::where('type', 'RHS')->get();
        return view('stop_study.index', ['check' => $check, 'notification' => $notification]);
    }

    function CreateViewPdf(Request $request)
    {
        if ($request->button_clicked == "xem_truoc") {
            Session::put('test_stop_study', $request->data);
        } else {
            $user = Auth::user();

            $student = Student::leftJoin('lops', 'students.lop_id', '=', 'lops.id')
                ->leftJoin('khoas', 'lops.khoa_id', '=', 'khoas.id')
                ->select('students.*', 'lops.name as lop_name', 'khoas.name as khoa_name')
                ->where('students.id', $user->student_id)->first();

            $studentData['full_name'] = $student->full_name;
            $studentData['date_of_birth'] = Carbon::createFromFormat('Y-m-d', $student->date_of_birth)->format('d/m/Y');
            $studentData['lop'] = $student->lop_name;
            $studentData['khoa'] = $student->khoa_name;
            $studentData['data'] = $request->data;
            $studentData['day'] = Carbon::now()->day;

            $studentData['month'] = Carbon::now()->month;
    
            $studentData['year'] = Carbon::now()->year;

            $check = StopStudy::where('student_id', $user->student_id)->first();
            if ($check) {
                $phieu = Phieu::where('id', $check->phieu_id)->first();
                $phieu->student_id = $user->student_id;
                $phieu->name = "Đơn xin rút hồ sơ";
                $phieu->key = "RHS";
                $phieu->content = json_encode($studentData);
                $phieu->save();
            } else {
                $phieu = new Phieu();
                $phieu->student_id = $user->student_id;
                $phieu->name = "Đơn xin rút hồ sơ";
                $phieu->key = "RHS";
                $phieu->content = json_encode($studentData);
                $phieu->save();

                $query = new StopStudy();
                $query->student_id = $user->student_id;
                $query->round = 1;
                $query->note = $request->data;
                $query->phieu_id = $phieu->id;
                $query->lop_id = $student->lop_id;
                $query->save();

                $this->notification("Đơn xin rút của bạn đã được gửi, vui lòng chờ thông báo khác", $phieu->id, "RHS");
            }
        }
        return true;
    }
    function viewDemoPdf()
    {
        $value = Session::get('test_stop_study');

        $user = Auth::user();
        $student = Student::leftJoin('lops', 'students.lop_id', '=', 'lops.id')
            ->leftJoin('khoas', 'lops.khoa_id', '=', 'khoas.id')
            ->select('students.*', 'lops.name as lop_name', 'khoas.name as khoa_name')
            ->where('students.id', $user->student_id)->first();

        $studentData['full_name'] = $student->full_name;
        $studentData['date_of_birth'] = Carbon::createFromFormat('Y-m-d', $student->date_of_birth)->format('d/m/Y');
        $studentData['lop'] = $student->lop_name;
        $studentData['khoa'] = $student->khoa_name;
        $studentData['data'] = $value;

        $studentData['day'] = Carbon::now()->day;

        $studentData['month'] = Carbon::now()->month;

        $studentData['year'] = Carbon::now()->year;

        return view('document.thoi_hoc', ['data' => $studentData]);
    }

    function viewPdf($id)
    {
        try {
            $user = Auth::user();
            $noti = Notification::where('user_id', $user->id)->where('id', $id)->first();
            if ($noti) {

                $data = Phieu::where('id', $noti->phieu_id)->first();
                return view('document.thoi_hoc', ['data' => json_decode($data->content, true)]);
            } else {
                abort(404);
            }
        } catch (\Throwable $th) {
            abort(404);
        }
    }
}
