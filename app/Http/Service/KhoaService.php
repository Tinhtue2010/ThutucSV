<?php

namespace App\Http\Service;

use App\Http\Controllers\Controller;
use App\Models\Phieu;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KhoaService extends Controller
{
    private function getAuthenticatedUser()
    {
        return Auth::user();
    }

    private function getStudent($studentId)
    {
        return Student::find($studentId);
    }

    private function getTeacher($teacherId)
    {
        return Teacher::find($teacherId);
    }

    function KyDonPdfRHS($request, $stopStudy)
    {
        try {
            $user = $this->getAuthenticatedUser();
            $student = $this->getStudent($stopStudy->student_id);
            $teacher = $this->getTeacher($user->teacher_id);

            $info_signature = $this->getInfoSignature($user->cccd);
            if (!$info_signature) {
                return 0;
            }

            if (!in_array($stopStudy->status, [0, 1, -2])) {
                abort(404);
            }

            $oldFilename = $stopStudy->file_name;
            $pathInfo = pathinfo($oldFilename);
            $newFilename = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '-tmp.pdf';

            $this->mergeImageWithTextIntoPdf(
                storage_path('app/public/' . $oldFilename),
                storage_path('app/public/' . $teacher->chu_ky),
                storage_path('app/public/' . $newFilename),
                $teacher->full_name,
                1, 120, 190
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
            if (!$getPDF) {
                return 0;
            }

            $file_name = $this->saveBase64AsPdf($getPDF, 'RUT_HO_SO');
            $this->deletePdfAndTmp($stopStudy->file_name);
            $stopStudy->update(["status" => 2, "file_name" => $file_name]);

            $newStopStudy = $stopStudy->replicate()->fill([
                "status" => 1,
                "teacher_id" => Auth::user()->teacher_id,
                "parent_id" => $request->id,
                "file_name" => null, // Thêm file_name = null
                "note" => $request->note
            ]);

            $user_id = User::where('student_id', $stopStudy->student_id)->value('id');
            $this->notification("Đơn xin rút hồ sơ của bạn đã được cán bộ khoa xác nhận", null, "RHS", $user_id);

            $newStopStudy->save();
            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            abort(404);
        }
    }

    function khongxacnhanRHS($request, $stopStudy)
    {
        DB::beginTransaction();
        try {
            if (!in_array($stopStudy->status, [0, 1, 2])) {
                abort(404);
            }

            $stopStudy->update(["status" => -2]);

            $newStopStudy = $stopStudy->replicate()->fill([
                "status" => 0,
                "teacher_id" => Auth::user()->teacher_id,
                "phieu_id" => null,
                "parent_id" => $request->id,
                "file_name" => null, // Thêm file_name = null
                "note" => $request->note
            ]);

            $user_id = User::where('student_id', $stopStudy->student_id)->value('id');
            $this->notification("Đơn xin rút của bạn đã bị từ chối bởi giáo viên chủ nhiệm", null, "RHS", $user_id);

            $newStopStudy->save();
            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            abort(404);
        }
    }
}
