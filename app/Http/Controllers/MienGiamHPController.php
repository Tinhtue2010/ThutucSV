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

class MienGiamHPController extends Controller
{
    function index()
    {
        $user = Auth::user();
        $don_parent = StopStudy::where('student_id', $user->student_id)->where('type', 1)->first();
        if ($don_parent) {
            $phieu = Phieu::where('id', $don_parent->phieu_id)->first();
            $phieu = json_decode($phieu->content, true);
            $don = StopStudy::where('parent_id', $don_parent->id)->orderBy('created_at', 'desc')->first();
        } else {
            $phieu = null;
            $don = null;
        }

        $user = Auth::user();
        $student = Student::leftJoin('lops', 'students.lop_id', '=', 'lops.id')
            ->leftJoin('khoas', 'lops.khoa_id', '=', 'khoas.id')
            ->select('students.*', 'lops.name as lop_name', 'khoas.name as khoa_name')
            ->where('students.id', $user->student_id)->first();
        return view('mien_giam_hoc_phi.index', ['don_parent' => $don_parent, 'don' => $don, 'phieu' => $phieu, 'student' => $student]);
    }

    function CreateViewPdf(Request $request)
    {
        if ($request->button_clicked == "xem_truoc") {
            Session::put('noisinh', $request->noisinh);
            Session::put('doituong', $request->doituong);
            Session::put('daduochuong', $request->daduochuong);
            Session::put('sdt', $request->sdt);
        } else {
            $user = Auth::user();

            $student = Student::leftJoin('lops', 'students.lop_id', '=', 'lops.id')
                ->leftJoin('khoas', 'lops.khoa_id', '=', 'khoas.id')
                ->select('students.*', 'lops.name as lop_name', 'khoas.name as khoa_name')
                ->where('students.id', $user->student_id)->first();

            $studentData['full_name'] = $student->full_name;
            $studentData['student_code'] = $student->student_code;
            $studentData['date_of_birth'] = Carbon::createFromFormat('Y-m-d', $student->date_of_birth)->format('d/m/Y');
            $studentData['lop'] = $student->lop_name;
            $studentData['khoa'] = $student->khoa_name;
            $studentData['khoa_hoc'] = $student->school_year;
            $studentData['noisinh'] = $request->noisinh;
            $studentData['doituong'] = $request->doituong;
            $studentData['daduochuong'] = $request->daduochuong;
            $studentData['sdt'] = $request->sdt;

            $studentData['day'] = Carbon::now()->day;

            $studentData['month'] = Carbon::now()->month;

            $studentData['year'] = Carbon::now()->year;

            $check = StopStudy::where('student_id', $user->student_id)->where('type', 1)->first();
            if ($check) {
                $check->note = $request->data;
                $check->update();
                $phieu = Phieu::where('id', $check->phieu_id)->first();
                $phieu->student_id = $user->student_id;
                $phieu->name = "Đơn xin rút hồ sơ";
                $phieu->key = "GHP";
                $phieu->content = json_encode($studentData);
                $phieu->save();
            } else {
                $phieu = new Phieu();
                $phieu->student_id = $user->student_id;
                $phieu->name = "Đơn xin rút hồ sơ";
                $phieu->key = "GHP";
                $phieu->content = json_encode($studentData);
                $phieu->save();

                $query = new StopStudy();
                $query->student_id = $user->student_id;
                $query->round = 1;
                $query->type = 1;
                $query->note = $request->data;
                $query->phieu_id = $phieu->id;
                $query->lop_id = $student->lop_id;
                $query->save();

                $this->notification("Đơn xin miễn giảm học phí của bạn đã được gửi, vui lòng chờ thông báo khác", $phieu->id, "GHP");
            }
        }
        return true;
    }
    function viewDemoPdf()
    {
        $noisinh = Session::get('noisinh');
        $doituong = Session::get('doituong');
        $daduochuong = Session::get('daduochuong');
        $sdt = Session::get('sdt');

        $user = Auth::user();
        $student = Student::leftJoin('lops', 'students.lop_id', '=', 'lops.id')
            ->leftJoin('khoas', 'lops.khoa_id', '=', 'khoas.id')
            ->select('students.*', 'lops.name as lop_name', 'khoas.name as khoa_name')
            ->where('students.id', $user->student_id)->first();

        $studentData['full_name'] = $student->full_name;
        $studentData['student_code'] = $student->student_code;
        $studentData['date_of_birth'] = Carbon::createFromFormat('Y-m-d', $student->date_of_birth)->format('d/m/Y');
        $studentData['lop'] = $student->lop_name;
        $studentData['khoa'] = $student->khoa_name;
        $studentData['khoa_hoc'] = $student->school_year;
        $studentData['noisinh'] = $noisinh;
        $studentData['doituong'] = $doituong;
        $studentData['daduochuong'] = $daduochuong;
        $studentData['sdt'] = $sdt;

        $studentData['day'] = Carbon::now()->day;

        $studentData['month'] = Carbon::now()->month;

        $studentData['year'] = Carbon::now()->year;

        return view('document.miengiamhp', ['data' => $studentData]);
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
