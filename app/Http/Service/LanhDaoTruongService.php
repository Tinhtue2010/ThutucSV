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
use Illuminate\Support\Facades\DB;

class LanhDaoTruongService  extends Controller
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
    function parseDateTime($dateString)
    {
        try {
            return $dateString ? Carbon::createFromFormat('d/m/Y H:i', trim($dateString)) : null;
        } catch (\Exception $e) {
            return null;
        }
    }
    function SendSignature($request, $stopStudy, $phieu_name, $phieu_key, $status_huy, $update_huy, $ndgiaiquyet = null, $folder = "TMP_")
    {
        DB::beginTransaction();
        try {

            if (in_array($request->button_clicked, ['huy_phieu', 'tu_choi_phieu']) && $stopStudy->status == $status_huy) {
                $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
                if ($newStopStudy) {
                    try {
                        $newStopStudy->delete();
                        $stopStudy->update(["status" => $update_huy]);
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
            $teacher = Teacher::find($user->teacher_id);
            $student = Student::find($stopStudy->student_id);

            $info_signature = $this->getInfoSignature($user->cccd);
            if ($info_signature === false) {
                return 0;
            }

            $tiepnhan = $this->parseDateTime($request->thoigiantiepnhan ?? '');
            $ketqua = $this->parseDateTime($request->thoigiantraketqua ?? '');

            $content_phieu = [
                'bosunggiayto'     => $request->bosunggiayto ?? '',
                'kekhailaigiayto'  => $request->kekhailaigiayto ?? '',
                'huongdankhac'     => $request->huongdankhac ?? '',
                'lydo'             => $request->lydo ?? '',
                'giaovien'         => $teacher->full_name ?? '',
                'chuky'            => $teacher->chu_ky ?? '',
                'sinhvien'         => $student->full_name ?? '',
                'cmnd'             => $student->cmnd ?? '',
                'sdt'              => $student->phone ?? '',
                'email'            => $student->email ?? '',
                'ngaycap'          => $student->date_range_cmnd ? Carbon::parse($student->date_range_cmnd)->format('d/m/Y') : '',
                'day'              => now()->day,
                'month'            => now()->month,
                'year'             => now()->year,
                'chu_ky'           => $this->convertImageToBase64($user->getUrlChuKy()),

                'tiepnhan_day'   => $tiepnhan?->day ?? '',
                'tiepnhan_month' => $tiepnhan?->month ?? '',
                'tiepnhan_year'  => $tiepnhan?->year ?? '',
                'tiepnhan_gio'   => $tiepnhan?->hour ?? '',
                'tiepnhan_phut'  => $tiepnhan?->minute ?? '',

                'ketqua_day'   => $ketqua?->day ?? '',
                'ketqua_month' => $ketqua?->month ?? '',
                'ketqua_year'  => $ketqua?->year ?? '',
                'ketqua_gio'   => $ketqua?->hour ?? '',
                'ketqua_phut'  => $ketqua?->minute ?? '',

                'bang' => isset($request->tengiayto) ? array_map(
                    fn($index) => [
                        "tengiayto" => $request->tengiayto[$index] ?? '',
                        "hinhthuc"  => $request->hinhthuc[$index] ?? '',
                        "ghichu"    => $request->ghichu[$index] ?? '',
                    ],
                    array_keys($request->tengiayto)
                ) : [],

                'ndgiaiquyet' => $ndgiaiquyet ?? "đơn xin giảm học phí",
            ];
            $phieu = new Phieu();
            $phieu->student_id = $stopStudy->student_id;
            $phieu->teacher_id = $user->teacher_id;
            $phieu->name = $phieu_name;
            $phieu->key = $phieu_key;
            $phieu->content = json_encode($content_phieu);

            $pdf = $this->createPDF($phieu);
            $signature = $this->craeteSignature($info_signature, $pdf, $user->cccd,  $folder . '_' . $student->student_code);
            DB::commit();
            return $signature;
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Đã có lỗi xảy ra: " . $e->getMessage()], 500);
        }
    }

    function saveFile($request, $stopStudy, $note, $notfi_content, $noti_key, $stopStudyUpdate, $folder, $tiepnhan = false)
    {
        $user = Auth::user();
        $getPDF = $this->getPDF($request->fileId, $request->tranId, $request->transIDHash);

        if ($getPDF === 0) {
            return 0;
        }
        if (!$tiepnhan) {
            $student = Student::find($stopStudy->student_id);

            $file_name = $this->saveBase64AsPdf($getPDF, $folder . '/' . $student->student_code);

            $this->deletePdfAndTmp($stopStudy->file_name, $file_name);
        }


        $user_id = User::where('student_id', $stopStudy->student_id)->first()->id;
        $this->notification($notfi_content, null, $noti_key, $user_id);

        $newStopStudy = $stopStudy->replicate();
        $newStopStudy->status = 0;
        $newStopStudy->teacher_id = $user->teacher_id;
        if (!$tiepnhan) {
            $newStopStudy->file_name = $file_name;
        }
        $newStopStudy->parent_id = $request->id;
        $newStopStudy->note = $note;
        $newStopStudy->save();
        $stopStudy->update($stopStudyUpdate);
        return;
    }

    function xacnhanRHSPDF($request, $stopStudy)
    {
        try {
            $user = $this->getAuthenticatedUser();
            $student = $this->getStudent($stopStudy->student_id);
            $teacher = $this->getTeacher($user->teacher_id);

            $info_signature = $this->getInfoSignature($user->cccd);
            if (!$info_signature) {
                return 0;
            }

            if (!in_array($stopStudy->status, [5, -6, 6])) {
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
                1,
                35,
                250
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
        try {
            $getPDF = $this->getPDF($request->fileId, $request->tranId, $request->transIDHash);
            if (!$getPDF) {
                return 0;
            }


            $student = Student::find($stopStudy->student_id);

            $file_name = $this->saveBase64AsPdf($getPDF, 'DON_XIN_RUT_HO_SO/' . $student->student_code);
            $this->deletePdfAndTmp($stopStudy->file_name, $file_name);
            $stopStudy->update(["status" => 6, "file_name" => $file_name]);



            Student::where('id', $stopStudy->student_id)->update(['status' => 1]);
            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->status = 1;
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->parent_id = $request->id;
            $newStopStudy->file_name = null;

            $user_id = User::where('student_id', $stopStudy->student_id)->first()->id;
            $this->notification("Đơn xin rút hồ sơ của bạn đã được lãnh đạo trường xác nhận", null, null, "RHS", $user_id);

            $newStopStudy->note = $request->note;

            $newStopStudy->save();
            return true;
        } catch (QueryException $e) {
            abort(404);
        }
    }
    function tuchoihsRHSPDF($request, $stopStudy)
    {
        if ($stopStudy->status != 5 && $stopStudy->status != -6 && $stopStudy->status != 6) {
            abort(404);
        }
        return $this->SendSignature($request, $stopStudy, "Phiếu từ chối hồ sơ", "TCGQ", -6, 5, "từ chối rút hồ sơ", "TU_CHOI_HS");
    }
    function tuchoihsRHS($request, $stopStudy)
    {
        return $this->saveFile($request, $stopStudy, "Yêu cầu rút hồ sơ của bạn cần bổ sung hồ sơ", "Đơn xin rút hồ sơ của bạn chưa thể duyệt", "RHS", ["status" => -6, "is_update" => 0], "RUT_HS");
    }
}
