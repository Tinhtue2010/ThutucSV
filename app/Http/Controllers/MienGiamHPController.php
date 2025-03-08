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
use Illuminate\Support\Facades\Storage;

class MienGiamHPController extends Controller
{
    function index()
    {
        $user = Auth::user();
        $don_parent = StopStudy::where('student_id', $user->student_id)->where('type', 1)->first();
        if ($don_parent) {
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
        return view('mien_giam_hoc_phi.index', ['don_parent' => $don_parent, 'don' => $don, 'student' => $student]);
    }


    function KyDonPdf(Request $request)
    {

        $chu_ky =  $this->convertImageToBase64(Auth::user()->getUrlChuKy());
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

        $doituong = config('doituong.miengiamhp');
        $studentData['doituong'] = $doituong[$request->doituong][2];

        $studentData['daduochuong'] = $request->daduochuong;
        $studentData['sdt'] = $student->phone;

        $studentData['day'] = Carbon::now()->day;

        $studentData['month'] = Carbon::now()->month;

        $studentData['year'] = Carbon::now()->year;
        
        $studentData['chu_ky'] = $chu_ky;


        $phieu = new Phieu();
        $phieu->student_id = $user->student_id;
        $phieu->name = "Đơn xin giảm học phí";
        $phieu->key = "GHP";
        $phieu->content = json_encode($studentData);
        $phieu->file = json_encode($this->uploadListFile($request, 'files', 'mien_giam_hp'));


        $pdf =  $this->createPDF($phieu);

        $this->saveBase64AsPdf($pdf,"demo");
        return true;
    }

    function CreateViewPdf(Request $request)
    {
        if(!$this->checkOtpApi($request->otp ?? ''))
        {
            abort(404);
        }
        if ($request->button_clicked == "xem_truoc") {
            Session::put('noisinh', $request->noisinh);
            Session::put('doituong', $request->doituong);
            Session::put('daduochuong', $request->daduochuong);
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

            $doituong = config('doituong.miengiamhp');
            $studentData['doituong'] = $doituong[$request->doituong][2];

            $studentData['daduochuong'] = $request->daduochuong;
            $studentData['sdt'] = $student->phone;

            $studentData['day'] = Carbon::now()->day;

            $studentData['month'] = Carbon::now()->month;

            $studentData['year'] = Carbon::now()->year;
            
            $studentData['url_chuky'] = Auth::user()->getUrlChuKy();

            $check = StopStudy::where('student_id', $user->student_id)->where('type', 1)->first();

            if ($check) {

                if (isset($check->files)) {
                    $this->deleteFiles(json_decode($check->files));
                }
                $check->files = json_encode($this->uploadListFile($request, 'files', 'mien_giam_hp'));

                $check->note = $request->data;
                $check->is_update = 1;
                if($request->doituong < 5)
                {
                    $check->phantramgiam = 100;
                }
                else if($request->doituong == 7)
                {
                    $check->phantramgiam = 50;
                }
                else
                {
                    $check->phantramgiam = 70;
                }
                $check->type_miengiamhp = $request->doituong ?? 1;
                $check->update();
                $phieu = Phieu::where('id', $check->phieu_id)->first();
                $phieu->student_id = $user->student_id;
                $phieu->name = "Đơn xin giảm học phí";
                $phieu->key = "GHP";
                $phieu->file = json_encode($this->uploadListFile($request, 'files', 'mien_giam_hp'));
                $phieu->content = json_encode($studentData);
                $phieu->save();
            } else {
                $phieu = new Phieu();
                $phieu->student_id = $user->student_id;
                $phieu->name = "Đơn xin giảm học phí";
                $phieu->key = "GHP";
                $phieu->content = json_encode($studentData);
                $phieu->file = json_encode($this->uploadListFile($request, 'files', 'mien_giam_hp'));
                $phieu->save();

                $query = new StopStudy();
                $query->files = json_encode($this->uploadListFile($request, 'files', 'mien_giam_hp'));
                $query->student_id = $user->student_id;
                $query->type_miengiamhp = $request->doituong ?? 1;
                $query->round = 1;
                $query->type = 1;
                if($request->doituong < 5)
                {
                    $query->phantramgiam = 100;
                }
                else if($request->doituong == 7)
                {
                    $query->phantramgiam = 50;
                }
                else
                {
                    $query->phantramgiam = 70;
                }
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
        $doituongSS = Session::get('doituong');
        $daduochuong = Session::get('daduochuong');

        $doituong = config('doituong.miengiamhp');
        $doituong = $doituong[$doituongSS];

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
        $studentData['sdt'] = $student->phone;

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
