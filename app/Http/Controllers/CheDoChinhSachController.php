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

class CheDoChinhSachController extends Controller
{
    function index()
    {
        $user = Auth::user();
        $don_parent = StopStudy::where('student_id', $user->student_id)->where('type', 4)->first();
        if ($don_parent) {
            $phieu = null;
            $don = StopStudy::where('parent_id', $don_parent->id)->orderBy('created_at', 'desc')->first();
        } else {
            $phieu = null;
            $don = null;
        }
        $user = Auth::user();
        $student = Student::leftJoin('lops', 'students.ma_lop', '=', 'lops.ma_lop')
            ->leftJoin('khoas', 'lops.ma_khoa', '=', 'khoas.ma_khoa')
            ->select('students.*', 'lops.name as lop_name', 'khoas.name as khoa_name')
            ->where('students.id', $user->student_id)->first();
        return view('che_do_chinh_sach.index', ['don_parent' => $don_parent, 'don' => $don, 'phieu' => $phieu, 'student' => $student]);
    }
    function kydonPDF(Request $request)
    {
        $chu_ky =  $this->convertImageToBase64(Auth::user()->getUrlChuKy());
        $user = Auth::user();

        $info_signature = $this->getInfoSignature($user->cccd);
        if ($info_signature === false) {
            return 0; //chưa đăng ký chữ ký số cần đăng ký chữ ký số
        }
        $user = Auth::user();


        $student = Student::leftJoin('lops', 'students.ma_lop', '=', 'lops.ma_lop')
            ->leftJoin('khoas', 'lops.ma_khoa', '=', 'khoas.ma_khoa')
            ->select('students.*', 'lops.name as lop_name', 'khoas.name as khoa_name')
            ->where('students.id', $user->student_id)->first();

        $studentData['full_name'] = $student->full_name;
        $studentData['student_code'] = $student->student_code;
        $studentData['date_of_birth'] = Carbon::parse($student->date_of_birth)->format('d/m/Y');
        $studentData['lop'] = $student->lop_name;
        $studentData['khoa'] = $student->khoa_name;
        $studentData['khoa_hoc'] = $student->nien_khoa;
        $studentData['hoso'] = $request->hoso;

        $doituong = config('doituong.chedochinhsach');

        $studentData['doituong'] = $doituong[$request->doituong][1];

        $studentData['sdt'] = $student->phone;
        $studentData['thuongchu'] = $request->thuongchu;
        $studentData['day'] = Carbon::now()->day;
        $studentData['month'] = Carbon::now()->month;
        $studentData['year'] = Carbon::now()->year;
        $studentData['chu_ky'] = $chu_ky;


        $phieu = new Phieu();
        $phieu->student_id = $user->student_id;
        $phieu->name = "Đơn xin chế độ chính sách";
        $phieu->key = "CDCS";
        $phieu->content = json_encode($studentData);

        $pdf =  $this->createPDF($phieu);

        return $this->craeteSignature($info_signature, $pdf, $user->cccd, 'CHE_DO_CHINH_SACH');
    }
    function CreateViewPdf(Request $request)
    {
        $getPDF = $this->getPDF($request->fileId, $request->tranId, $request->transIDHash);
        if ($getPDF === 0) {
            return 0;
        }

        $user = Auth::user();

        $student = Student::leftJoin('lops', 'students.ma_lop', '=', 'lops.ma_lop')
            ->leftJoin('khoas', 'lops.ma_khoa', '=', 'khoas.ma_khoa')
            ->select('students.*', 'lops.name as lop_name', 'khoas.name as khoa_name')
            ->where('students.id', $user->student_id)->first();

        $file_name = $this->saveBase64AsPdf($getPDF, 'CHE_DO_CHINH_SACH/' . ($student->student_code ?? $student->id), 'don-');
        $uploadedFiles = json_encode($this->uploadListFile($request, 'files', 'CHE_DO_CHINH_SACH/' . ($student->student_code ?? $student->id), 'file-dinh-kem-'));

        $doituong_key = "1";
        if ($request->doituong == 2) {
            $doituong_key = "4";
        }


        $check = StopStudy::where('student_id', $user->student_id)->where('type', 4)->first();
        if ($check) {
            if (isset($check->files)) {
                $this->deleteFiles(json_decode($check->files));
            }
            $check->files = $uploadedFiles;
            $check->file_name = $file_name;

            $check->note = $request->data;
            $check->doi_tuong_chinh_sach = json_encode([$doituong_key]);
            $check->update();
        } else {
            $query = new StopStudy();
            $query->file_name = $file_name;
            $query->files = $uploadedFiles;
            $query->student_id = $user->student_id;
            $query->round = 1;
            $query->type = 4;
            $query->note = $request->data;
            $query->ma_lop = $student->ma_lop;
            $query->doi_tuong_chinh_sach = json_encode([$doituong_key]);
            $query->save();

            $this->notification("Đơn xin chế độ chính sách của bạn đã được gửi, vui lòng chờ thông báo khác", null, $file_name, "CDCS");
        }

        return true;
    }
    function viewDemoPdf()
    {
        $doituong = Session::get('doituong');
        $hoso = Session::get('hoso');
        $thuongchu = Session::get('thuongchu');

        $user = Auth::user();
        $student = Student::leftJoin('lops', 'students.ma_lop', '=', 'lops.ma_lop')
            ->leftJoin('khoas', 'lops.khoa_id', '=', 'khoas.id')
            ->select('students.*', 'lops.name as lop_name', 'khoas.name as khoa_name')
            ->where('students.id', $user->student_id)->first();

        $studentData['full_name'] = $student->full_name;
        $studentData['student_code'] = $student->student_code;
        $studentData['date_of_birth'] = Carbon::createFromFormat('Y-m-d', $student->date_of_birth)->format('d/m/Y');
        $studentData['lop'] = $student->lop_name;
        $studentData['khoa'] = $student->khoa_name;
        $studentData['khoa_hoc'] = $student->nien_khoa;
        $studentData['hoso'] = $hoso;
        $studentData['doituong'] = $doituong;
        $studentData['sdt'] = $student->phone;
        $studentData['thuongchu'] = $thuongchu;

        $studentData['day'] = Carbon::now()->day;

        $studentData['month'] = Carbon::now()->month;

        $studentData['year'] = Carbon::now()->year;

        return view('document.chinhsach_qn', ['data' => $studentData]);
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
