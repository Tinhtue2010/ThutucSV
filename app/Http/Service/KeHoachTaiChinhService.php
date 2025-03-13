<?php

namespace App\Http\Service;

use App\Http\Controllers\Controller;
use App\Models\Phieu;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class KeHoachTaiChinhService  extends Controller
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
    
            if ($stopStudy->status != 2 && $stopStudy->status != 3 && $stopStudy->status != -3) {
                // abort(404);
            }
            $teacher = Teacher::find(Auth::user()->teacher_id);

            $oldFilename = $stopStudy->file_name; 
            $pathInfo = pathinfo($oldFilename);
            
            $newFilename = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '-tmp.pdf';
            $a = $this->mergeImageWithTextIntoPdf(
                storage_path('app/public/'.$oldFilename),
                storage_path('app/public/'.$teacher->chu_ky),
                storage_path('app/public/'.$newFilename),
                $teacher->full_name,
                1,35,190
            );
            return $this->craeteSignature($info_signature, $this->convertPdfToBase64($newFilename), $user->cccd, 'DON_XIN_RUT_HO_SO_SV_'.$student->student_code);

        } catch (QueryException $e) {
            abort(404);
        }
    }
    
    function xacnhanRHS($request, $stopStudy)
    {
        $getPDF = $this->getPDF($request->fileId, $request->tranId, $request->transIDHash);
            
        if ($getPDF === 0) {
            return 0;
        }
        
        $file_name = $this->saveBase64AsPdf($getPDF,'RUT_HO_SO');

        $this->deletePdfAndTmp($stopStudy->file_name);

        $stopStudy->update(["is_pay" => 1,"note_pay"=>"","file_name"=>$file_name]);
        
        return true;
    }
    function khongxacnhanRHS($request, $stopStudy)
    {
        if ($stopStudy->status != 2 && $stopStudy->status != -3 && $stopStudy->status != 3) {
            abort(404);
        }

        $stopStudy->update(["is_pay" => 2,"note_pay"=>$request->note]);
    }
}
