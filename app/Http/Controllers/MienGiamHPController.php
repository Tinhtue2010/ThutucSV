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
        $student = Student::leftJoin('lops', 'students.ma_lop', '=', 'lops.ma_lop')
            ->leftJoin('khoas', 'lops.ma_khoa', '=', 'khoas.ma_khoa')
            ->select('students.*', 'lops.name as lop_name', 'khoas.name as khoa_name')
            ->where('students.id', $user->student_id)->first();
        return view('mien_giam_hoc_phi.index', ['don_parent' => $don_parent, 'don' => $don, 'student' => $student]);
    }


    function KyDonPdf(Request $request)
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
        $studentData['noisinh'] = $request->noisinh;

        $doituong = config('doituong.miengiamhp');
        // $studentData['doituong'] = $doituong[$request->doituong][2];
        $studentData['doituong'] = '';

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
        // $phieu->file = json_encode($this->uploadListFile($request, 'files', 'MIEN_GIAM_HOC_PHI', 'file-dinh-kem'));

        $pdf =  $this->createPDF($phieu);

        return $this->craeteSignature($info_signature, $pdf, $user->cccd, 'MIEN_GIAM_HOC_PHI');
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
            ->where('students.id', $user->student_id)
            ->first();

        $file_name = $this->saveBase64AsPdf($getPDF,'MIEN_GIAM_HOC_PHI/' . ($student->student_code ?? $student->id),'don-');
        $uploadedFiles = json_encode($this->uploadListFile($request, 'files', 'MIEN_GIAM_HOC_PHI/' . ($student->student_code ?? $student->id), 'file-dinh-kem-'));
        
        $phantramgiam = match ($request->doituong) {
            null, 5, 6 => 70,
            7 => 50,
            default => 100,
        };
    

        
        $check = StopStudy::where('student_id', $user->student_id)->where('type', 1)->first();
    
        if ($check) {
            if (isset($check->files)) {
                $this->deleteFiles(json_decode($check->files));
            }
            $check->update([
                'files' => $uploadedFiles,
                'note' => $request->data,
                'is_update' => 1,
                'phantramgiam' => $phantramgiam,
                'type_miengiamhp' => $request->doituong ?? 1,
                'file_name' => $file_name
            ]);
        } else {
            $query = new StopStudy([
                'files' => $uploadedFiles,
                'student_id' => $user->student_id,
                'type_miengiamhp' => $request->doituong ?? 1,
                'name' => 'Hồ sơ miễn, giảm học phí theo NĐ 81',
                'nam_hoc' => '2024-2025',
                'ky_hoc' => 2,
                'round' => 1,
                'type' => 1,
                'phantramgiam' => $phantramgiam,
                'note' => $request->data,
                'ma_lop' => $student->ma_lop,
                'file_name' => $file_name
            ]);
            $query->save();
            $this->notification("Đơn xin miễn giảm học phí của bạn đã được gửi, vui lòng chờ thông báo khác", null,$file_name, "GHP");
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
