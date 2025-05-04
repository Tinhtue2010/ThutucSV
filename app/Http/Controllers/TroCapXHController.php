<?php

namespace App\Http\Controllers;

use App\Models\Phieu;
use App\Models\StopStudy;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class TroCapXHController extends Controller
{
    function index()
    {
        $lastSegment = request()->segment(count(request()->segments()));
        $user = Auth::user();
        if($lastSegment == "tro-cap-xh")
        {
            $don_parent = StopStudy::where('student_id', $user->student_id)->where('type', 2)->first();
        }
        else
        {
            $don_parent = StopStudy::where('student_id', $user->student_id)->where('type', 3)->first();
        }
        
        if ($don_parent) {
            $don = StopStudy::where('parent_id', $don_parent->id)->orderBy('created_at', 'desc')->first();
        } else {
            $phieu = null;
            $don = null;
        }

        $student = Student::leftJoin('lops', 'students.ma_lop', '=', 'lops.ma_lop')
            ->leftJoin('khoas', 'lops.ma_khoa', '=', 'khoas.ma_khoa')
            ->select('students.*', 'lops.name as lop_name', 'khoas.name as khoa_name')
            ->where('students.id', $user->student_id)->first();
        return view('tro_cap_xh.index', ['don_parent' => $don_parent, 'don' => $don, 'student' => $student]);
    }

    function kydonPDF(Request $request)
    {
        $chu_ky =  $this->convertImageToBase64(Auth::user()->getUrlChuKy());

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

        $doituong = config('doituong.trocapxahoi');
        $studentData['doituong'] = $doituong[$request->doituong][2];


        $studentData['sdt'] = $student->phone;
        $studentData['thuongtru'] = $request->thuongtru;

        $studentData['day'] = Carbon::now()->day;

        $studentData['month'] = Carbon::now()->month;

        $studentData['year'] = Carbon::now()->year;

        $studentData['chu_ky'] = $chu_ky;

        if (isset($request->trocapxh)) {
            return $this->createPhieu($user, $request, $studentData, $student, 2);
        }
        if (isset($request->trocaphp)) {
            return $this->createPhieu($user, $request, $studentData, $student, 3);
        }

        return null;
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

        if (isset($request->trocapxh)) {
            $file_name = $this->saveBase64AsPdf($getPDF, 'TRO_CAP_XH/' . ($student->student_code ?? $student->id), 'don-');
            $file_dinh_kem = json_encode($this->uploadListFile($request, 'files', 'TRO_CAP_XH/' . ($student->student_code ?? $student->id), 'file-dinh-kem-'));
            $check = StopStudy::where('student_id', $user->student_id)->where('type', 2)->first();
        }
        if (isset($request->trocaphp)) {
            $file_name = $this->saveBase64AsPdf($getPDF, 'TRO_CAP_HP/' . ($student->student_code ?? $student->id), 'don-');
            $file_dinh_kem = json_encode($this->uploadListFile($request, 'files', 'TRO_CAP_HP/' . ($student->student_code ?? $student->id), 'file-dinh-kem-'));
            $check = StopStudy::where('student_id', $user->student_id)->where('type', 3)->first();
        }

        if ($check) {
            if (isset($check->files)) {
                $this->deleteFiles(json_decode($check->files));
            }
            $check->update([
                'files' => $file_dinh_kem,
                'note' => $request->data,
                'is_update' => 1,
                'type_miengiamhp' => $request->doituong,
                'file_name' => $file_name
            ]);
        } else {
            $query = new StopStudy([
                'files' => $file_dinh_kem,
                'student_id' => $user->student_id,
                'round' => 1,
                'type' => $request->trocapxh ? 2 : 3,
                'note' => $request->data,
                'ma_lop' => $student->ma_lop,
                'type_miengiamhp' => $request->doituong,
                'file_name' => $file_name
            ]);
            $query->save();
            
            $this->notification("Đơn xin miễn giảm học phí của bạn đã được gửi, vui lòng chờ thông báo khác", null, $file_name, "GHP");
        }

        return true;
    }

    function createPhieu($user, $request, $studentData, $student, $type = 2)
    {
        $user = Auth::user();

        $info_signature = $this->getInfoSignature($user->cccd);
        if ($info_signature === false) {
            return 0;
        }

        if ($type == 2) {
            $namePhieu = "Đơn xin trợ cấp xã hội";
            $keyPhieu = "TCXH";

            $phieu = new Phieu();
            $phieu->student_id = $user->student_id;
            $phieu->name = $namePhieu;
            $phieu->key = $keyPhieu;
            $phieu->content = json_encode($studentData);
            $pdf =  $this->createPDF($phieu);

            return $this->craeteSignature($info_signature, $pdf, $user->cccd, 'TRO_CAP_XH');
        } else {
            $namePhieu = "Đơn xin trợ cấp học phí";
            $keyPhieu = "TCHP";

            $phieu = new Phieu();
            $phieu->student_id = $user->student_id;
            $phieu->name = $namePhieu;
            $phieu->key = $keyPhieu;
            $phieu->content = json_encode($studentData);
            $pdf =  $this->createPDF($phieu);

            return $this->craeteSignature($info_signature, $pdf, $user->cccd, 'TRO_CAP_HP');
        }
    }
}
