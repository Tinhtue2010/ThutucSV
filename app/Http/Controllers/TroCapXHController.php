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

class TroCapXHController extends Controller
{
    function index()
    {
        $user = Auth::user();
        $don_parent = StopStudy::where('student_id', $user->student_id)->where('type', 2)->first();
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
        return view('tro_cap_xh.index', ['don_parent' => $don_parent, 'don' => $don, 'phieu' => $phieu, 'student' => $student]);
    }

    function CreateViewPdf(Request $request)
    {
        if ($request->button_clicked == "xem_truoc") {
            Session::put('doituong', $request->doituong);
            Session::put('hoso', $request->hoso);
            Session::put('thuongchu', $request->thuongchu);
            
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
        $studentData['hoso'] = $request->hoso;
        $studentData['doituong'] = $request->doituong;
        $studentData['sdt'] = $student->phone;
        $studentData['thuongchu'] = $request->thuongchu;

        $studentData['day'] = Carbon::now()->day;

        $studentData['month'] = Carbon::now()->month;

        $studentData['year'] = Carbon::now()->year;

            $check = StopStudy::where('student_id', $user->student_id)->where('type', 2)->first();
            if ($check) {
                if(isset($check->files))
                {
                    $this->deleteFiles(json_decode($check->files));
                }
                $check->files = json_encode($this->uploadListFile($request,'files','mien_giam_hp'));
                
                $check->note = $request->data;
                $check->is_update = 1;
                $check->update();
                $phieu = Phieu::where('id', $check->phieu_id)->first();
                $phieu->student_id = $user->student_id;
                $phieu->name = "Đơn xin rút hồ sơ";
                $phieu->key = "TCXH";
                $phieu->content = json_encode($studentData);
                $phieu->save();
            } else {
                $phieu = new Phieu();
                $phieu->student_id = $user->student_id;
                $phieu->name = "Đơn xin rút hồ sơ";
                $phieu->key = "TCXH";
                $phieu->content = json_encode($studentData);
                $phieu->save();

                $query = new StopStudy();
                $query->files = json_encode($this->uploadListFile($request,'files','mien_giam_hp'));
                $query->student_id = $user->student_id;
                $query->round = 1;
                $query->type = 2;
                $query->note = $request->data;
                $query->phieu_id = $phieu->id;
                $query->lop_id = $student->lop_id;
                $query->save();

                $this->notification("Đơn xin trợ cấp xã hội của bạn đã được gửi, vui lòng chờ thông báo khác", $phieu->id, "TCXH");
            }
        }
        return true;
    }
    function viewDemoPdf()
    {
        $doituong = Session::get('doituong');
        $hoso = Session::get('hoso');
        $thuongchu = Session::get('thuongchu');

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
        $studentData['hoso'] = $hoso;
        $studentData['doituong'] = $doituong;
        $studentData['sdt'] = $student->phone;
        $studentData['thuongchu'] = $thuongchu;

        $studentData['day'] = Carbon::now()->day;

        $studentData['month'] = Carbon::now()->month;

        $studentData['year'] = Carbon::now()->year;

        return view('document.trocapxahoi', ['data' => $studentData]);
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
