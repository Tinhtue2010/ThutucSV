<?php

namespace App\Http\Service;

use App\Http\Controllers\Controller;
use App\Models\Phieu;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PhongDaoTaoService extends Controller
{
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

    function bosunghsRHSPDF($request, $stopStudy)
    {
        if (!in_array($stopStudy->status, [2, -3, 3, -4])) {
            abort(404);
        }
        return $this->SendSignature($request, $stopStudy, "Phiếu hướng dẫn bổ sung hồ sơ", "RHS", -3, 3, "đơn xin rút hồ sơ", "BO_XUNG_HS");
    }

    function tuchoihsRHSPDF($request, $stopStudy)
    {
        if (!in_array($stopStudy->status, [2, -3, 3, -4])) {
            abort(404);
        }
        return $this->SendSignature($request, $stopStudy, "Phiếu từ chối hồ sơ", "TCGQ", -4, 4, "từ chối bổ sung hồ sơ", "TU_CHOI_HS");
    }
    function tiepnhanhsRHSPDF($request, $stopStudy)
    {
        if ($stopStudy->status != 2 && $stopStudy->status != -3 && $stopStudy->status != 3 && $stopStudy->status != -4) {
            abort(404);
        }


        return $this->SendSignature($request, $stopStudy, "Phiếu tiếp nhận hồ sơ", "TNHS", 3, 2, "đơn xin rút hồ sơ", "TIEP_NHAN_HS");
    }

    function saveFile($request, $stopStudy, $note, $notfi_content, $noti_key, $stopStudyUpdate, $folder, $tiepnhan = false)
    {
        $user = Auth::user();
        $getPDF = $this->getPDF($request->fileId, $request->tranId, $request->transIDHash);

        if ($getPDF === 0) {
            return 0;
        }

        $student = Student::find($stopStudy->student_id);

        if (!$tiepnhan) {
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
    function bosunghsRHS($request, $stopStudy)
    {
        return $this->saveFile($request, $stopStudy, "Yêu cầu bổ sung hồ sơ", "Đơn xin rút hồ sơ của bạn cần bổ sung hồ sơ", "RHS", ["status" => -3, "is_update" => 0], "BO_SUNG_HS_RHS");
    }


    function tuchoihsRHS($request, $stopStudy)
    {
        return $this->saveFile($request, $stopStudy, "Đã bị từ chối bởi phòng đào tạo", "Đơn xin rút hồ sơ của bạn bị từ chối bởi phòng đào tạo", "RHS", ["status" => -4], "TU_CHOI_GIAI_QUYET_RHS");;
    }

    function tiepnhanhsRHS($request, $stopStudy)
    {
        $notfi_content = "Đơn xin rút hồ sơ của bạn đã được nhận bởi phòng đào tạo vui lòng chờ kết quả";
        $noti_key = "RHS";
        $note = "Đã được nhận bởi phòng đào tạo";
        $stopStudyUpdate = ["status" => 3];

        return $this->saveFile($request, $stopStudy, $note, $notfi_content, $noti_key, $stopStudyUpdate, 'DON_XIN_RUT_HO_SO', true);
    }




    function bosunghsGHPPDF($request, $stopStudy)
    {
        if ($stopStudy->status == -3 || $stopStudy->status == -5 || $stopStudy->status == -6) {
            $stopStudy->update(["status" => 0]);
        }
        if ($stopStudy->status != 0 && $stopStudy->status != -1 && $stopStudy->status != 1 && $stopStudy->status != 2 && $stopStudy->status != -2) {
            abort(404);
        }

        return $this->SendSignature($request, $stopStudy, "Phiếu hướng dẫn bổ sung hồ sơ", "HDBSSH", -1, 1, "Đơn xin miễn giảm học phí", "BO_SUNG_HS_RHS_SV_");
    }

    function bosunghsGHP($request, $stopStudy)
    {
        $notfi_content = "Đơn xin miễn giảm học phí của bạn cần bổ sung hồ sơ";
        $noti_key = "HDBSSH";
        $note = "Yêu cầu bổ sung hồ sơ";
        $stopStudyUpdate = ["status" => -1, "is_update" => 0];

        return $this->saveFile($request, $stopStudy, $note, $notfi_content, $noti_key, $stopStudyUpdate, 'DON_XIN_RUT_HO_SO');
    }


    function bosunghsTCXHPDF($request, $stopStudy)
    {
        if ($stopStudy->status == -3 || $stopStudy->status == -5 || $stopStudy->status == -6) {
            $stopStudy->update(["status" => 0]);
        }
        if ($stopStudy->status != 0 && $stopStudy->status != -1 && $stopStudy->status != 1 && $stopStudy->status != 2 && $stopStudy->status != -2) {
            abort(404);
        }
        return $this->SendSignature($request, $stopStudy, "Phiếu hướng dẫn bổ sung hồ sơ", "HDBSSH", -1, 1, "đơn xin rút trợ cấp xã hội", "BO_XUNG_HS");
    }
    function bosunghsTCXH($request, $stopStudy)
    {
        $notfi_content = "Đơn xin trợ cấp xã hội của bạn cần bổ sung hồ sơ";
        $noti_key = "GHP";
        $note = "Yêu cầu bổ sung hồ sơ";
        $stopStudyUpdate = ["status" => -1, "is_update" => 0];

        return $this->saveFile($request, $stopStudy, $note, $notfi_content, $noti_key, $stopStudyUpdate, 'DON_XIN_RUT_HO_SO');
    }
    function tiepnhanhsGHPPDF($request, $stopStudy)
    {
        if ($stopStudy->status == -3 || $stopStudy->status == -5 || $stopStudy->status == -6) {
            $stopStudy->update(["status" => 0]);
        }
        if ($stopStudy->status != 0 && $stopStudy->status != -1 && $stopStudy->status != 1 && $stopStudy->status != 2 && $stopStudy->status != -2) {
            abort(404);
        }

        return $this->SendSignature($request, $stopStudy, "Phiếu tiếp nhận hồ sơ", "TNHS", 1, 0, "đơn xin rút hồ sơ", "TIEP_NHAN_HS");
    }
    function tiepnhanhsGHP($request, $stopStudy)
    {
        $notfi_content = "Đơn xin miễn giảm học phí của bạn đã được nhận bởi phòng đào tạo vui lòng chờ kết quả";
        $noti_key = "GHP";
        $note = "Đã được nhận bởi phòng đào tạo";
        $stopStudyUpdate = ["status" => 1];

        return $this->saveFile($request, $stopStudy, $note, $notfi_content, $noti_key, $stopStudyUpdate, 'GIAM_HOC_PHI');
    }

    function tiepnhanhsTCXHPDF($request, $stopStudy)
    {
        if ($stopStudy->status == -3 || $stopStudy->status == -5 || $stopStudy->status == -6) {
            $stopStudy->update(["status" => 0]);
        }
        if ($stopStudy->status != 0 && $stopStudy->status != -1 && $stopStudy->status != 1 && $stopStudy->status != 2 && $stopStudy->status != -2) {
            abort(404);
        }


        return $this->SendSignature($request, $stopStudy, "Phiếu tiếp nhận hồ sơ", "TNHS", 1, 0, "đơn xin trợ cấp xã hội", "TIEP_NHAN_HS");
    }

    function tiepnhanhsTCXH($request, $stopStudy)
    {
        $notfi_content = "Đơn xin trợ cấp xã hội của bạn đã được nhận bởi phòng đào tạo vui lòng chờ kết quả";
        $noti_key = "TCXH";
        $note = "Đã được nhận bởi phòng đào tạo";
        $stopStudyUpdate = ["status" => 1];

        return $this->saveFile($request, $stopStudy, $note, $notfi_content, $noti_key, $stopStudyUpdate, 'TRO_CAP_XA_HOI');
    }

    function tiepnhanhsTCHPPDF($request, $stopStudy)
    {
        if ($stopStudy->status == -3 || $stopStudy->status == -5 || $stopStudy->status == -6) {
            $stopStudy->update(["status" => 0]);
        }
        if ($stopStudy->status != 0 && $stopStudy->status != -1 && $stopStudy->status != 1 && $stopStudy->status != 2 && $stopStudy->status != -2) {
            abort(404);
        }


        return $this->SendSignature($request, $stopStudy, "Phiếu tiếp nhận hồ sơ", "TNHS", 1, 0, "đơn xin trợ cấp học phí", "TIEP_NHAN_HS");
    }

    function tiepnhanhsTCHP($request, $stopStudy)
    {
        $notfi_content = "Đơn xin trợ cấp học phí của bạn đã được nhận bởi phòng đào tạo vui lòng chờ kết quả";
        $noti_key = "GHP";
        $note = "Đã được nhận bởi phòng đào tạo";
        $stopStudyUpdate = ["status" => 1];

        return $this->saveFile($request, $stopStudy, $note, $notfi_content, $noti_key, $stopStudyUpdate, 'TRO_CAP_XA_HOI');
    }

    function tuchoihsGHPPDF($request, $stopStudy)
    {
        if ($stopStudy->status == -3 || $stopStudy->status == -5 || $stopStudy->status == -6) {
            $stopStudy->update(["status" => 0]);
        }
        if ($stopStudy->status != 0 && $stopStudy->status != -1 && $stopStudy->status != 1 && $stopStudy->status != 2 && $stopStudy->status != -2) {
            abort(404);
        }
        return $this->SendSignature($request, $stopStudy, "Phiếu từ chối hồ sơ", "TCGQ", 1, 1, "từ chối hồ sơ", "TU_CHOI_HS");
    }
    function tuchoihsTCXHPDF($request, $stopStudy)
    {
        if ($stopStudy->status == -3 || $stopStudy->status == -5 || $stopStudy->status == -6) {
            $stopStudy->update(["status" => 0]);
        }
        if ($stopStudy->status != 0 && $stopStudy->status != -1 && $stopStudy->status != 1 && $stopStudy->status != 2 && $stopStudy->status != -2) {
            abort(404);
        }
        return $this->SendSignature($request, $stopStudy, "Phiếu từ chối hồ sơ", "TCGQ", 1, 0, "từ chối hồ sơ", "TU_CHOI_HS");
    }
    function tuchoihsTCHPPDF($request, $stopStudy)
    {
        if ($stopStudy->status == -3 || $stopStudy->status == -5 || $stopStudy->status == -6) {
            $stopStudy->update(["status" => 0]);
        }
        if ($stopStudy->status != 0 && $stopStudy->status != -1 && $stopStudy->status != 1 && $stopStudy->status != 2 && $stopStudy->status != -2) {
            abort(404);
        }
        return $this->SendSignature($request, $stopStudy, "Phiếu từ chối hồ sơ", "TCGQ", -2, 0, "từ chối hồ sơ", "TU_CHOI_HS");
    }
    function tuchoihsCDCSPDF($request, $stopStudy)
    {
        if ($stopStudy->status == -3 || $stopStudy->status == -5 || $stopStudy->status == -6) {
            $stopStudy->update(["status" => 0]);
        }
        if ($stopStudy->status != 0 && $stopStudy->status != -1 && $stopStudy->status != 1 && $stopStudy->status != 2 && $stopStudy->status != -2) {
            abort(404);
        }
        return $this->SendSignature($request, $stopStudy, "Phiếu từ chối hồ sơ", "TCGQ", -2, 0, "từ chối hồ sơ", "TU_CHOI_HS");
    }


    function tuchoihsGHP($request, $stopStudy)
    {
        return $this->saveFile($request, $stopStudy, "Đã bị từ chối bởi phòng đào tạo", "Đơn xin giảm học phí của bạn bị từ chối bởi phòng đào tạo", "GHP", ["status" => -2], "TU_CHOI_GIAI_QUYET_GIAM_HOC_PHI");;
    }

    function tiepnhanhsCDCS($request, $stopStudy)
    {
        if ($stopStudy->status == -3 || $stopStudy->status == -5 || $stopStudy->status == -6) {
            $stopStudy->update(["status" => 0]);
        }
        if ($stopStudy->status != 0 && $stopStudy->status != -1 && $stopStudy->status != 1 && $stopStudy->status != 2 && $stopStudy->status != -2) {
            abort(404);
        }

        if ($stopStudy->status == -1 || $stopStudy->status == -2) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            if ($newStopStudy) {
                try {
                    $phieu = Phieu::find($newStopStudy->phieu_id);
                    if ($phieu) {
                        $phieu->delete();
                    }
                    $newStopStudy->delete();
                    $stopStudy->update(["status" => 0]);
                } catch (\Exception $e) {
                }
            } else {
            }
        }

        if ($request->button_clicked == "huy_phieu" && $stopStudy->status == 1) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            if ($newStopStudy) {
                try {
                    $phieu = Phieu::find($newStopStudy->phieu_id);
                    if ($phieu) {
                        $phieu->delete();
                    }
                    $newStopStudy->delete();
                    $stopStudy->update(["status" => 0]);

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
        $student = Student::find($stopStudy->student_id);

        $teacher = Teacher::find(Auth::user()->teacher_id);

        $content_phieu['giaovien'] = $teacher->full_name ?? '';
        $content_phieu['sinhvien'] = $student->full_name ?? '';
        $content_phieu['chuky'] = $teacher->chu_ky ?? '';
        $content_phieu['cmnd'] = $student->cmnd ?? '';
        $content_phieu['ngaycap'] = $student->date_range_cmnd ?? '';
        $content_phieu['sdt'] = $student->phone ?? '';
        $content_phieu['email'] = $student->email ?? '';

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

        $timestamp = strtotime(str_replace('/', '-', $request->thoigiantiepnhan));
        $content_phieu['tiepnhan_day'] = date('d', $timestamp);
        $content_phieu['tiepnhan_month'] = date('m', $timestamp);
        $content_phieu['tiepnhan_year'] = date('Y', $timestamp);
        $content_phieu['tiepnhan_gio'] = date('H', $timestamp);
        $content_phieu['tiepnhan_phut'] = date('i', $timestamp);

        $timestamp = strtotime(str_replace('/', '-', $request->thoigiantraketqua));
        $content_phieu['ketqua_day'] = date('d', $timestamp);
        $content_phieu['ketqua_month'] = date('m', $timestamp);
        $content_phieu['ketqua_year'] = date('Y', $timestamp);
        $content_phieu['ketqua_gio'] = date('H', $timestamp);
        $content_phieu['ketqua_phut'] = date('i', $timestamp);

        $tmp = [];
        foreach ($request->tengiayto as $index => $item) {
            $tmp[] = [
                "tengiayto" => $request->tengiayto[$index] ?? '',
                "hinhthuc" => $request->hinhthuc[$index] ?? '',
                "ghichu" => $request->ghichu[$index] ?? '',
            ];
        }
        $content_phieu['bang'] = $tmp;

        $content_phieu['ndgiaiquyet'] = "đơn xin trợ học phí";

        $user_id = User::where('student_id', $stopStudy->student_id)->first()->id;
        $this->notification("Đơn xin trợ cấp học phí của bạn đã được nhận bởi phòng đào tạo vui lòng chờ kết quả", null, "GHP", $user_id);


        if ($stopStudy->status == 0 || $stopStudy->status == 2) {

            $phieu = new Phieu();
            $phieu->student_id = $stopStudy->student_id;
            $phieu->teacher_id = Auth::user()->teacher_id;
            $phieu->name = "Phiếu tiếp nhận hồ sơ";
            $phieu->key = "CDCS";
            $phieu->content = json_encode($content_phieu);
            $phieu->save();

            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->status = 1;
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->phieu_id = $phieu->id;
            $newStopStudy->parent_id = $request->id;
            $newStopStudy->note = "Đã được nhận bởi phòng đào tạo";
            $newStopStudy->save();
            $stopStudy->update(["status" => 1]);
            return $phieu->id;
        }
        if ($stopStudy->status == 0) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->note = "Đã được nhận bởi phòng đào tạo";
            $newStopStudy->save();

            $phieu = Phieu::find($newStopStudy->phieu_id);
            $phieu->student_id = $stopStudy->student_id;
            $phieu->teacher_id = Auth::user()->teacher_id;
            $phieu->name = "Phiếu hướng dẫn bổ sung hồ sơ";
            $phieu->key = "CDCS";
            $phieu->content = json_encode($content_phieu);
            $phieu->save();
            $stopStudy->update(["status" => 1]);
            return $phieu->id;
        }

        abort(404);
    }



    function tuchoihsTCXH($request, $stopStudy)
    {
        return $this->saveFile($request, $stopStudy, "Đã bị từ chối bởi phòng đào tạo", "Đơn xin trợ cấp xã hội của bạn bị từ chối bởi phòng đào tạo", "TCXH", ["status" => -2], "TU_CHOI_GIAI_QUYET_TRO_CAP_XA_HOI");;
    }

    function tuchoihsTCHP($request, $stopStudy)
    {
        return $this->saveFile($request, $stopStudy, "Đã bị từ chối bởi phòng đào tạo", "Đơn xin trợ cấp học phí của bạn bị từ chối bởi phòng đào tạo", "TCHP", ["status" => -2], "TU_CHOI_GIAI_QUYET_TRO_CAP_HOC_PHI");;
    }
    function tuchoihsCDCS($request, $stopStudy)
    {
        return $this->saveFile($request, $stopStudy, "Đã bị từ chối bởi phòng đào tạo", "Đơn xin trợ cấp học phí của bạn bị từ chối bởi phòng đào tạo", "CDCS", ["status" => -2], "TU_CHOI_GIAI_QUYET_TRO_CAP_HOC_PHI");;
    }


    function duyethsRHS($request, $stopStudy)
    {
        if ($stopStudy->status != 3 && $stopStudy->status != -4 && $stopStudy->status != 4) {
            abort(404);
        }
        if ($stopStudy->status == 4) {
            return true;
        }


        $content_phieu['ndgiaiquyet'] = "đơn xin rút hồ sơ";

        $newStopStudy = $stopStudy->replicate();
        $newStopStudy->status = 1;
        $newStopStudy->teacher_id = Auth::user()->teacher_id;
        $newStopStudy->parent_id = $request->id;
        $newStopStudy->note = "Đang chờ cán bộ phòng CTSV xác nhận";
        $newStopStudy->save();
        $stopStudy->update(["status" => 4]);
        return true;
    }
    function duyethsGHP($request, $stopStudy)
    {
        if ($stopStudy->status != 1) {
            abort(404);
        }

        $newStopStudy = $stopStudy->replicate();
        $newStopStudy->status = 1;
        $newStopStudy->teacher_id = Auth::user()->teacher_id;
        $newStopStudy->parent_id = $request->id;
        $newStopStudy->note = "Đang chờ cán bộ phòng CTSV xác nhận";
        $newStopStudy->save();
        $stopStudy->update(["status" => 2]);
        return true;


        abort(404);
    }
    function duyethsTCXH($request, $stopStudy)
    {
        if ($stopStudy->status != 1) {
            abort(404);
        }

        $newStopStudy = $stopStudy->replicate();
        $newStopStudy->status = 1;
        $newStopStudy->teacher_id = Auth::user()->teacher_id;
        $newStopStudy->parent_id = $request->id;
        $newStopStudy->note = "Đang chờ cán bộ phòng CTSV xác nhận";
        $newStopStudy->save();
        $stopStudy->update(["status" => 2]);
        return true;


        abort(404);
    }
}
