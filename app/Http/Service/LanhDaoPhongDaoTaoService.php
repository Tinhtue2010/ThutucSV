<?php

namespace App\Http\Service;

use App\Http\Controllers\Controller;
use App\Models\Phieu;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class LanhDaoPhongDaoTaoService  extends Controller
{
    function tuchoihsRHSPDF($request, $stopStudy)
    {
        try {

            if ($stopStudy->status != 4 && $stopStudy->status != -5 && $stopStudy->status != 5) {
                abort(404);
            }

            if ($request->button_clicked == "huy_phieu" && $stopStudy->status == -5) {
                $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
                if ($newStopStudy) {
                    try {
                        $newStopStudy->delete();
                        $stopStudy->update(["status" => 4]);

                        return true;
                    } catch (\Exception $e) {
                        abort(404);
                    }
                } else {
                    abort(404);
                }
            }
            if ($request->button_clicked == "huy_phieu") {
                return true;
            }
            $user = Auth::user();
            $info_signature = $this->getInfoSignature($user->cccd);
            if ($info_signature === false) {
                return 0;
            }


            $student = Student::find($stopStudy->student_id);

            $teacher = Teacher::find(Auth::user()->teacher_id);

            $content_phieu['giaovien'] = $teacher->full_name ?? '';
            $content_phieu['sinhvien'] = $student->full_name ?? '';
            $content_phieu['cmnd'] = $student->cmnd ?? '';
            $content_phieu['ngaycap'] = $student->date_range_cmnd ?? '';
            $content_phieu['sdt'] = $student->phone ?? '';
            $content_phieu['email'] = $student->email ?? '';

            $chu_ky =  $this->convertImageToBase64($user->getUrlChuKy());
            $content_phieu['chu_ky'] = $chu_ky;

            if ($student->date_range_cmnd == null) {
                $content_phieu['ngaycap'] = '';
            } else {
                $date = substr($student->date_range_cmnd, 0, 10);
                $formattedDate = Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
                $content_phieu['ngaycap'] = $formattedDate;
            }

            $content_phieu['day'] = Carbon::now()->day;
            $content_phieu['month'] = Carbon::now()->month;
            $content_phieu['year'] = Carbon::now()->year;

            $content_phieu['lydo'] = $request->lydo;

            $content_phieu['ndgiaiquyet'] = "đơn xin rút hồ sơ";


            $phieu = new Phieu();
            $phieu->student_id = $stopStudy->student_id;
            $phieu->teacher_id = Auth::user()->teacher_id;
            $phieu->name = "Phiếu từ chối giải quyết hồ sơ";
            $phieu->key = "TCGQ";
            $phieu->content = json_encode($content_phieu);
            $pdf = $this->createPDF($phieu);
            return $this->craeteSignature($info_signature, $pdf, $user->cccd, 'TU_CHOI_RHS' . $student->student_code);
        } catch (\Throwable $th) {
            abort(404);
        }
    }
    function tuchoihsRHS($request, $stopStudy)
    {
        try {

            if ($stopStudy->status != 4 && $stopStudy->status != -5 && $stopStudy->status != 5) {
                abort(404);
            }
            $getPDF = $this->getPDF($request->fileId, $request->tranId, $request->transIDHash);
            if ($getPDF === 0) {
                return 0;
            }
            $file_name = $this->saveBase64AsPdf($getPDF, 'TU_CHOI_HS_RHS');

            $this->deletePdfAndTmp($stopStudy->file_name, $file_name);



            $this->notification("Đơn xin rút hồ sơ của bạn bị từ chối bởi lãnh đạo phòng CTSV ", null, "RHS", $stopStudy->student_id);


            if ($stopStudy->status == 4  || $stopStudy->status == 5) {


                $newStopStudy = $stopStudy->replicate();
                $newStopStudy->status = 0;
                $newStopStudy->teacher_id = Auth::user()->teacher_id;
                $newStopStudy->file_name = $file_name;
                $newStopStudy->parent_id = $request->id;
                $newStopStudy->note = "Đã bị từ chối bởi lãnh đạo phòng đào tạo";
                $newStopStudy->save();
                $stopStudy->update(["status" => -5]);
                return;
            }
        } catch (\Throwable $th) {
            abort(404);
        }
    }

    function xacnhanRHSPDF($request, $stopStudy)
    {
        try {
            if (!in_array($stopStudy->status, [4, -5, 5])) {
                abort(404);
            }
            $user = Auth::user();
            $student = Student::find($stopStudy->student_id);
            $teacher = Teacher::find($user->teacher_id);

            $info_signature = $this->getInfoSignature($user->cccd);
            if (!$info_signature) {
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
                1, 120, 250
            );

            $newStopStudy = $stopStudy->replicate(["phieu_id", "status", "teacher_id", "parent_id", "note"]);
            $newStopStudy->fill([
                "status" => 1,
                "teacher_id" => $teacher->id,
                "parent_id" => $request->id,
                "note" => $request->note,
            ])->save();

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
        try {
            $getPDF = $this->getPDF($request->fileId, $request->tranId, $request->transIDHash);
            if (!$getPDF) {
                return 0;
            }
            $user = Auth::user();
            $teacher = Teacher::find($user->teacher_id);

            
            $student = Student::find($stopStudy->student_id);

            $file_name = $this->saveBase64AsPdf($getPDF, 'DON_XIN_RUT_HO_SO/'.$student->student_code);
            $this->deletePdfAndTmp($stopStudy->file_name, $file_name);
            $stopStudy->update(["status" => 5, "file_name" => $file_name]);


            $newStopStudy = $stopStudy->replicate(["phieu_id", "status", "teacher_id", "parent_id", "note"]);
            $newStopStudy->fill([
                "status" => 1,
                "teacher_id" => $teacher->id,
                "parent_id" => $request->id,
                "note" => $request->note,
                "file_name" => null
            ])->save();

            $user_id = User::where('student_id', $stopStudy->student_id)->value('id');
            $this->notification("Đơn xin rút hồ sơ của bạn đã được lãnh đạo phòng CTSV xác nhận", null, null, "RHS", $user_id);

            return true;
        } catch (QueryException $e) {
            abort(404);
        }
    }
}
