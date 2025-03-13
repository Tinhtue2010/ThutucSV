<?php

namespace App\Http\Service;

use App\Http\Controllers\Controller;
use App\Models\Phieu;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class KhoaService  extends Controller
{
    function KyDonPdfRHS($request, $stopStudy)
    {
        try {
            $user = Auth::user();
            $student = Student::where('id',$stopStudy->student_id)->first();
    
            $info_signature = $this->getInfoSignature($user->cccd);
            if ($info_signature === false) {
                return 0; //chưa đăng ký chữ ký số cần đăng ký chữ ký số
            }
    
            if ($stopStudy->status != 0 && $stopStudy->status != 1 && $stopStudy->status != -2) {
                // abort(404);
            }
            $teacher = Teacher::find(Auth::user()->teacher_id);

            $oldFilename = $stopStudy->file_name; 
            $pathInfo = pathinfo($oldFilename);
            
            $newFilename = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '-tmp.pdf';
            $this->mergeImageWithTextIntoPdf(
                storage_path('app/public/'.$oldFilename),
                storage_path('app/public/'.$teacher->chu_ky),
                storage_path('app/public/'.$newFilename),
                $teacher->full_name,
                1,120,190
            );
            return $this->craeteSignature($info_signature, $this->convertPdfToBase64($newFilename), $user->cccd, 'DON_XIN_RUT_HO_SO_SV_'.$student->student_code);

        } catch (QueryException $e) {
            abort(404);
        }
    }
    function xacnhanRHS($request, $stopStudy)
    {
        try {

            $getPDF = $this->getPDF($request->fileId, $request->tranId, $request->transIDHash);
            
            if ($getPDF === 0) {
                return 0;
            }
            
            $file_name = $this->saveBase64AsPdf($getPDF,'RUT_HO_SO');

            $this->deletePdfAndTmp($stopStudy->file_name);

            $stopStudy->update(["status" => 2,"file_name"=>$file_name]);


            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->status = 1;
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->parent_id = $request->id;
            $newStopStudy->file_name = null;
            $user_id = User::where('student_id',$stopStudy->student_id)->first()->id;
            $this->notification("Đơn xin rút hồ sơ của bạn đã được cán bộ khoa xác nhận", null, "RHS",$user_id);

            $newStopStudy->note = $request->note;


            $newStopStudy->save();
        } catch (QueryException $e) {
            abort(404);
        }
    }
    function khongxacnhanRHS($request, $stopStudy)
    {
        try {
            if ($stopStudy->status != 0 && $stopStudy->status != 1 && $stopStudy->status != 2) {
                abort(404);
            }
            $stopStudy->update(["status" => -2]);

            $phieu = Phieu::find($stopStudy->phieu_id);
            $phieu_content = json_decode($phieu->content,true);
            $phieu_content['khoa_xac_nhan'] = "";
            $phieu->content = json_encode($phieu_content,true);
            $phieu->save();

            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->status = 0;
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->phieu_id = null;
            $newStopStudy->parent_id = $request->id;
            $user_id = User::where('student_id',$stopStudy->student_id)->first()->id;
            $this->notification("Đơn xin rút của bạn đã bị từ chối bởi giáo viên chủ nhiệm", null, "RHS",$user_id);

            $newStopStudy->note = $request->note;
            $newStopStudy->save();
        } catch (QueryException $e) {
            abort(404);
        }
    }
}
