<?php

namespace App\Http\Service;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KeHoachTaiChinhService extends Controller
{
    function KyDonPdfRHS($request, $stopStudy)
    {
        try {
            $user = Auth::user();
            $student = Student::find($stopStudy->student_id);
            if (!$student) {
                return 0; // Không tìm thấy sinh viên
            }

            $info_signature = $this->getInfoSignature($user->cccd);
            if ($info_signature === false) {
                return 0; // Chưa đăng ký chữ ký số
            }

            if (!in_array($stopStudy->status, [2, 3, -3])) {
                return 0;
            }

            $teacher = Teacher::find($user->teacher_id);
            if (!$teacher) {
                return 0;
            }

            $oldFilename = $stopStudy->file_name;
            $pathInfo = pathinfo($oldFilename);
            $newFilename = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '-tmp.pdf';

            $this->mergeImageWithTextIntoPdf(
                storage_path('app/public/' . $oldFilename),
                storage_path('app/public/' . $teacher->chu_ky),
                storage_path('app/public/' . $newFilename),
                $teacher->full_name,
                1, 35, 190
            );

            return $this->craeteSignature(
                $info_signature,
                $this->convertPdfToBase64($newFilename),
                $user->cccd,
                'DON_XIN_RUT_HO_SO_SV_' . $student->student_code
            );
        } catch (QueryException $e) {
            abort(404);
        }
    }

    function xacnhanRHS($request, $stopStudy)
    {
        DB::beginTransaction();
        try {
            $getPDF = $this->getPDF($request->fileId, $request->tranId, $request->transIDHash);
            if ($getPDF === 0) {
                return 0;
            }

            $file_name = $this->saveBase64AsPdf($getPDF, 'RUT_HO_SO');
            $this->deletePdfAndTmp($stopStudy->file_name);

            $stopStudy->update([
                "is_pay" => 1,
                "note_pay" => "",
                "file_name" => $file_name
            ]);

            DB::commit();
            return true;
        } catch (QueryException $e) {
            DB::rollBack();
            abort(404);
        }
    }

    function khongxacnhanRHS($request, $stopStudy)
    {
        DB::beginTransaction();
        try {
            if (!in_array($stopStudy->status, [2, 3, -3])) {
                abort(404);
            }

            $stopStudy->update([
                "is_pay" => 2,
                "note_pay" => $request->note
            ]);

            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            abort(404);
        }
    }
}
